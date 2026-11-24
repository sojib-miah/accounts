<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserImpersonationController extends Controller
{
    /**
     * Show Login As User Form
     */
    public function index()
    {
        abort_unless(auth()->user()->hasRole('Super-Admin'), 403);

        return view('BackEnd.Users.login-as-user');
    }

    /**
     * Login as another user using ID and Username
     */
    public function login(Request $request)
    {
        $admin = Auth::user();

        // Only Super Admin can impersonate
        abort_unless($admin && $admin->hasRole('Super-Admin'), 403);

        $validated = $request->validate([
            'user_id' => ['required', 'integer'],
            'name' => ['required', 'string'],
        ]);

        // Find user by BOTH ID and Username
        $targetUser = User::where('id', $validated['user_id'])
            ->where('name', $validated['name'])
            ->first();

        if (!$targetUser) {
            throw ValidationException::withMessages([
                'name' => 'User ID and Username do not match.',
            ]);
        }

        // Prevent switching to the same account
        if ($admin->id === $targetUser->id) {
            throw ValidationException::withMessages([
                'name' => 'You are already logged in with this account.',
            ]);
        }

        // Save original admin information
        $adminId = $admin->id;
        $adminName = $admin->name;

        // Logout currently authenticated user
        Auth::logout();

        // Clear previous session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Login as selected user
        Auth::login($targetUser);

        // Regenerate session ID
        $request->session()->regenerate();

        // Store impersonation information
        $request->session()->put('impersonator_id', $adminId);
        $request->session()->put('impersonator_name', $adminName);

        // Audit log
        Log::info('Admin impersonation started', [
            'admin_id' => $adminId,
            'admin_name' => $adminName,
            'target_user_id' => $targetUser->id,
            'target_username' => $targetUser->username,
            'ip_address' => $request->ip(),
            'time' => now()->toDateTimeString(),
        ]);

        return redirect()->route('dashboard.index')->with('success', 'Successfully logged in as ' . $targetUser->name);
    }

    /**
     * Stop impersonation and return to original Super Admin
     */
    public function stop(Request $request)
    {
        $adminId = $request->session()->get('impersonator_id');

        if (!$adminId) {
            abort(403, 'No active impersonation session.');
        }

        // Find original admin
        $admin = User::find($adminId);

        if (!$admin || !$admin->hasRole('Super-Admin')) {
            abort(403, 'Original Super Admin account is unavailable.');
        }

        $currentUser = Auth::user();

        Log::info('Admin impersonation stopped', [
            'admin_id' => $admin->id,
            'target_user_id' => $currentUser?->id,
            'ip_address' => $request->ip(),
            'time' => now()->toDateTimeString(),
        ]);

        // Logout current user
        Auth::logout();

        // Clear current session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Restore original Super Admin
        Auth::login($admin);

        $request->session()->regenerate();

        return redirect()->route('dashboard.index')->with('success', 'Returned to your Super Admin account.');
    }
}
