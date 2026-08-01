<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::when(
            !Auth::user()->hasRole('Super-Admin'),
            function ($query) {
                $query->where('created_by', Auth::id());
            }
        )->latest()->paginate(20);
        return view('BackEnd.Brand.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:brands,name',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        Brand::create([
            'company_id' => Auth::user()->company_id,
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('brand.index')->with('success', 'Brand created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return response()->json($brand);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        if (
            !Auth::user()->hasRole('Super-Admin') &&
            $brand->created_by != Auth::id()
        ) {
            abort(404);
        }

        return response()->json($brand);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        if (
            !Auth::user()->hasRole('Super-Admin') &&
            $brand->created_by != Auth::id()
        ) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:100|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        $brand->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('brand.index')->with('success', 'Brand updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        if (
            !Auth::user()->hasRole('Super-Admin') &&
            $brand->created_by != Auth::id()
        ) {
            abort(404);
        }

        if ($brand->products()->count() > 0) {
            return redirect()->route('brand.index')->with('error', 'This brand is already used by products.');
        }

        $brand->delete();

        return redirect()->route('brand.index')->with('success', 'Brand deleted successfully.');
    }
}
