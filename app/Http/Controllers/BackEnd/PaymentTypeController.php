<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\PaymentType;
use App\Models\ReceiptPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PackageHelper;

class PaymentTypeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $paymentTypes = PaymentType::when($request->filled('search'), function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%');
        })->when($request->filled('company_id'), function ($query) use ($request) {
            $query->where('company_id', $request->company_id);
        })->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->latest()->paginate(10)->withQueryString();

        $companies = Company::orderBy('name')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('id', $user->company_id)->orWhere('created_by', $user->id);
                });
            })->get();

        return view('BackEnd.PaymentType.index', compact('paymentTypes', 'companies'));
    }

    public function store(Request $request)
    {
        $request->validateWithBag('add', [
            'name'   => 'required|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        if (!Auth::user()->hasRole('Super-Admin')) {

            $totalCompany = PaymentType::where(function ($q) {
                $q->where('created_by', Auth::id());
            })->count();

            if ($message = PackageHelper::checkLimit('payment_type_limit', $totalCompany)) {
                return back()->with('error', $message);
            }
        }

        PaymentType::create([
            'company_id' => Auth::user()->company_id,
            'name'   => $request->name,
            'status' => $request->status,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('payment-type.index')->with('success', 'Payment Type Added Successfully.');
    }

    public function update(Request $request, PaymentType $paymentType)
    {
        $request->validateWithBag('edit', [
            'name'   => 'required|max:255' . $paymentType->id,
            'status' => 'required|in:Active,Inactive',
        ]);

        $paymentType->update([
            'name'   => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('payment-type.index')->with('success', 'Payment Type Updated Successfully.');
    }

    public function destroy(PaymentType $paymentType)
    {
        if (ReceiptPayment::where('payment_type_id', $paymentType->id)->exists()) {

            return back()->with('error', 'This payment type has already been used in transactions and cannot be deleted.');
        }
        $paymentType->delete();

        return redirect()->route('payment-type.index')->with('success', 'Payment Type Deleted Successfully.');
    }
}
