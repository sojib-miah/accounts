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

class SSLCommerzController extends Controller
{
    public function initiate(Request $request, Package $package)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()
                ->route('admin.login')
                ->with('error', 'Please login before making a payment.');
        }
        if (!$package->is_active) {
            return back()->with(
                'error',
                'This package is currently unavailable.'
            );
        }

        $existingPayment = Payment::where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->where('status', 'Pending')
            ->where('created_at', '>=', now()->subMinutes(30))
            ->latest('id')
            ->first();

        if ($existingPayment) {
            return back()->with(
                'error',
                'You already have a pending payment for this package. Please complete that payment before starting another one.'
            );
        }

        $tranId = 'COMITS_' .
            now()->format('YmdHis') .
            '_' .
            strtoupper(Str::random(8));
        $payment = Payment::create([
            'user_id'    => $user->id,
            'company_id' => $user->company_id,
            'package_id' => $package->id,
            'tran_id'    => $tranId,
            'amount'     => $package->price,
            'currency'   => 'BDT',
            'status'     => 'Pending',
        ]);

        $apiUrl = config('services.sslcommerz.mode') === 'live'
            ? 'https://securepay.sslcommerz.com/gwprocess/v4/api.php'
            : 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php';
        $data = [
            'store_id'       => config('services.sslcommerz.store_id'),
            'store_passwd'   => config('services.sslcommerz.store_password'),

            'total_amount'   => number_format(
                (float) $package->price,
                2,
                '.',
                ''
            ),
            'currency'       => 'BDT',
            'tran_id'        => $tranId,
            'success_url'    => route('payment.sslcommerz.success'),
            'fail_url'       => route('payment.sslcommerz.fail'),
            'cancel_url'     => route('payment.sslcommerz.cancel'),
            'ipn_url'        => route('payment.sslcommerz.ipn'),
            'cus_name'       => $user->name ?? 'Customer',
            'cus_email'      => $user->email ?? 'customer@example.com',
            'cus_add1'       => 'Bangladesh',
            'cus_city'       => 'Dhaka',
            'cus_country'    => 'Bangladesh',
            'cus_phone'      => $user->phone ?? '01700000000',
            'product_name'      => $package->name,
            'product_category' => 'Software Subscription',
            'product_profile'  => 'general',

            'value_a' => (string) $payment->id,
            'value_b' => (string) $package->id,
            'value_c' => (string) $user->id,
            'value_d' => (string) ($user->company_id ?? ''),
        ];

        try {
            $response = Http::asForm()
                ->acceptJson()
                ->timeout(30)
                ->post($apiUrl, $data);

            if (!$response->successful()) {

                $payment->update([
                    'status' => 'Failed',
                    'gateway_response' => $response->body(),
                ]);

                return back()->with(
                    'error',
                    'Unable to connect with SSLCommerz. Please try again.'
                );
            }

            $result = $response->json();

            if (
                ($result['status'] ?? null) === 'SUCCESS' &&
                !empty($result['GatewayPageURL'])
            ) {
                return redirect()->away($result['GatewayPageURL']);
            }
            $payment->update([
                'status' => 'Failed',
                'gateway_response' => json_encode(
                    $result,
                    JSON_UNESCAPED_UNICODE
                ),
            ]);

            return back()->with(
                'error',
                $result['failedreason']
                    ?? 'Unable to initialize payment.'
            );
        } catch (\Throwable $e) {

            report($e);

            $payment->update([
                'status' => 'Failed',
                'gateway_response' => $e->getMessage(),
            ]);

            return back()->with(
                'error',
                'Payment initialization failed. Please try again.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SUCCESS
    |--------------------------------------------------------------------------
    */

    public function success(Request $request)
    {
        return $this->processPayment($request, false);
    }


    /*
    |--------------------------------------------------------------------------
    | IPN
    |--------------------------------------------------------------------------
    */

    public function ipn(Request $request)
    {
        return $this->processPayment($request, true);
    }


    /*
    |--------------------------------------------------------------------------
    | FAIL
    |--------------------------------------------------------------------------
    */

    public function fail(Request $request)
    {
        $tranId = $request->input('tran_id');

        if ($tranId) {

            Payment::where('tran_id', $tranId)
                ->where('status', 'Pending')
                ->update([
                    'status' => 'Failed',
                    'gateway_response' => json_encode(
                        $request->all(),
                        JSON_UNESCAPED_UNICODE
                    ),
                ]);
        }

        return redirect()
            ->route('dashboard.index')
            ->with(
                'error',
                'Payment failed. Please try again.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CANCEL
    |--------------------------------------------------------------------------
    */

    public function cancel(Request $request)
    {
        $tranId = $request->input('tran_id');

        if ($tranId) {

            Payment::where('tran_id', $tranId)
                ->where('status', 'Pending')
                ->update([
                    'status' => 'Cancelled',
                    'gateway_response' => json_encode(
                        $request->all(),
                        JSON_UNESCAPED_UNICODE
                    ),
                ]);
        }

        return redirect()
            ->route('dashboard.index')
            ->with(
                'error',
                'Payment was cancelled.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PROCESS PAYMENT
    |--------------------------------------------------------------------------
    */

    private function processPayment(Request $request, bool $isIpn = false)
    {
        $tranId = $request->input('tran_id');
        $valId  = $request->input('val_id');

        if (!$tranId || !$valId) {

            if ($isIpn) {
                return response()->json([
                    'status' => 'FAILED',
                    'message' => 'Invalid payment response.',
                ], 400);
            }

            return redirect()
                ->route('dashboard.index')
                ->with(
                    'error',
                    'Invalid payment response.'
                );
        }

        $payment = Payment::where('tran_id', $tranId)->first();

        if (!$payment) {

            if ($isIpn) {
                return response()->json([
                    'status' => 'FAILED',
                    'message' => 'Payment record not found.',
                ], 404);
            }

            return redirect()
                ->route('package.index')
                ->with(
                    'error',
                    'Payment record not found.'
                );
        }

        if ($payment->status === 'Paid') {

            if ($isIpn) {
                return response()->json([
                    'status' => 'SUCCESS',
                    'message' => 'Payment already processed.',
                ]);
            }

            return redirect()
                ->route('payment.result', $payment->id)
                ->with(
                    'success',
                    'Payment already processed successfully.'
                );
        }

        $validationUrl = config('services.sslcommerz.mode') === 'live'
            ? 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php'
            : 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php';

        try {

            $validationResponse = Http::timeout(30)
                ->acceptJson()
                ->get($validationUrl, [
                    'val_id'       => $valId,
                    'store_id'     => config('services.sslcommerz.store_id'),
                    'store_passwd' => config('services.sslcommerz.store_password'),
                    'format'       => 'json',
                ]);

            if (!$validationResponse->successful()) {

                if ($isIpn) {
                    return response()->json([
                        'status' => 'FAILED',
                        'message' => 'Unable to validate payment.',
                    ], 502);
                }

                return redirect()
                    ->route('dashboard.index')
                    ->with(
                        'error',
                        'Unable to validate payment.'
                    );
            }

            $validated = $validationResponse->json();
        } catch (\Throwable $e) {

            report($e);

            if ($isIpn) {
                return response()->json([
                    'status' => 'FAILED',
                    'message' => 'Payment validation failed.',
                ], 500);
            }

            return redirect()
                ->route('dashboard.index')
                ->with(
                    'error',
                    'Payment validation failed.'
                );
        }

        if (!in_array(
            $validated['status'] ?? null,
            ['VALID', 'VALIDATED'],
            true
        )) {

            $payment->update([
                'status' => 'Failed',
                'val_id' => $valId,
                'gateway_response' => json_encode(
                    $validated,
                    JSON_UNESCAPED_UNICODE
                ),
            ]);

            if ($isIpn) {
                return response()->json([
                    'status' => 'FAILED',
                    'message' => 'Payment validation failed.',
                ], 400);
            }

            return redirect()
                ->route('package.index')
                ->with(
                    'error',
                    'Payment validation failed.'
                );
        }

        if (($validated['tran_id'] ?? null) !== $payment->tran_id) {

            $payment->update([
                'status' => 'Failed',
                'val_id' => $valId,
                'gateway_response' => json_encode(
                    $validated,
                    JSON_UNESCAPED_UNICODE
                ),
            ]);

            if ($isIpn) {
                return response()->json([
                    'status' => 'FAILED',
                    'message' => 'Transaction ID mismatch.',
                ], 400);
            }

            return redirect()
                ->route('dashboard.index')
                ->with(
                    'error',
                    'Transaction ID mismatch.'
                );
        }

        $gatewayAmount = number_format(
            (float) ($validated['amount'] ?? 0),
            2,
            '.',
            ''
        );

        $paymentAmount = number_format(
            (float) $payment->amount,
            2,
            '.',
            ''
        );

        if ($gatewayAmount !== $paymentAmount) {

            $payment->update([
                'status' => 'Failed',
                'val_id' => $valId,
                'gateway_response' => json_encode(
                    $validated,
                    JSON_UNESCAPED_UNICODE
                ),
            ]);

            if ($isIpn) {
                return response()->json([
                    'status' => 'FAILED',
                    'message' => 'Payment amount mismatch.',
                ], 400);
            }

            return redirect()
                ->route('dashboard.index')
                ->with(
                    'error',
                    'Payment amount mismatch.'
                );
        }
        if (($validated['currency'] ?? null) !== 'BDT') {

            $payment->update([
                'status' => 'Failed',
                'val_id' => $valId,
                'gateway_response' => json_encode(
                    $validated,
                    JSON_UNESCAPED_UNICODE
                ),
            ]);

            if ($isIpn) {
                return response()->json([
                    'status' => 'FAILED',
                    'message' => 'Payment currency mismatch.',
                ], 400);
            }

            return redirect()
                ->route('dashboard.index')
                ->with(
                    'error',
                    'Payment currency mismatch.'
                );
        }
        try {

            DB::beginTransaction();

            $payment = Payment::where('id', $payment->id)
                ->lockForUpdate()
                ->first();

            if (!$payment) {
                throw new \Exception(
                    'Payment record no longer exists.'
                );
            }

            if ($payment->status === 'Paid') {

                DB::commit();

                if ($isIpn) {
                    return response()->json([
                        'status' => 'SUCCESS',
                        'message' => 'Payment already processed.',
                    ]);
                }

                return redirect()
                    ->route('dashboard.index', $payment->id)
                    ->with(
                        'success',
                        'Payment already processed.'
                    );
            }

            $package = Package::find($payment->package_id);

            if (!$package) {
                throw new \Exception(
                    'Package not found.'
                );
            }

            $userId = $payment->user_id;
            $companyId = $payment->company_id;

            $currentPackage = CompanyPackage::with('package')
                ->where('user_id', $userId)
                ->where('status', 'Active')
                ->where(function ($query) {
                    $query
                        ->whereNull('expire_date')
                        ->orWhereDate(
                            'expire_date',
                            '>=',
                            Carbon::today()
                        );
                })
                ->latest('expire_date')
                ->lockForUpdate()
                ->first();

            if ($currentPackage) {

                $currentPackageName = strtolower(
                    trim(
                        $currentPackage->package->name ?? ''
                    )
                );

                if ($currentPackageName === 'trial') {

                    $startDate = Carbon::today();
                } else {

                    $startDate = Carbon::parse(
                        $currentPackage->expire_date
                    );
                }

                $currentPackage->update([
                    'status' => 'Cancelled',
                ]);
            } else {

                $startDate = Carbon::today();
            }

            $expireDate = $startDate
                ->copy()
                ->addMonthNoOverflow();

            CompanyPackage::create([
                'company_id' => $companyId,
                'user_id' => $userId,
                'package_id' => $package->id,
                'start_date' => $startDate->toDateString(),
                'expire_date' => $expireDate->toDateString(),
                'status' => 'Active',
            ]);

            $paymentMethod =
                $validated['card_type']
                ?? $validated['card_brand']
                ?? $validated['payment_method']
                ?? $request->input('card_type')
                ?? $request->input('card_brand')
                ?? null;

            $payment->update([
                'val_id' => $valId,

                'status' => 'Paid',

                'payment_method' => $paymentMethod,

                'gateway_response' => json_encode(
                    $validated,
                    JSON_UNESCAPED_UNICODE
                ),

                'paid_at' => now(),
            ]);

            DB::commit();

            if ($isIpn) {

                return response()->json([
                    'status' => 'SUCCESS',
                    'message' => 'Payment processed successfully.',
                    'tran_id' => $payment->tran_id,
                ], 200);
            }

            return redirect()
                ->route('payment.result', $payment->id)
                ->with(
                    'success',
                    'Payment completed successfully. Your package is now active.'
                );
        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            if ($isIpn) {

                return response()->json([
                    'status' => 'FAILED',
                    'message' => 'Payment received but subscription activation failed.',
                ], 500);
            }

            return redirect()
                ->route('dashboard.index')
                ->with(
                    'error',
                    'Payment was received but subscription activation failed. Please contact support.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PAYMENT RESULT
    |--------------------------------------------------------------------------
    */

    public function result(Payment $payment)
    {
        return view(
            'BackEnd.Payment.result',
            compact('payment')
        );
    }
}
