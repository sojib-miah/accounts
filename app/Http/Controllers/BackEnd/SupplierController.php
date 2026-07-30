<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Party;
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
    public function index()
    {
        $suppliers = Party::whereIn('type', ['Supplier', 'Both'])
            ->when(!auth()->user()->hasRole('Super-Admin'), function ($query) {
                $query->where('created_by', auth()->id());
            })->latest()->paginate(20);

        return view('BackEnd.Supplier.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('BackEnd.Supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'company_name' => 'required|max:255',
            'phone' => 'nullable|max:30',
            'email' => 'nullable|email',
            'status' => 'required'
        ]);

        Party::create([
            'company_id' => Auth::user()->company_id,
            'party_id' => $this->generateSupplierId(),
            'name' => $request->name,
            'designation' => $request->designation,
            'phone' => $request->phone,
            'email' => $request->email,
            'company_name' => $request->company_name,
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
        return view('BackEnd.Supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Party $supplier)
    {
        abort_if(!in_array($supplier->type, ['Supplier', 'Both']), 404);

        $request->validate([
            'name' => 'required|max:255',
            'company_name' => 'required|max:255',
            'phone' => 'nullable|max:30',
            'email' => 'nullable|email',
            'status' => 'required'
        ]);

        $supplier->update([
            'name' => $request->name,
            'designation' => $request->designation,
            'phone' => $request->phone,
            'email' => $request->email,
            'company_name' => $request->company_name,
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
        abort_if(!in_array($supplier->type, ['Supplier', 'Both']), 404);
        $supplier->delete();
        return redirect()->route('supplier.index')->with('success', 'Supplier deleted successfully.');
    }
}
