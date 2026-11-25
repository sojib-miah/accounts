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
    public function index(Request $request)
    {
        $user = Auth::user();

        // Base query
        $query = CompanyPackage::query()
            ->with(['company', 'user', 'package']);

        // Access control
        if (!$user->hasRole('Super-Admin')) {
            $query->where('company_id', $user->company_id);
        }

        // Company filter
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereHas('company', function ($companyQuery) use ($search) {
                    $companyQuery->where('name', 'like', "%{$search}%");
                })
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('package', function ($packageQuery) use ($search) {
                        $packageQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $groupKeys = (clone $query)
            ->selectRaw('company_id, user_id, MAX(id) as latest_id')
            ->groupBy('company_id', 'user_id')
            ->orderByDesc('latest_id')
            ->paginate(10)
            ->withQueryString();

        // Get all assignment records belonging to the current page's groups
        $pageGroups = $groupKeys->getCollection();

        $recordsQuery = CompanyPackage::with([
            'company',
            'user',
            'package',
        ]);

        $recordsQuery->where(function ($outerQuery) use ($pageGroups) {
            foreach ($pageGroups as $groupKey) {
                $outerQuery->orWhere(function ($q) use ($groupKey) {
                    if ($groupKey->company_id === null) {
                        $q->whereNull('company_id');
                    } else {
                        $q->where('company_id', $groupKey->company_id);
                    }

                    if ($groupKey->user_id === null) {
                        $q->whereNull('user_id');
                    } else {
                        $q->where('user_id', $groupKey->user_id);
                    }
                });
            }
        });

        // Apply the same access restriction to the records query
        if (!$user->hasRole('Super-Admin')) {
            $recordsQuery->where('company_id', $user->company_id);
        }

        $allRecords = $recordsQuery
            ->orderByDesc('id')
            ->get();

        // Build Company + User groups
        $groupedRecords = $allRecords->groupBy(function ($record) {
            $companyKey = $record->company_id ?? 'null';
            $userKey = $record->user_id ?? 'null';

            return "company_{$companyKey}_user_{$userKey}";
        });

        // Prepare data for Blade
        $companyPackageGroups = $pageGroups->map(function ($groupKey) use ($groupedRecords) {
            $companyKey = $groupKey->company_id ?? 'null';
            $userKey = $groupKey->user_id ?? 'null';

            $key = "company_{$companyKey}_user_{$userKey}";

            $assignments = $groupedRecords->get($key, collect());

            return [
                'latest' => $assignments->first(),
                'previous' => $assignments->slice(1)->values(),
                'total' => $assignments->count(),
            ];
        })->filter(function ($group) {
            return $group['latest'] !== null;
        })->values();

        $groupKeys->setCollection($companyPackageGroups);

        // Dropdown data
        $companiesQuery = Company::query();

        if (!$user->hasRole('Super-Admin')) {
            $companiesQuery->where('id', $user->company_id);
        }

        $companies = $companiesQuery->orderBy('name')->get();

        $usersQuery = User::query();

        if (!$user->hasRole('Super-Admin')) {
            $usersQuery->where('company_id', $user->company_id);
        }

        $users = $usersQuery->orderBy('name')->get();

        $packages = Package::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('BackEnd.UserPackage.index', [
            'companyPackageGroups' => $groupKeys,
            'companies' => $companies,
            'users' => $users,
            'packages' => $packages,
        ]);
    }

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
