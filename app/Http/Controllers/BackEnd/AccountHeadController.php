<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\AccountHead;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountHeadController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $accountHeads = AccountHead::with(['category', 'creator', 'company'])
            ->when($request->filled('company_id'), function ($query) use ($request) {
                $query->where('company_id', $request->company_id);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {

                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($category) use ($search) {
                            $category->where('name', 'like', "%{$search}%");
                        });
                });
            })->where('type', 'Expense')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
                $query->where('created_by', Auth::id());
            })->latest()->paginate(10)->withQueryString();

        $categories = Category::where('status', 'Active')->where('type', 'Expense')->orderBy('name')->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->get();

        $companies = Company::orderBy('name')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('id', $user->company_id)->orWhere('created_by', $user->id);
                });
            })->get();

        return view('BackEnd.AccountHead.index', compact('accountHeads', 'categories', 'companies'));
    }

    public function store(Request $request)
    {
        $request->validateWithBag('add', [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);
        AccountHead::create([
            'company_id' => auth()->user()->company_id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'type' => 'Expense',
            'status' => $request->status,
            'created_by' => auth()->id(),
        ]);
        return redirect()->route('account-head.index')->with('success', 'Account Head Created Successfully');
    }

    public function update(Request $request, AccountHead $accountHead)
    {
        $request->validateWithBag('edit', [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        $accountHead->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'status' => $request->status,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('account-head.index')->with('success', 'Account Head Updated Successfully');
    }

    public function destroy(AccountHead $accountHead)
    {
        if ($accountHead->receiptItems()->exists()) {

            return back()->with(
                'error',
                'This Account Head has already been used in transactions and cannot be deleted.'
            );
        }
        $accountHead->delete();

        return redirect()->route('account-head.index')->with('success', 'Account Head Deleted Successfully');
    }
}
