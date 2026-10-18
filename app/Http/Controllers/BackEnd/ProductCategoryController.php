<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $categories = Category::where('type', 'Product')
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
            ->when(!auth()->user()->hasRole('Super-Admin'), function ($query) {
                $query->where('created_by', auth()->id());
            })->latest()->paginate(10)->withQueryString();

        $companies = Company::orderBy('name')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('id', $user->company_id)->orWhere('created_by', $user->id);
                });
            })->get();

        return view('BackEnd.ProductCategory.index', compact('categories', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $companyId = Auth::user()->company_id;

        $request->validateWithBag('add', [
            'name' => ['required', 'string', 'max:100'],
            'status' => 'required'
        ]);

        Category::create([
            'company_id' => $companyId,
            'name' => $request->name,
            'type' => 'Product',
            'status' => $request->status,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('product-category.index')->with('success', 'Category created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $product_category)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100'],
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('product-category.index')
                ->withErrors($validator, 'edit')
                ->withInput()
                ->with('edit_category_id', $product_category->id);
        }

        $product_category->update([
            'name' => $request->name,
            'status' => $request->status,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('product-category.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $product_category)
    {
        if ($product_category->products()->exists()) {
            return redirect()->route('product-category.index')->with('error', 'This category cannot be deleted because it is already used in one or more products.');
        }

        $product_category->delete();

        return redirect()->route('product-category.index')->with('success', 'Category deleted successfully.');
    }
}
