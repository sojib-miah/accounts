<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $packages = Package::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })->latest()->paginate(15)->withQueryString();

        return view('BackEnd.Package.index', compact('packages'));
    }

    public function create()
    {
        return redirect()->route('package.index');
    }

    public function store(Request $request)
    {
        $request->validateWithBag('add', [
            'name' => 'required|string|max:100|unique:packages,name',
            'price' => 'required|numeric|min:0',
            'user_limit' => 'required|integer|min:-1',
            'company_limit' => 'required|integer|min:-1',
            'branch_limit' => 'required|integer|min:-1',
            'income_limit' => 'required|integer|min:-1',
            'expense_limit' => 'required|integer|min:-1',
            'challan_limit' => 'required|integer|min:-1',
            'party_limit' => 'required|integer|min:-1',
            'account_limit' => 'required|integer|min:-1',
            'storage_limit' => 'required|integer|min:0',
            'remarks' => 'nullable|string',
        ]);
        Package::create([
            'name' => $request->name,
            'price' => $request->price,
            'user_limit' => $request->user_limit,
            'company_limit' => $request->company_limit,
            'branch_limit' => $request->branch_limit,
            'income_limit' => $request->income_limit,
            'expense_limit' => $request->expense_limit,
            'challan_limit' => $request->challan_limit,
            'party_limit' => $request->party_limit,
            'account_limit' => $request->account_limit,
            'storage_limit' => $request->storage_limit,
            'remarks' => $request->remarks,
            'is_active' => $request->boolean('is_active'),
        ]);
        return redirect()->route('package.index')->with('success', 'Package created successfully.');
    }

    public function show(Package $package)
    {
        return view('BackEnd.Package.show', compact('package'));
    }

    public function edit(Package $package)
    {
        return response()->json($package);
    }

    public function update(Request $request, Package $package)
    {
        $request->validateWithBag('edit', [
            'name' => 'required|string|max:100|unique:packages,name,' . $package->id,
            'price' => 'required|numeric|min:0',
            'user_limit' => 'required|integer|min:-1',
            'company_limit' => 'required|integer|min:-1',
            'branch_limit' => 'required|integer|min:-1',
            'income_limit' => 'required|integer|min:-1',
            'expense_limit' => 'required|integer|min:-1',
            'challan_limit' => 'required|integer|min:-1',
            'party_limit' => 'required|integer|min:-1',
            'account_limit' => 'required|integer|min:-1',
            'storage_limit' => 'required|integer|min:0',
            'remarks' => 'nullable|string',
        ]);
        $package->update([
            'name' => $request->name,
            'price' => $request->price,
            'user_limit' => $request->user_limit,
            'company_limit' => $request->company_limit,
            'branch_limit' => $request->branch_limit,
            'income_limit' => $request->income_limit,
            'expense_limit' => $request->expense_limit,
            'challan_limit' => $request->challan_limit,
            'party_limit' => $request->party_limit,
            'account_limit' => $request->account_limit,
            'storage_limit' => $request->storage_limit,
            'remarks' => $request->remarks,
            'is_active' => $request->boolean('is_active'),
        ]);
        return redirect()->route('package.index')->with('success', 'Package updated successfully.');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('package.index')->with('success', 'Package deleted successfully.');
    }
}
