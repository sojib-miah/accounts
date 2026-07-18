<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeCategoryController extends Controller
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
        })->where('type', 'Income')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->latest()->get();
        return view('BackEnd.IncomeCategory.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        Category::create([
            'name'        => $request->name,
            'type'        => 'Income',
            'status'      => $request->status,
            'created_by'  => auth()->id(),
        ]);

        return redirect()->route('income.category.index')->with('success', 'Category Created Successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'   => 'required|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        $category->update([
            'name'       => $request->name,
            'status'     => $request->status,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('income.category.index')->with('success', 'Category Updated Successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->accountHeads()->exists()) {

            return back()->with(
                'error',
                'This category is already used by Income List. Delete those Account Income List.'
            );
        }
        $category->delete();
        return redirect()->route('income.category.index')->with('success', 'Category Deleted Successfully.');
    }
}
