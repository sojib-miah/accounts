<?php

namespace App\Http\Controllers;

use App\Models\CompanyPackage;
use App\Models\Package;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class SSLCommerzController extends Controller
{
    /**
     * Initiate a new payment.
     */
    public function initiate(Request $request, Package $package)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('admin.login')
                ->with('error', 'Please login before payment.');
        }

        if (!$package->is_active) {
            return back()->with('error', 'Package is unavailable.');
        }

        if (!is_numeric($package->price) || $package->price <= 0) {
            return back()->with('error', 'Invalid package price.');
        }

        // Prevent duplicate pending payments for 30 minutes.
        $existingPayment = Payment::where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->where('status', 'Pending')
            ->where('created_at', '>=', now()->subMinutes(30))
            ->latest('id')
            ->first();

        if ($existingPayment) {
            return redirect()->route(
                'payment.result',
                $existingPayment->id
            )->with('info', 'You already have a pending payment.');
        }

        $tranId = 'COMITS-' . now()->format('YmdHis')
            . '-' . strtoupper(Str::random(8));

        $payment = Payment::create([
            'user_id'       => $user->id,
            'company_id'    => $user->company_id,
            'package_id'    => $package->id,
            'tran_id'       => $tranId,
            'amount'        => $package->price,
            'currency'      => 'BDT',
            'status'        => 'Pending',
        ]);

        $baseUrl = config('services.sslcommerz.mode') === 'live'
            ? 'https://securepay.sslcommerz.com'
            : 'https://sandbox.sslcommerz.com';

        $data = [
            'store_id'       => config('services.sslcommerz.store_id'),
            'store_passwd'   => config('services.sslcommerz.store_password'),
            'total_amount'   => number_format(
                (float) $payment->amount,
                2,
                '.',
                ''
            ),
            'currency'       => 'BDT',
            'tran_id'        => $payment->tran_id,

            'success_url' => route('payment.sslcommerz.success'),
            'fail_url'    => route('payment.sslcommerz.fail'),
            'cancel_url'  => route('payment.sslcommerz.cancel'),
            'ipn_url'     => route('payment.sslcommerz.ipn'),

            'cus_name'    => $user->name ?: 'Customer',
            'cus_email'   => $user->email ?: 'customer@example.com',
            'cus_add1'    => 'Bangladesh',
            'cus_city'    => 'Dhaka',
            'cus_country' => 'Bangladesh',
            'cus_phone'   => $user->phone ?: '01700000000',

            'product_name'     => $package->name,
            'product_category' => 'Software Subscription',
            'product_profile'  => 'general',

            'value_a' => (string) $payment->id,
            'value_b' => (string) $package->id,
            'value_c' => (string) $user->id,
            'value_d' => (string) ($user->company_id ?? ''),

            'success_url' => config('services.sslcommerz.success_url'),
            'fail_url'    => config('services.sslcommerz.fail_url'),
            'cancel_url'  => config('services.sslcommerz.cancel_url'),
            'ipn_url'     => config('services.sslcommerz.ipn_url'),
        ];

        try {
            $response = Http::asForm()
                ->timeout(30)
                ->post($baseUrl . '/gwprocess/v4/api.php', $data);

            $result = $response->json() ?? [];

            if (
                $response->successful()
                && ($result['status'] ?? '') === 'SUCCESS'
                && !empty($result['GatewayPageURL'])
            ) {
                return redirect()->away($result['GatewayPageURL']);
            }

            $payment->update([
                'status' => 'Failed',
                'gateway_response' => json_encode(
                    $result ?: ['response' => $response->body()],
                    JSON_UNESCAPED_UNICODE
                ),
            ]);

            return redirect()->route(
                'payment.result',
                $payment->id
            )->with('error', 'Unable to initialize SSLCommerz payment.');
        } catch (Throwable $e) {
            report($e);

            $payment->update([
                'status' => 'Failed',
                'gateway_response' => $e->getMessage(),
            ]);

            return redirect()->route(
                'payment.result',
                $payment->id
            )->with('error', 'Payment initialization failed.');
        }
    }

    /**
     * Customer returns from SSLCommerz after payment.
     */
    public function success(Request $request)
    {
        Log::info('SSLCommerz SUCCESS callback received', [
            'tran_id' => $request->input('tran_id'),
            'val_id' => $request->input('val_id'),
            'ip' => $request->ip(),
        ]);
        return $this->processPayment($request, false);
    }

    /**
     * Server-to-server notification.
     */
    public function ipn(Request $request)
    {
        Log::info('SSLCommerz IPN received', [
            'tran_id' => $request->input('tran_id'),
            'val_id' => $request->input('val_id'),
        ]);
        return $this->processPayment($request, true);
    }

    /**
     * Payment failed.
     */
    public function fail(Request $request)
    {
        $payment = Payment::where(
            'tran_id',
            $request->input('tran_id')
        )->first();

        if ($payment) {
            DB::transaction(function () use ($payment, $request) {
                $locked = Payment::whereKey($payment->id)
                    ->lockForUpdate()
                    ->first();

                // Never downgrade a successfully paid transaction.
                if ($locked && $locked->status === 'Pending') {
                    $locked->update([
                        'status' => 'Failed',
                        'gateway_response' => json_encode(
                            $request->all(),
                            JSON_UNESCAPED_UNICODE
                        ),
                    ]);
                }
            });
        }

        if ($payment) {
            return redirect()->route(
                'payment.result',
                $payment->id
            )->with('error', 'Payment failed. Please try again.');
        }

        return redirect()->route('dashboard.index')
            ->with('error', 'Payment failed or transaction not found.');
    }

    /**
     * Customer cancelled payment.
     */
    public function cancel(Request $request)
    {
        $payment = Payment::where(
            'tran_id',
            $request->input('tran_id')
        )->first();

        if ($payment) {
            DB::transaction(function () use ($payment, $request) {
                $locked = Payment::whereKey($payment->id)
                    ->lockForUpdate()
                    ->first();

                if ($locked && $locked->status === 'Pending') {
                    $locked->update([
                        'status' => 'Cancelled',
                        'gateway_response' => json_encode(
                            $request->all(),
                            JSON_UNESCAPED_UNICODE
                        ),
                    ]);
                }
            });

            return redirect()->route(
                'payment.result',
                $payment->id
            )->with('info', 'Payment was cancelled.');
        }

        return redirect()->route('dashboard.index')
            ->with('info', 'Payment was cancelled.');
    }

    /**
     * Validate transaction with SSLCommerz and activate subscription.
     */
    private function processPayment(Request $request, bool $isIpn = false)
    {
        $tranId = $request->input('tran_id');
        $valId = $request->input('val_id');

        if (!$tranId || !$valId) {
            return $this->callbackResponse(
                $isIpn,
                'FAILED',
                'Invalid payment response.',
                null,
                400
            );
        }

        $payment = Payment::where('tran_id', $tranId)->first();

        if (!$payment) {
            return $this->callbackResponse(
                $isIpn,
                'FAILED',
                'Payment record not found.',
                null,
                404
            );
        }

        // Safe for repeated IPN / browser callback after success.
        if ($payment->status === 'Paid') {
            return $this->callbackResponse(
                $isIpn,
                'SUCCESS',
                'Payment already processed.',
                $payment,
                200
            );
        }

        // Do not reactivate a failed or cancelled payment.
        if ($payment->status !== 'Pending') {
            return $this->callbackResponse(
                $isIpn,
                'FAILED',
                'Payment is not pending.',
                $payment,
                400
            );
        }

        $baseUrl = config('services.sslcommerz.mode') === 'live'
            ? 'https://securepay.sslcommerz.com'
            : 'https://sandbox.sslcommerz.com';

        try {
            $response = Http::timeout(30)->get(
                $baseUrl . '/validator/api/validationserverAPI.php',
                [
                    'val_id'       => $valId,
                    'store_id'     => config('services.sslcommerz.store_id'),
                    'store_passwd' => config('services.sslcommerz.store_password'),
                    'format'       => 'json',
                ]
            );

            if (!$response->successful()) {
                return $this->callbackResponse(
                    $isIpn,
                    'FAILED',
                    'Unable to validate payment.',
                    $payment,
                    502
                );
            }

            $validated = $response->json() ?? [];
        } catch (Throwable $e) {
            report($e);

            return $this->callbackResponse(
                $isIpn,
                'FAILED',
                'Payment validation unavailable.',
                $payment,
                502
            );
        }

        if (!in_array(
            $validated['status'] ?? '',
            ['VALID', 'VALIDATED'],
            true
        )) {
            return $this->callbackResponse(
                $isIpn,
                'FAILED',
                'SSLCommerz validation failed.',
                $payment,
                400
            );
        }

        // Verify transaction, amount, and currency independently.
        if (($validated['tran_id'] ?? '') !== $payment->tran_id) {
            return $this->callbackResponse(
                $isIpn,
                'FAILED',
                'Transaction ID mismatch.',
                $payment,
                400
            );
        }

        $gatewayAmount = number_format(
            (float) ($validated['amount'] ?? -1),
            2,
            '.',
            ''
        );

        $localAmount = number_format(
            (float) $payment->amount,
            2,
            '.',
            ''
        );

        if ($gatewayAmount !== $localAmount) {
            return $this->callbackResponse(
                $isIpn,
                'FAILED',
                'Payment amount mismatch.',
                $payment,
                400
            );
        }

        if (strtoupper($validated['currency'] ?? '') !== 'BDT') {
            return $this->callbackResponse(
                $isIpn,
                'FAILED',
                'Payment currency mismatch.',
                $payment,
                400
            );
        }

        try {
            $payment = DB::transaction(function () use (
                $payment,
                $valId,
                $validated
            ) {
                $lockedPayment = Payment::whereKey($payment->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Success callback and IPN may arrive concurrently.
                if ($lockedPayment->status === 'Paid') {
                    return $lockedPayment;
                }

                if ($lockedPayment->status !== 'Pending') {
                    throw new \RuntimeException(
                        'Payment is no longer pending.'
                    );
                }

                $package = Package::findOrFail(
                    $lockedPayment->package_id
                );

                $today = Carbon::today();

                $currentQuery = CompanyPackage::where(
                    'status',
                    'Active'
                )->where(function ($query) use ($today) {
                    $query->whereNull('expire_date')
                        ->orWhereDate('expire_date', '>=', $today);
                });

                if ($lockedPayment->company_id) {
                    $currentQuery->where(
                        'company_id',
                        $lockedPayment->company_id
                    );
                } else {
                    $currentQuery->whereNull('company_id')
                        ->where('user_id', $lockedPayment->user_id);
                }

                $currentPackage = $currentQuery
                    ->orderByDesc('expire_date')
                    ->lockForUpdate()
                    ->first();

                // Continue from the current expiry date when renewing.
                $startDate = $today;

                if ($currentPackage) {
                    $currentName = strtolower(
                        trim($currentPackage->package->name ?? '')
                    );

                    if (
                        $currentName !== 'trial'
                        && $currentPackage->expire_date
                    ) {
                        $startDate = Carbon::parse(
                            $currentPackage->expire_date
                        );
                    }

                    $currentPackage->update([
                        'status' => 'Cancelled',
                    ]);
                }

                // Assumes every paid package lasts one month.
                // Change this if your packages have different durations.
                $expireDate = $startDate->copy()
                    ->addMonthNoOverflow();

                CompanyPackage::create([
                    'company_id' => $lockedPayment->company_id,
                    'user_id'    => $lockedPayment->user_id,
                    'package_id' => $package->id,
                    'start_date' => $startDate->toDateString(),
                    'expire_date' => $expireDate->toDateString(),
                    'status' => 'Active',
                ]);

                $lockedPayment->update([
                    'val_id' => $valId,
                    'status' => 'Paid',
                    'payment_method' =>
                    $validated['card_type']
                        ?? $validated['card_brand']
                        ?? $validated['payment_method']
                        ?? null,
                    'gateway_response' => json_encode(
                        $validated,
                        JSON_UNESCAPED_UNICODE
                    ),
                    'paid_at' => now(),
                ]);

                return $lockedPayment->fresh();
            });

            return $this->callbackResponse(
                $isIpn,
                'SUCCESS',
                'Payment completed successfully.',
                $payment,
                200
            );
        } catch (Throwable $e) {
            report($e);

            return $this->callbackResponse(
                $isIpn,
                'FAILED',
                'Payment could not be processed. Please contact support.',
                $payment,
                500
            );
        }
    }

    /**
     * IPN gets a server response; browser callbacks get a result-page redirect.
     */
    private function callbackResponse(
        bool $isIpn,
        string $status,
        string $message,
        ?Payment $payment,
        int $httpStatus = 200
    ) {
        if ($isIpn) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'tran_id' => $payment?->tran_id,
            ], $httpStatus);
        }

        if ($payment) {
            $redirect = redirect()->route(
                'payment.result',
                $payment->id
            );

            if ($status === 'SUCCESS') {
                return $redirect->with('success', $message);
            }

            return $redirect->with('error', $message);
        }

        return redirect()->route('dashboard.index')
            ->with('error', $message);
    }

    /**
     * Payment result page.
     */
    public function result(Payment $payment)
    {
        // Optional authorization check can be added here if
        // the result must only be visible to its owner.

        return view('BackEnd.Payment.result', compact('payment'));
    }
}
