<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ReceiptItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with(['creator', 'updater'])->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        })->where('type', 'Expense')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->latest()->get();
        return view('BackEnd.Category.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validateWithBag('add', [
            'name'   => 'required|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        Category::create([
            'name'        => $request->name,
            'type'        => 'Expense',
            'status'      => $request->status,
            'created_by'  => auth()->id(),
        ]);

        return redirect()->route('category.index')->with('success', 'Category Created Successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validateWithBag('edit', [
            'name'   => 'required|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        $category->update([
            'name'       => $request->name,
            'status'     => $request->status,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('category.index')->with('success', 'Category Updated Successfully.');
    }

    public function destroy(Category $category)
    {
        foreach ($category->accountHeads as $head) {

            if (ReceiptItem::where('account_head_id', $head->id)->exists()) {

                return back()->with(
                    'error',
                    'This category has been used in transactions and cannot be deleted.'
                );
            }
        }

        $category->accountHeads()->delete();
        $category->delete();
        return redirect()->route('category.index')->with('success', 'Category Deleted Successfully.');
    }
}
