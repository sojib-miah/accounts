<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    private function generateProductCode()
    {
        $last = Product::latest()->first();
        if (!$last) {
            return 'PRD-10001';
        }
        $number = (int) substr($last->product_code, 4);
        return 'PRD-' . ($number + 1);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');
        if (!Auth::user()->hasRole('Super-Admin')) {
            $query->where('company_id', Auth::user()->company_id);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('product_code', 'like', "%{$request->search}%")
                    ->orWhere('barcode', 'like', "%{$request->search}%")
                    ->orWhere('sku', 'like', "%{$request->search}%");
            });
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $products = $query->latest()->paginate(20)->withQueryString();

        $categories = Category::where('type', 'Product')->where('status', 'Active')->orderBy('name')->get();

        $statistics = [
            'total' => Product::count(),
            'active' => Product::where('status', 'Active')->count(),
            'inactive' => Product::where('status', 'Inactive')->count(),
            'low_stock' => Product::whereColumn('current_stock', '<=', 'minimum_stock')->count(),
        ];

        return view('BackEnd.Product.index', compact('products', 'categories', 'statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('type', 'Product')->where('status', 'Active')->orderBy('name')->get();

        return view('BackEnd.Product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'unit' => 'required'
        ]);

        Product::create([
            'company_id' => Auth::user()->company_id,
            'category_id' => $request->category_id,
            'product_code' => $this->generateProductCode(),
            'barcode' => $request->barcode,
            'sku' => $request->sku,
            'name' => $request->name,
            'purchase_price' => $request->purchase_price,
            'sale_price' => $request->sale_price,
            'minimum_stock' => $request->minimum_stock,
            'unit' => $request->unit,
            'description' => $request->description,
            'status' => $request->status,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('product.index')->with('success', 'Product Created Successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'company', 'creator']);

        return view('BackEnd.Product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::where('type', 'Product')->where('status', 'Active')->orderBy('name')->get();

        return view('BackEnd.Product.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'unit' => 'required'
        ]);

        $product->update([
            'category_id' => $request->category_id,
            'barcode' => $request->barcode,
            'sku' => $request->sku,
            'name' => $request->name,
            'purchase_price' => $request->purchase_price,
            'sale_price' => $request->sale_price,
            'minimum_stock' => $request->minimum_stock,
            'unit' => $request->unit,
            'status' => $request->status,
            'description' => $request->description,
        ]);

        return redirect()->route('product.index')->with('success', 'Product Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->receiptItems()->exists()) {
            return back()->with('error', 'This product is already used in transactions and cannot be deleted.');
        }
        $product->delete();
        return redirect()->route('product.index')->with('success', 'Product Deleted Successfully.');
    }
}
