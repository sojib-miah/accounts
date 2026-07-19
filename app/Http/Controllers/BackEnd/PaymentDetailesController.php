<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentDetailesController extends Controller
{
    public function expenseDetails(Request $request)
    {
        $user = Auth::user();

        $query = Receipt::with([
            'party',
            'branch',
            'items.accountHead',
            'payments.paymentType',
            'creator'
        ])
            ->where('type', 'Expense')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where('created_by', $user->id);
            });

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('receipt_no', 'like', "%{$search}%")

                    ->orWhereHas('party', function ($party) use ($search) {
                        $party->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Payment Status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Date Filter
        if ($request->filled('from_date')) {
            $query->whereDate('receipt_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('receipt_date', '<=', $request->to_date);
        }

        $receipts = $query->latest()
            ->paginate(10)
            ->withQueryString();

        // Summary Query
        $summary = Receipt::where('type', 'Expense')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where('created_by', $user->id);
            });

        $todayExpense = (clone $summary)
            ->whereDate('receipt_date', today())
            ->sum('total_amount');

        $monthExpense = (clone $summary)
            ->whereMonth('receipt_date', now()->month)
            ->whereYear('receipt_date', now()->year)
            ->sum('total_amount');

        $yearExpense = (clone $summary)
            ->whereYear('receipt_date', now()->year)
            ->sum('total_amount');

        $totalExpense = (clone $summary)->sum('total_amount');
        $totalPaid = (clone $summary)->sum('paid_amount');
        $totalDue = (clone $summary)->sum('due_amount');

        return view(
            'BackEnd.Report.expense_details',
            compact(
                'receipts',
                'todayExpense',
                'monthExpense',
                'yearExpense',
                'totalExpense',
                'totalPaid',
                'totalDue'
            )
        );
    }

    public function incomeInvoice(Request $request)
    {
        $user = Auth::user();

        $query = Receipt::with([
            'party',
            'branch',
            'items.accountHead',
            'payments.paymentType',
            'creator'
        ])
            ->where('type', 'Income')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where('created_by', $user->id);
            });

        // Search
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('receipt_no', 'like', "%{$search}%")

                    ->orWhereHas('party', function ($party) use ($search) {
                        $party->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Payment Status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Date Filter
        if ($request->filled('from_date')) {
            $query->whereDate('receipt_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('receipt_date', '<=', $request->to_date);
        }

        $receipts = $query->latest()
            ->paginate(20)
            ->withQueryString();

        $summary = Receipt::where('type', 'Income')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where('created_by', $user->id);
            });

        $todayIncome = (clone $summary)
            ->whereDate('receipt_date', today())
            ->sum('total_amount');

        $monthIncome = (clone $summary)
            ->whereMonth('receipt_date', now()->month)
            ->whereYear('receipt_date', now()->year)
            ->sum('total_amount');

        $yearIncome = (clone $summary)
            ->whereYear('receipt_date', now()->year)
            ->sum('total_amount');

        $totalIncome = (clone $summary)->sum('total_amount');
        $totalPaid   = (clone $summary)->sum('paid_amount');
        $totalDue    = (clone $summary)->sum('due_amount');

        return view(
            'BackEnd.Report.income_invoice',
            compact(
                'receipts',
                'todayIncome',
                'monthIncome',
                'yearIncome',
                'totalIncome',
                'totalPaid',
                'totalDue'
            )
        );
    }
}
