<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Party;
use App\Models\Receipt;
use Illuminate\Http\Request;

class ChallanController extends Controller
{
    public function index(Request $request)
    {
        $query = Receipt::with([
            'party',
            'branch',
            'creator'
        ])->where('type', 'Income');
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('receipt_no', 'like', "%{$search}%")
                    ->orWhereHas('party', function ($party) use ($search) {
                        $party->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('branch', function ($branch) use ($search) {
                        $branch->where('name', 'like', "%{$search}%");
                    });
            });
        }
        // Payment Status
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }
        $perPage = $request->per_page ?? 24;
        $receipts = $query->latest()->paginate($perPage)->withQueryString();
        return view('BackEnd.Challan.index', compact('receipts'));
    }

    public function createChallan()
    {
        $branches = Branch::latest()->get();
        $parties = Party::where('type', 'Income')->where('status', 'Active')->get();

        $categories = Category::where('type', 'Income')->where('status', 'Active')->get();
        return view('BackEnd.Challan.challan_create', compact('branches', 'parties', 'categories'));
    }
}
