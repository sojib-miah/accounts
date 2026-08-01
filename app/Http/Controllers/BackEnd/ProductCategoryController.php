<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::where('type', 'Product')
            ->when(!auth()->user()->hasRole('Super-Admin'), function ($query) {
                $query->where('created_by', auth()->id());
            })->latest()->paginate(20);

        return view('BackEnd.ProductCategory.index', compact('categories'));
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
            'name' => 'required|max:255',
            'status' => 'required'
        ]);

        Category::create([
            'company_id' => Auth::user()->company_id,
            'name' => $request->name,
            'type' => 'Product',
            'status' => $request->status,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('product-category.index')->with('success', 'Category created successfully.');
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
    public function edit(Category $product_category)
    {
        abort_if($product_category->type != 'Product', 404);

        return response()->json($product_category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $product_category)
    {
        abort_if($product_category->type != 'Product', 404);

        $request->validate([
            'name' => 'required|max:255',
            'status' => 'required'
        ]);

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
        abort_if($product_category->type != 'Product', 404);
        $product_category->delete();
        return redirect()->route('product-category.index')->with('success', 'Category deleted successfully.');
    }
}
