<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\AccountTransaction;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Receipt;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::with('company')

            ->when($request->filled('search'), function ($query) use ($request) {

                $search = $request->search;

                $query->where(function ($q) use ($search) {

                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('branch_id', 'like', "%{$search}%")
                        ->orWhere('phone_one', 'like', "%{$search}%")
                        ->orWhere('phone_two', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhereHas('company', function ($company) use ($search) {
                            $company->where('name', 'like', "%{$search}%");
                        });
                });
            })->latest()->get();
        $companies = Company::orderBy('name')->get();
        return view('BackEnd.Branch.index', compact('branches', 'companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|max:255',
            'phone_one' => 'required|max:30',
            'phone_two' => 'nullable|max:30',
            'email' => 'nullable|email',
            'address' => 'nullable'
        ]);

        // Generate Branch ID
        $lastBranch = Branch::latest('id')->first();

        if ($lastBranch) {
            $number = (int) str_replace('BR_', '', $lastBranch->branch_id);
            $number++;
        } else {
            $number = 10001;
        }

        $branchId = 'BR_' . $number;

        Branch::create([
            'company_id' => $request->company_id,
            'branch_id' => $branchId,
            'name' => $request->name,
            'phone_one' => $request->phone_one,
            'phone_two' => $request->phone_two,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return redirect()->route('branch.index')->with('success', 'Branch Created Successfully');
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|max:255',
            'phone_one' => 'required|max:30',
            'phone_two' => 'nullable|max:30',
            'email' => 'nullable|email',
            'address' => 'nullable'
        ]);

        $branch->update([
            'company_id' => $request->company_id,
            'name' => $request->name,
            'phone_one' => $request->phone_one,
            'phone_two' => $request->phone_two,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return redirect()->route('branch.index')->with('success', 'Branch Updated Successfully');
    }

    public function destroy(Branch $branch)
    {
        if (Receipt::where('branch_id', $branch->id)->exists()) {

            return back()->with(
                'error',
                'Branch cannot be deleted because receipts exist.'
            );
        }
        $branch->delete();
        return redirect()->route('branch.index')->with('success', 'Branch Deleted Successfully');
    }
}
