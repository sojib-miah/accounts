<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CustomerCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SupplierCompanyCustomer extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $customerCompanies = CustomerCompany::query()
            ->where('status', 'Supplier')
            ->when(
                !$user->hasRole('Super-Admin'),
                function ($query) use ($user) {
                    $query->where('created_by', $user->id);
                }
            )
            ->when($request->filled('company_id'), function ($query) use ($request) {
                $query->where('company_id', $request->company_id);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })->latest()->paginate(10)->withQueryString();

        $companies = Company::orderBy('name')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('id', $user->company_id)->orWhere('created_by', $user->id);
                });
            })->get();

        return view('BackEnd.SupplierCustomer.index', compact('customerCompanies', 'companies'));
    }

    public function store(Request $request)
    {
        $companyId = Auth::user()->company_id;

        $validated = $request->validateWithBag('add', [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('customer_companies', 'name')
                    ->where('company_id', $companyId),
            ],
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
        ], [
            'name.unique' => 'This supplier company already exists.',
        ]);

        CustomerCompany::create([
            'company_id' => $companyId,
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'status' => 'Supplier',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('supplier-company.index')->with('success', 'Supplier Company created successfully.');
    }

    public function update(Request $request, CustomerCompany $customerCompany)
    {
        $companyId = Auth::user()->company_id;

        abort_unless(
            (int) $customerCompany->company_id === (int) $companyId,
            404
        );

        $validated = $request->validateWithBag('edit', [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('customer_companies', 'name')
                    ->where('company_id', $companyId)
                    ->ignore($customerCompany->id),
            ],
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
        ], [
            'name.unique' => 'This supplier company already exists.',
        ]);

        $customerCompany->update([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('supplier-company.index')->with('success', 'Supplier Company updated successfully.');
    }

    public function destroy(CustomerCompany $customerCompany)
    {
        if ($customerCompany->parties()->exists()) {
            return back()->with(
                'error',
                'This Customer Company has customers. It cannot be deleted.'
            );
        }

        $customerCompany->delete();

        return back()->with(
            'success',
            'Customer Company deleted successfully.'
        );
    }
}
