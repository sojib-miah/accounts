<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\CustomerCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerCompanyController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $customerCompanies = CustomerCompany::where('status', 'Sales')->latest()->get();
        return view('BackEnd.SalesCustomer.index', compact('customerCompanies'));
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
                'status' => 'Sales',
                'created_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer Company created successfully.',
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
                'status' => 'Sales',
                'updated_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer Company updated successfully.',
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
                    'This Customer Company has customers. It cannot be deleted.'
                );
            }
            $customerCompany->delete();

            return response()->json([
                'success' => true,
                'message' => 'Customer Company deleted successfully.',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // expense method 
    public function expenseIndex()
    {
        $user = Auth::user();
        $customerCompanies = CustomerCompany::where('status', 'Expense')->latest()->get();
        return view('BackEnd.ExpenseCustomer.index', compact('customerCompanies'));
    }

    public function expenseStore(Request $request)
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
                'status' => 'Expense',
                'created_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer Company created successfully.',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function expenseShow(CustomerCompany $customerCompany)
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

    public function expenseUpdate(Request $request, CustomerCompany $customerCompany)
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
                'status' => 'Expense',
                'updated_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer Company updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function expenseDestroy(CustomerCompany $customerCompany)
    {
        try {
            if ($customerCompany->parties()->exists()) {
                return back()->with(
                    'error',
                    'This Customer Company has customers. It cannot be deleted.'
                );
            }
            $customerCompany->delete();
            return response()->json([
                'success' => true,
                'message' => 'Customer Company deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
