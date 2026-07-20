<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PackageHelper;

class CompanyUserController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $users = User::with(['company', 'branch', 'creator'])
            ->when($request->filled('search'), function ($query) use ($request) {

                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhereHas('company', function ($company) use ($search) {
                            $company->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('branch', function ($branch) use ($search) {
                            $branch->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('company_id', $user->company_id)
                        ->orWhere('created_by', $user->id);
                });
            })
            ->latest()
            ->get();

        $companies = Company::orderBy('name')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('id', $user->company_id)
                        ->orWhere('created_by', $user->id);
                });
            })
            ->get();

        $branches = Branch::orderBy('name')
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('company_id', $user->company_id)
                        ->orWhere('created_by', $user->id);
                });
            })
            ->get();

        return view('BackEnd.CompanyUser.index', compact('users', 'companies', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validateWithBag('add', [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|max:30',
            'company_id' => 'required|exists:companies,id',
            'branch_id' => 'required|exists:branches,id',
            'password' => 'required|min:6'
        ]);

        if (!Auth::user()->hasRole('Super-Admin')) {
            $companyPackage = PackageHelper::package();
            if (!$companyPackage) {
                return back()->with('error', 'No active package assigned.');
            }
            $limit = $companyPackage->package->user_limit;

            $current = User::where('company_id', Auth::user()->company_id)->count();
            if ($limit != -1 && $current >= $limit) {
                return back()->with('error', 'Your company limit has been exceeded.');
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company_id' => $request->company_id,
            'branch_id' => $request->branch_id,
            'created_by' => auth()->id(),
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('User');

        return redirect()->route('user.index')->with('success', 'User Created Successfully');
    }

    public function update(Request $request, User $user)
    {
        $request->validateWithBag('edit', [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|max:30',
            'company_id' => 'required|exists:companies,id',
            'branch_id' => 'required|exists:branches,id',
            'password' => 'required|min:6'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company_id' => $request->company_id,
            'branch_id' => $request->branch_id,
            // 'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user.index')->with('success', 'User Updated Successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User Deleted Successfully');
    }
}
