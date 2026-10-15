<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CustomerCompany;
use App\Models\Party;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    private function generateSupplierId()
    {
        $last = Party::whereIn('type', ['Supplier', 'Both'])->latest('id')->first();
        if (!$last) {
            return 'SUP-10001';
        }
        $number = (int) substr($last->party_id, 4);
        return 'SUP-' . ($number + 1);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $suppliers = Party::with([
            'creator',
            'updater',
            'customerCompany'
        ])
            ->when($request->filled('company_id'), function ($query) use ($request) {
                $query->where('company_id', $request->company_id);
            })
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
            ->whereIn('type', ['Supplier', 'Both'])
            ->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
                $query->where('created_by', Auth::id());
            })->latest()->paginate(10)->withQueryString();

        $customerCompanies = CustomerCompany::when(
            !Auth::user()->hasRole('Super-Admin'),
            function ($query) {
                $query->where('created_by', Auth::id());
            }
        )->where('status', 'Supplier')->orderBy('name')->get();

        $companies = Company::orderBy('name')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('id', $user->company_id)->orWhere('created_by', $user->id);
                });
            })->get();

        return view('BackEnd.Supplier.index', compact('suppliers', 'customerCompanies', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validateWithBag('add', [
            'customer_company_id' => 'required|exists:customer_companies,id',
            'name' => ['required', 'string', 'max:100'],
            'phone' => 'nullable|max:30',
            'email' => 'nullable|email',
            'status' => 'required',
            'address' => 'nullable',
        ]);

        Party::create([
            'company_id' => Auth::user()->company_id,
            'customer_company_id' => $request->customer_company_id,
            'party_id' => $this->generateSupplierId(),
            'name' => $request->name,
            'designation' => $request->designation,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'type' => 'Supplier',
            'status' => $request->status,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('supplier.index')->with('success', 'Supplier created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Party $supplier)
    {
        $validator = Validator::make($request->all(), [
            'customer_company_id' => 'required|exists:customer_companies,id',
            'name' => ['required', 'string', 'max:100'],
            'phone' => 'nullable|max:30',
            'email' => 'nullable|email',
            'status' => 'required',
            'address' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('supplier.index')
                ->withErrors($validator, 'edit')
                ->withInput()
                ->with('edit_supplier_id', $supplier->id);
        }

        $supplier->update([
            'customer_company_id' => $request->customer_company_id,
            'name' => $request->name,
            'designation' => $request->designation,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'status' => $request->status,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('supplier.index')->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Party $supplier)
    {
        if ($supplier->receipts()->exists()) {
            return redirect()->route('supplier.index')->with('error', 'This supplier is already used in transactions. You cannot delete it.');
        }
        try {
            $supplier->delete();
            return redirect()->route('supplier.index')->with('success', 'Supplier deleted successfully.');
        } catch (QueryException $e) {
            return redirect()->route('supplier.index')->with('error', 'This supplier is already used in other records. You cannot delete it.');
        }
    }
}
