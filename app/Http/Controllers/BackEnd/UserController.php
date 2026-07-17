<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles')->with('company')->when($request->filled('search'), function ($query) use ($request) {

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

        return view('BackEnd.Users.user', compact(
            'users',
            'roles'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $user->assignRole($request->role);

        return back()->with('success', 'User Created Successfully');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        $user->syncRoles([$request->role]);

        return back()->with('success', 'User Updated Successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', 'User Deleted Successfully');
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
}
