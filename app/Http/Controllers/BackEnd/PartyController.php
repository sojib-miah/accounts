<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\CustomerCompany;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PackageHelper;

class PartyController extends Controller
{
    public function index(Request $request)
    {
        $parties = Party::with([
            'creator',
            'updater',
            'customerCompany'
        ])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('party_id', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhereHas('customerCompany', function ($companyQuery) use ($search) {
                            $companyQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->where('type', 'Expense')
            ->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
                $query->where('created_by', Auth::id());
            })
            ->latest()
            ->get();

        $customerCompanies = CustomerCompany::when(
            !Auth::user()->hasRole('Super-Admin'),
            function ($query) {
                $query->where('created_by', Auth::id());
            }
        )
            ->where('status', 'Expense')
            ->orderBy('name')
            ->get();

        return view('BackEnd.Party.index', compact('parties', 'customerCompanies'));
    }

    public function store(Request $request)
    {
        $request->validateWithBag('add', [
            'customer_company_id' => 'nullable|exists:customer_companies,id',
            'name'               => 'nullable|max:255',
            'phone'              => 'nullable|max:30',
            'email'              => 'nullable|email|max:255',
            'address'            => 'nullable|string',
            'designation'        => 'nullable|string',
            'status'             => 'required|in:Active,Inactive',
        ]);

        if (!Auth::user()->hasRole('Super-Admin')) {

            $current = Party::where('created_by', Auth::id())
                ->where('type', 'Customer')
                ->count();

            if ($message = PackageHelper::checkLimit('party_limit', $current)) {
                return back()->with('error', $message);
            }

            // Check selected customer company belongs to current user
            if ($request->filled('customer_company_id')) {

                $customerCompany = CustomerCompany::where('id', $request->customer_company_id)
                    ->where('created_by', Auth::id())
                    ->first();

                if (!$customerCompany) {
                    return back()
                        ->withInput()
                        ->withErrors([
                            'customer_company_id' => 'Invalid customer company selected.'
                        ], 'add');
                }
            }
        }

        $customerCompany = null;

        if ($request->filled('customer_company_id')) {
            $customerCompany = CustomerCompany::find($request->customer_company_id);
        }

        Party::create([
            'company_id'          => auth()->user()->company_id,
            'customer_company_id' => $request->customer_company_id,
            'party_id'            => random_int(100000, 999999),
            'name'                => $request->name,
            'designation'         => $request->designation,
            'phone'               => $request->phone,
            'email'               => $request->email,
            'address'             => $request->address,
            'type'                => 'Expense',
            'status'              => $request->status,
            'created_by'          => auth()->id(),
        ]);

        return redirect()->route('party.index')->with('success', 'Expense Created Successfully.');
    }

    public function update(Request $request, Party $party)
    {
        $request->validateWithBag('edit', [
            'customer_company_id' => 'nullable|exists:customer_companies,id',
            'name'               => 'nullable|max:255',
            'phone'              => 'nullable|max:30',
            'email'              => 'nullable|email|max:255',
            'address'            => 'nullable|string',
            'designation'        => 'nullable|string',
            'status'             => 'required|in:Active,Inactive',
        ]);

        if (!Auth::user()->hasRole('Super-Admin')) {

            if ($party->created_by != Auth::id()) {
                return back()->with('error', 'You are not allowed to update this customer.');
            }

            if ($request->filled('customer_company_id')) {

                $customerCompany = CustomerCompany::where('id', $request->customer_company_id)
                    ->where('created_by', Auth::id())
                    ->first();

                if (!$customerCompany) {
                    return back()
                        ->withInput()
                        ->withErrors([
                            'customer_company_id' => 'Invalid customer company selected.'
                        ], 'edit');
                }
            }
        }

        $customerCompany = null;

        if ($request->filled('customer_company_id')) {
            $customerCompany = CustomerCompany::find(
                $request->customer_company_id
            );
        }

        $party->update([
            'customer_company_id' => $request->customer_company_id,
            'name'               => $request->name,
            'designation'        => $request->designation,
            'phone'              => $request->phone,
            'email'              => $request->email,
            'address'            => $request->address,
            'status'             => $request->status,
            'updated_by'         => auth()->id(),
        ]);

        return redirect()->route('party.index')->with('success', 'Expense Updated Successfully.');
    }

    public function destroy(Party $party)
    {
        if ($party->receipts()->exists()) {
            return redirect()->back()->with('error', 'This party has receipts and cannot be deleted.');
        }
        $party->delete();

        return redirect()->route('party.index')->with('success', 'Party Deleted Successfully.');
    }
}
