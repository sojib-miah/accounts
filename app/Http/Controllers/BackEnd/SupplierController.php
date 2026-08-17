<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\CustomerCompany;
use App\Models\Party;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $suppliers = Party::with([
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
            ->whereIn('type', ['Supplier', 'Both'])
            ->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
                $query->where('created_by', Auth::id());
            })
            ->latest()
            ->paginate(10);

        $customerCompanies = CustomerCompany::when(
            !Auth::user()->hasRole('Super-Admin'),
            function ($query) {
                $query->where('created_by', Auth::id());
            }
        )
            ->where('status', 'Supplier')
            ->orderBy('name')
            ->get();

        return view('BackEnd.Supplier.index', compact('suppliers', 'customerCompanies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json([
            'supplier_id' => $this->generateSupplierId()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_company_id' => 'nullable|exists:customer_companies,id',
            'name' => 'required|max:255',
            'phone' => 'nullable|max:30',
            'email' => 'nullable|email',
            'status' => 'required'
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Party $supplier)
    {
        abort_if(!in_array($supplier->type, ['Supplier', 'Both']), 404);

        return response()->json($supplier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Party $supplier)
    {
        abort_if(!in_array($supplier->type, ['Supplier', 'Both']), 404);

        $request->validate([
            'customer_company_id' => 'nullable|exists:customer_companies,id',
            'name' => 'required|max:255',
            'phone' => 'nullable|max:30',
            'email' => 'nullable|email',
            'status' => 'required'
        ]);

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

        // return redirect()->route('supplier.index')->with('success', 'Supplier updated successfully.');
        return response()->json([
            'status' => true,
            'message' => 'Supplier updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Party $supplier)
    {
        abort_if(!in_array($supplier->type, ['Supplier', 'Both']), 404);
        try {
            $supplier->delete();
            return response()->json([
                'status' => true,
                'message' => 'Supplier deleted successfully.'
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'This supplier is already used in transactions. You cannot delete it.'
            ], 422);
        }
    }
}
