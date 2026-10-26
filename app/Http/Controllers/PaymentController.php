<?php

namespace App\Http\Controllers;

use App\Models\CompanyPackage;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Payment::with([
            'user:id,name,email',
            'company:id,name',
            'package:id,name,price',
        ]);
        if (!$user->hasRole('Super-Admin')) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if ($user->company_id) {
                    $q->orWhere('company_id', $user->company_id);
                }
            });
        }
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tran_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('package', function ($packageQuery) use ($search) {
                        $packageQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('company', function ($companyQuery) use ($search) {
                        $companyQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        $payments = $query->latest('id')->paginate(15)->withQueryString();

        return view('BackEnd.Payment.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load([
            'user',
            'company',
            'package',
        ]);
        return view('BackEnd.Payment.show', compact('payment'));
    }

    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:Pending,Paid,Failed,Cancelled',
        ]);
        if ($request->status === 'Paid' && $payment->status !== 'Paid') {
            DB::beginTransaction();
            try {
                $currentPackage = CompanyPackage::with('package')
                    ->where('user_id', $payment->user_id)
                    ->where('status', 'Active')
                    ->where(function ($query) {
                        $query
                            ->whereNull('expire_date')
                            ->orWhereDate('expire_date', '>=', Carbon::today()->toDateString());
                    })->latest('expire_date')->lockForUpdate()->first();

                if ($currentPackage) {
                    $currentPackageName = strtolower(
                        trim($currentPackage->package->name ?? '')
                    );
                    if ($currentPackageName === 'trial') {
                        $startDate = Carbon::today();
                    } else {
                        $startDate = Carbon::parse($currentPackage->expire_date);
                    }
                    $currentPackage->update([
                        'status' => 'Cancelled',
                    ]);
                } else {
                    $startDate = Carbon::today();
                }
                $expireDate = $startDate->copy()->addMonthNoOverflow();
                CompanyPackage::create([
                    'company_id' => $payment->company_id,
                    'user_id' => $payment->user_id,
                    'package_id' => $payment->package_id,
                    'start_date' => $startDate->toDateString(),
                    'expire_date' => $expireDate->toDateString(),
                    'status' => 'Active',
                ]);
                $payment->update([
                    'status' => 'Paid',
                    'paid_at' => $payment->paid_at ?? now(),
                ]);
                DB::commit();
                return back()->with('success', 'Payment marked as Paid and package activated successfully.');
            } catch (\Throwable $e) {
                DB::rollBack();
                report($e);
                return back()->with('error', 'Unable to activate package.');
            }
        }
        $payment->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Payment status updated successfully.');
    }
}
