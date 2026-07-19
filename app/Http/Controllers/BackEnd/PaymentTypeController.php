<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\PaymentType;
use App\Models\ReceiptPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentTypeController extends Controller
{
    public function index(Request $request)
    {
        $paymentTypes = PaymentType::when($request->filled('search'), function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%');
        })->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->latest()->get();

        return view('BackEnd.PaymentType.index', compact('paymentTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        PaymentType::create([
            'name'   => $request->name,
            'status' => $request->status,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('payment-type.index')->with('success', 'Payment Type Added Successfully.');
    }

    public function update(Request $request, PaymentType $paymentType)
    {
        $request->validate([
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
