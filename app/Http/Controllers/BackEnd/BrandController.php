<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $brands = Brand::with('company')
            ->when($request->filled('company_id'), function ($query) use ($request) {
                $query->where('company_id', $request->company_id);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('company', function ($company) use ($search) {
                            $company->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when(
                !Auth::user()->hasRole('Super-Admin'),
                function ($query) {
                    $query->where('created_by', Auth::id());
                }
            )->latest()->paginate(10)->withQueryString();

        $companies = Company::orderBy('name')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('id', $user->company_id)->orWhere('created_by', $user->id);
                });
            })->get();

        return view('BackEnd.Brand.index', compact('brands', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validateWithBag('add', [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('brands', 'name')
                    ->where('company_id', Auth::user()->company_id),
            ],
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('brands', 'name')
                    ->where('company_id', Auth::user()->company_id)
                    ->ignore($brand->id),
            ],
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('brand.index')
                ->withErrors($validator, 'edit')
                ->withInput()
                ->with('edit_brand_id', $brand->id);
        }

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
        if ($brand->products()->count() > 0) {
            return redirect()->route('brand.index')->with('error', 'This brand is already used by products.');
        }

        $brand->delete();

        return redirect()->route('brand.index')->with('success', 'Brand deleted successfully.');
    }
}
