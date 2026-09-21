<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyPackage;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CompanyPackageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = CompanyPackage::with([
            'company',
            'user',
            'package',
        ])->latest();

        if (!$user->hasRole('Super-Admin')) {
            $query->where('company_id', $user->company_id);
        }

        $companyPackages = $query->get();

        $companiesQuery = Company::query();

        if (!$user->hasRole('Super-Admin')) {
            $companiesQuery->where('id', $user->company_id);
        }

        $companies = $companiesQuery->orderBy('name')->get();

        $usersQuery = User::query();

        if (!$user->hasRole('Super-Admin')) {
            $usersQuery->where(
                'company_id',
                $user->company_id
            );
        }

        $users = $usersQuery->orderBy('name')->get();

        $packages = Package::where('is_active', true)->orderBy('name')->get();

        return view(
            'BackEnd.UserPackage.index',
            compact(
                'companyPackages',
                'companies',
                'users',
                'packages'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'company_id' => ['required', 'exists:companies,id',],
            'user_id' => ['nullable', 'exists:users,id',],
            'package_id' => ['required', 'exists:packages,id',],
            'start_date' => ['required', 'date',],
            'expire_date' => ['nullable', 'date', 'after_or_equal:start_date',],
            'status' => ['required', Rule::in(['Active', 'Expired', 'Cancelled',]),],
        ];

        if (!$user->hasRole('Super-Admin')) {
            $rules['company_id'][] = Rule::in([
                $user->company_id
            ]);
        }

        $validated = $request->validate($rules);

        if (!empty($validated['user_id'])) {
            $validUser = User::where('id', $validated['user_id'])->where('company_id', $validated['company_id'])->exists();
            if (!$validUser) {
                return back()->withInput()->with('error', 'Selected user does not belong to the selected company.');
            }
        }

        CompanyPackage::create([
            'company_id' => $validated['company_id'],
            'user_id' => $validated['user_id'] ?? null,
            'package_id' => $validated['package_id'],
            'start_date' => $validated['start_date'],
            'expire_date' => $validated['expire_date'] ?? null,
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Package assigned successfully.');
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    | Only expire_date and status can be updated
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, CompanyPackage $companyPackage)
    {
        $user = Auth::user();

        if (!$user->hasRole('Super-Admin') && $companyPackage->company_id != $user->company_id) {
            abort(403);
        }

        $validated = $request->validate([
            'expire_date' => ['nullable', 'date', 'after_or_equal:' . $companyPackage->start_date->format('Y-m-d'),],
            'status' => [
                'required',
                Rule::in([
                    'Active',
                    'Expired',
                    'Cancelled',
                ]),
            ],

        ]);

        $companyPackage->update([
            'expire_date' => $validated['expire_date'] ?? null,
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Package updated successfully.');
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(CompanyPackage $companyPackage)
    {
        $user = Auth::user();

        if (!$user->hasRole('Super-Admin') && $companyPackage->company_id != $user->company_id) {
            abort(403);
        }

        $companyPackage->delete();

        return back()->with('success', 'Package deleted successfully.');
    }
}
