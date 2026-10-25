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
use Illuminate\Support\Str;
use Throwable;

class SSLCommerzController extends Controller
{
    private function gatewayBaseUrl(): string
    {
        return config('services.sslcommerz.mode') === 'live' ? 'https://securepay.sslcommerz.com' : 'https://sandbox.sslcommerz.com';
    }

    public function initiate(Request $request, Package $package)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('admin.login')->with('error', 'Please login before making a payment.');
        }

        if (!$package->is_active) {
            return back()->with('error', 'This package is currently unavailable.');
        }

        if (!is_numeric($package->price) || (float) $package->price <= 0) {
            return back()->with('error', 'Invalid package price.');
        }

        $storeId = config('services.sslcommerz.store_id');
        $storePassword = config('services.sslcommerz.store_password');

        if (empty($storeId) || empty($storePassword)) {
            return back()->with('error', 'Payment gateway is not configured. Please contact support.');
        }

        // $existingPayment = Payment::where('user_id', $user->id)->where('package_id', $package->id)
        //     ->where('status', 'Pending')->where('created_at', '>=', now()->subMinutes(30))->latest('id')->first();

        // if ($existingPayment) {
        //     return redirect()->route('payment.result', $existingPayment->id)
        //         ->with('info', 'A pending payment already exists. Please check its status before paying again.');
        // }

        // $payment = null;
        $payment = Payment::where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->where('status', 'Pending')
            ->where('created_at', '>=', now()->subMinutes(30))
            ->latest('id')
            ->first();

        try {
            if (!$payment) {
                $payment = Payment::create([
                    'user_id' => $user->id,
                    'company_id' => $user->company_id,
                    'package_id' => $package->id,
                    'tran_id' => 'COMITS-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(8)),
                    'amount' => $package->price,
                    'currency' => 'BDT',
                    'status' => 'Pending',
                ]);
            }

            $data = [
                'store_id' => $storeId,
                'store_passwd' => $storePassword,
                'total_amount' => number_format((float) $payment->amount, 2, '.', ''),
                'currency' => 'BDT',
                'tran_id' => $payment->tran_id,
                'success_url' => config('services.sslcommerz.success_url'),
                'fail_url' => config('services.sslcommerz.fail_url'),
                'cancel_url' => config('services.sslcommerz.cancel_url'),
                'ipn_url' => config('services.sslcommerz.ipn_url'),
                'cus_name' => $user->name ?: 'Customer',
                'cus_email' => $user->email ?: 'customer@example.com',
                'cus_add1' => 'Bangladesh',
                'cus_city' => 'Dhaka',
                'cus_country' => 'Bangladesh',
                'cus_phone' => $user->phone ?: '01700000000',
                'shipping_method' => 'NO',
                'product_name' => $package->name,
                'product_category' => 'Software Subscription',
                'product_profile' => 'general',
                'value_a' => (string) $payment->id,
                'value_b' => (string) $package->id,
                'value_c' => (string) $user->id,
                'value_d' => (string) ($user->company_id ?? ''),
            ];

            $response = Http::asForm()->acceptJson()->connectTimeout(10)->timeout(30)->post($this->gatewayBaseUrl() . '/gwprocess/v4/api.php', $data);

            $gatewayResult = $response->json() ?? [];

            if ($response->successful() && strtoupper((string) ($gatewayResult['status'] ?? '')) === 'SUCCESS' && filter_var($gatewayResult['GatewayPageURL'] ?? '', FILTER_VALIDATE_URL)) {
                return redirect()->away($gatewayResult['GatewayPageURL']);
            }

            $errorMessage = $gatewayResult['failedreason'] ?? $gatewayResult['error'] ?? 'SSLCommerz did not return a valid payment URL.';

            $payment->update([
                'status' => 'Failed',
                'gateway_response' => $this->encodeJson([
                    'stage' => 'initialization',
                    'http_status' => $response->status(),
                    'gateway_response' => $gatewayResult ?: $response->body(),
                ]),
            ]);

            return redirect()->route('payment.result', $payment->id)->with('error', $errorMessage);
        } catch (Throwable $e) {
            if ($payment && $payment->status === 'Pending') {
                $payment->update([
                    'gateway_response' => $this->encodeJson([
                        'stage' => 'initialization_exception',
                        'error' => $e->getMessage(),
                    ]),
                ]);
            }

            if ($payment) {
                return redirect()->route('payment.result', $payment->id)->with('error', 'Unable to start payment. Please check the payment record before retrying.');
            }

            return back()->with('error', 'Unable to create the payment. Please contact support.');
        }
    }

    public function success(Request $request)
    {
        return $this->processPayment($request, false);
    }

    public function ipn(Request $request)
    {
        return $this->processPayment($request, true);
    }

    public function fail(Request $request)
    {
        return $this->markUnsuccessfulPayment($request, 'Failed');
    }

    public function cancel(Request $request)
    {
        return $this->markUnsuccessfulPayment($request, 'Cancelled');
    }

    private function processPayment(Request $request, bool $isIpn = false)
    {
        $tranId = trim((string) $request->input('tran_id', ''));
        $valId = trim((string) $request->input('val_id', ''));

        if ($tranId === '' || $valId === '') {
            $payment = $tranId !== '' ? Payment::where('tran_id', $tranId)->first() : null;
            return $this->callbackResponse($isIpn, 'FAILED', 'Callback is missing the transaction ID or validation ID.', $payment, 400);
        }

        $payment = Payment::where('tran_id', $tranId)->first();

        if (!$payment) {
            return $this->callbackResponse($isIpn, 'FAILED', 'Payment record not found.', null, 404);
        }

        if ($payment->status === 'Paid') {
            return $this->callbackResponse($isIpn, 'SUCCESS', 'Payment has already been processed.', $payment, 200);
        }

        if ($payment->status !== 'Pending') {
            return $this->callbackResponse($isIpn, 'FAILED', 'This payment is not pending.', $payment, 409);
        }

        try {
            $response = Http::acceptJson()->connectTimeout(10)->timeout(30)
                ->get(
                    $this->gatewayBaseUrl()
                        . '/validator/api/validationserverAPI.php',
                    [
                        'val_id' => $valId,
                        'store_id' => config('services.sslcommerz.store_id'),
                        'store_passwd' => config('services.sslcommerz.store_password'),
                        'format' => 'json',
                    ]
                );

            $validated = $response->json() ?? [];

            if (!$response->successful() || empty($validated)) {
                return $this->callbackResponse($isIpn, 'FAILED', 'SSLCommerz validation service returned an invalid response.', $payment, 502);
            }
        } catch (Throwable $e) {
            return $this->callbackResponse($isIpn, 'FAILED', 'Payment validation could not be completed. The payment remains pending.', $payment, 502);
        }

        $validationStatus = strtoupper(
            (string) ($validated['status'] ?? '')
        );

        if (!in_array($validationStatus, ['VALID', 'VALIDATED'], true)) {
            return $this->callbackResponse($isIpn, 'FAILED', 'SSLCommerz did not validate this payment.', $payment, 400);
        }

        if (($validated['tran_id'] ?? '') !== $payment->tran_id) {
            return $this->callbackResponse($isIpn, 'FAILED', 'Transaction ID mismatch.', $payment, 400);
        }

        $gatewayAmount = number_format((float) ($validated['amount'] ?? -1), 2, '.', '');
        $localAmount = number_format((float) $payment->amount, 2, '.', '');

        if ($gatewayAmount !== $localAmount) {
            return $this->callbackResponse($isIpn, 'FAILED', 'Payment amount mismatch.', $payment, 400);
        }

        $gatewayCurrency = strtoupper(
            (string) ($validated['currency'] ?? '')
        );

        if ($gatewayCurrency !== strtoupper((string) $payment->currency)) {
            return $this->callbackResponse($isIpn, 'FAILED', 'Payment currency mismatch.', $payment, 400);
        }

        try {
            $payment = DB::transaction(function () use ($payment, $valId, $validated) {
                $lockedPayment = Payment::whereKey($payment->id)->lockForUpdate()->firstOrFail();

                if ($lockedPayment->status === 'Paid') {
                    return $lockedPayment;
                }

                if ($lockedPayment->status !== 'Pending') {
                    throw new \RuntimeException(
                        'Payment status changed before activation.'
                    );
                }

                $package = Package::findOrFail(
                    $lockedPayment->package_id
                );

                $today = Carbon::today();

                $currentQuery = CompanyPackage::where('status', 'Active')
                    ->where(function ($query) use ($today) {
                        $query->whereNull('expire_date')->orWhereDate('expire_date', '>=', $today);
                    });

                if ($lockedPayment->company_id) {
                    $currentQuery->where('company_id', $lockedPayment->company_id);
                } else {
                    $currentQuery->whereNull('company_id')->where('user_id', $lockedPayment->user_id);
                }

                $currentPackage = $currentQuery->orderByDesc('expire_date')->lockForUpdate()->first();

                $startDate = $today;

                if ($currentPackage) {
                    $currentPackageName = strtolower(
                        trim((string) ($currentPackage->package->name ?? ''))
                    );

                    if ($currentPackageName !== 'trial' && $currentPackage->expire_date) {
                        $existingExpiry = Carbon::parse(
                            $currentPackage->expire_date
                        );

                        $startDate = $existingExpiry->greaterThan($today) ? $existingExpiry : $today;
                    }

                    $currentPackage->update(['status' => 'Cancelled',]);
                }

                $expireDate = $startDate->copy()->addMonthNoOverflow();

                CompanyPackage::create([
                    'company_id' => $lockedPayment->company_id,
                    'user_id' => $lockedPayment->user_id,
                    'package_id' => $package->id,
                    'start_date' => $startDate->toDateString(),
                    'expire_date' => $expireDate->toDateString(),
                    'status' => 'Active',
                ]);

                $method = $validated['card_type'] ?? $validated['card_brand'] ?? $validated['payment_method'] ?? 'SSLCommerz';

                $lockedPayment->update([
                    'val_id' => $valId,
                    'status' => 'Paid',
                    'payment_method' => $method,
                    'gateway_response' => $this->encodeJson($validated),
                    'paid_at' => now(),
                ]);

                return $lockedPayment->fresh();
            });

            return $this->callbackResponse($isIpn, 'SUCCESS', 'Payment completed successfully.', $payment, 200);
        } catch (Throwable $e) {
            return $this->callbackResponse($isIpn, 'FAILED', 'Payment processing failed. The payment remains pending for verification.', $payment, 500);
        }
    }

    private function markUnsuccessfulPayment(Request $request, string $newStatus)
    {
        $tranId = trim((string) $request->input('tran_id', ''));

        if ($tranId === '') {
            return redirect()->route('dashboard.index');
        }

        try {
            $payment = DB::transaction(function () use ($tranId, $newStatus, $request) {
                $lockedPayment = Payment::where('tran_id', $tranId)->lockForUpdate()->first();

                if (!$lockedPayment) {
                    return null;
                }

                if ($lockedPayment->status === 'Pending') {
                    $lockedPayment->update([
                        'status' => $newStatus,
                        'gateway_response' => $this->encodeJson([
                            'callback_status' => $newStatus,
                            'callback_data' => $request->all(),
                        ]),
                    ]);
                }

                return $lockedPayment->fresh();
            });

            if (!$payment) {
                return redirect()->route('dashboard.index');
            }

            return redirect()->route('payment.result', $payment->id);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.index');
        }
    }

    private function callbackResponse(bool $isIpn, string $status, string $message, ?Payment $payment, int $httpStatus = 200)
    {
        if ($isIpn) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'tran_id' => $payment?->tran_id,
            ], $httpStatus);
        }

        if ($payment) {
            return redirect()->route('payment.result', $payment->id);
        }

        return redirect()->route('dashboard.index');
    }

    private function encodeJson(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR) ?: '{}';
    }

    public function result(Payment $payment)
    {
        $hasFlash = session()->has('success') || session()->has('error') || session()->has('info');

        if (!$hasFlash) {
            [$type, $message] = match ($payment->status) {
                'Paid' => ['success', 'Payment completed successfully. Your package is now active.',],
                'Failed' => ['error', 'Payment failed. No package was activated. You can try again.',],
                'Cancelled' => ['info', 'Payment was cancelled.',],
                default => ['info', 'Your payment is still being verified. Please do not pay again yet. Refresh this page in a few minutes.',],
            };

            session()->now($type, $message);
        }

        return view('BackEnd.Payment.result', compact('payment'));
    }
}
