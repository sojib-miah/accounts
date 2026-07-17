<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->hasRole('Super-Admin')) {
            $roles = Role::with(['users', 'permissions'])->latest()->get();
        } elseif (auth()->user()->hasRole('Admin')) {
            $roles = Role::with(['users', 'permissions'])->whereNotIn('name', ['Super-Admin'])->latest()->get();
        } elseif (auth()->user()->hasRole('Manager')) {
            $roles = Role::with(['users', 'permissions'])->whereNotIn('name', ['Super-Admin', 'Admin'])->latest()->get();
        }
        $permissions = Permission::orderBy('name')->get();
        $users = User::with('roles')
            ->whereHas('roles')
            ->when($request->filled('search'), function ($query) use ($request) {

                $search = $request->search;

                $query->where(function ($q) use ($search) {

                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();
        $groupPermissions = [];
        foreach ($permissions as $permission) {
            $parts = explode('-', $permission->name);
            $module = ucfirst($parts[0]);
            $groupPermissions[$module][] = $permission;
        }
        ksort($groupPermissions);
        return view('BackEnd.Role.role', compact('roles', 'permissions', 'users', 'groupPermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        $role->syncPermissions(
            $request->permissions ?? []
        );

        return redirect()->back()
            ->with('success', 'Role Created Successfully');
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id
        ]);

        $role->update([
            'name' => $request->name
        ]);

        $role->syncPermissions(
            $request->permissions ?? []
        );

        return redirect()->back()
            ->with('success', 'Role Updated Successfully');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->back()
            ->with('success', 'Role Deleted Successfully');
    }
}
