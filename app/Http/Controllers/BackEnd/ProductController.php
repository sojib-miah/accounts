<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Brand;
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
            $query->where(function ($q) {
                $q->where('company_id', Auth::user()->company_id)
                    ->where('created_by', Auth::id());
            });
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

        $products = $query->latest()
            ->paginate(20)
            ->withQueryString();

        $categories = Category::where('type', 'Product')
            ->where('status', 'Active')
            ->when(!Auth::user()->hasRole('Super-Admin'), function ($q) {
                $q->where('created_by', Auth::id());
            })
            ->orderBy('name')
            ->get();

        $brands = Brand::where('status', 'Active')
            ->when(!Auth::user()->hasRole('Super-Admin'), function ($q) {
                $q->where('created_by', Auth::id());
            })
            ->orderBy('name')
            ->get();

        $statisticsQuery = Product::query();

        if (!Auth::user()->hasRole('Super-Admin')) {
            $statisticsQuery->where(function ($q) {
                $q->where('company_id', Auth::user()->company_id)
                    ->where('created_by', Auth::id());
            });
        }

        $statistics = [
            'total' => (clone $statisticsQuery)->count(),
            'active' => (clone $statisticsQuery)->where('status', 'Active')->count(),
            'inactive' => (clone $statisticsQuery)->where('status', 'Inactive')->count(),
            'low_stock' => (clone $statisticsQuery)
                ->whereColumn('current_stock', '<=', 'minimum_stock')
                ->count(),
        ];

        return view('BackEnd.Product.index', compact('products', 'categories', 'brands', 'statistics'));
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
            'product_code' => 'required|unique:products,product_code',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|max:255',
            'model_no' => 'required|unique:products,model_no|max:255',
            'minimum_stock' => 'required|numeric|min:0',
            'unit' => 'required',
            'sku' => 'required|unique:products,sku|max:255',
        ]);

        Product::create([
            'company_id' => Auth::user()->company_id,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'product_code' => $request->product_code,
            'model_no' => $request->model_no,
            'barcode' => $request->barcode,
            'sku' => $request->sku,
            'name' => $request->name,
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
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_code' => 'required|unique:products,product_code,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|max:255',
            'model_no' => 'required|max:255|unique:products,model_no,' . $product->id,
            'minimum_stock' => 'required|numeric|min:0',
            'unit' => 'required',
            'sku' => 'required|max:255|unique:products,sku,' . $product->id,
        ]);

        $product->update([
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'product_code' => $request->product_code,
            'model_no' => $request->model_no,
            'barcode' => $request->barcode,
            'sku' => $request->sku,
            'name' => $request->name,
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
