<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use App\Models\CompanyPackage;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with([
            'roles',
            'company',
            'companyPackage.package'
        ])->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('roles', function ($roles) use ($search) {
                        $roles->where('name', 'like', "%{$search}%");
                    });
            });
        })->latest()->paginate(10)->withQueryString();

        $roles = Role::orderBy('name')->get();
        $packages = Package::where('is_active', true)->orderBy('name')->get();

        return view('BackEnd.Users.user', compact('users', 'roles', 'packages'));
    }

    public function store(Request $request)
    {
        $request->validateWithBag('add', [
            'name'     => 'required',
            'company_name'     => 'required',
            'branch_name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required',
            'package_id' => 'required|exists:packages,id'
        ]);

        DB::beginTransaction();

        try {

            $company = Company::create([
                'name'       => $request->company_name,
                'created_by' => Auth::id(),
            ]);

            $lastBranch = Branch::orderByDesc('branch_id')->first();
            $branchId = $lastBranch ? $lastBranch->branch_id + 1 : 10001;

            $branch = Branch::create([
                'company_id' => $company->id,
                'branch_id'  => $branchId,
                'name'       => $request->branch_name,
                'created_by' => Auth::id(),
            ]);

            $user = User::create([
                'name'       => $request->name,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
                'company_id' => $company->id,
                'branch_id'  => $branch->id,
                'created_by' => Auth::id(),
            ]);

            CompanyPackage::create([
                'company_id' => $company->id,
                'user_id' => $user->id,
                'package_id' => $request->package_id,
                'start_date' => today(),
                'expire_date' => today()->addYear(),
                'status' => 'Active',
            ]);

            $user->assignRole($request->role);
            DB::commit();
            return back()->with('success', 'User Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, User $user)
    {
        $request->validateWithBag('edit', [
            'name'       => 'required',
            'company_name'     => 'required',
            'branch_name'     => 'required',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'role'       => 'required',
            'package_id' => 'required|exists:packages,id',
        ]);
        DB::beginTransaction();
        try {
            $user->company->update([
                'name' => $request->company_name,
            ]);

            $user->branch->update([
                'name' => $request->branch_name,
            ]);
            $user->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);
            $user->syncRoles([$request->role]);
            CompanyPackage::updateOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'company_id' => $user->company_id,
                    'package_id' => $request->package_id,
                    'start_date' => now(),
                    'expire_date' => now()->addYear(),
                    'status' => 'Active',
                ]
            );
            DB::commit();
            return back()->with('success', 'User Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            // $user->syncRoles([]);
            $user->delete();
            CompanyPackage::where('user_id', $user->id)->update(['status' => 'Cancelled',]);
            DB::commit();
            return back()->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    // update profile 
    public function profile()
    {
        $user = Auth::user();
        return view(
            'BackEnd.Profile.profile',
            compact('user')
        );
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $user = Auth::user();

        $imageName = $user->image;

        if ($request->hasFile('image')) {

            if (
                $user->image &&
                file_exists(public_path('uploads/users/' . $user->image))
            ) {
                unlink(public_path('uploads/users/' . $user->image));
            }

            $file = $request->file('image');

            $imageName =
                time() . '_' .
                uniqid() . '.' .
                $file->getClientOriginalExtension();

            $file->move(
                public_path('uploads/users'),
                $imageName
            );
        }

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'image' => $imageName,
        ]);

        return back()->with(
            'success',
            'Profile Updated Successfully'
        );
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);
        $user = Auth::user();
        if (!Hash::check(
            $request->current_password,
            $user->password
        )) {
            return back()->with(
                'error',
                'Current password incorrect'
            );
        }
        $user->update([
            'password' => Hash::make(
                $request->password
            )
        ]);
        return back()->with(
            'success',
            'Password Changed Successfully'
        );
    }

    // soft delete method 
    public function deleted()
    {
        $users = User::onlyTrashed()->with(['company', 'branch'])->latest('deleted_at')->paginate(20);
        return view('BackEnd.Users.deleted', compact('users'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return back()->with('success', 'User restored successfully.');
    }

    public function forceDelete($id)
    {
        DB::beginTransaction();
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            CompanyPackage::where('user_id', $user->id)->delete();
            $user->forceDelete();
            DB::commit();
            return back()->with('success', 'User permanently deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
