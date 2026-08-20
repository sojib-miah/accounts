<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\CustomerCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })

            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'BackEnd.SupplierCustomer.index',
            compact('customerCompanies')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
        ]);

        try {

            CustomerCompany::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => 'Supplier',
                'created_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Supplier Company created successfully.',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(CustomerCompany $customerCompany)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $customerCompany->id,
                'name' => $customerCompany->name,
                'email' => $customerCompany->email,
                'phone' => $customerCompany->phone,
                'address' => $customerCompany->address,
                'status' => $customerCompany->status,
            ],
        ]);
    }

    public function update(Request $request, CustomerCompany $customerCompany)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
        ]);

        try {

            $customerCompany->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => 'Supplier',
                'updated_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Supplier Company updated successfully.',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(CustomerCompany $customerCompany)
    {
        try {
            if ($customerCompany->parties()->exists()) {
                return back()->with(
                    'error',
                    'This Supplier Company has customers. It cannot be deleted.'
                );
            }
            $customerCompany->delete();

            return response()->json([
                'success' => true,
                'message' => 'Supplier Company deleted successfully.',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
