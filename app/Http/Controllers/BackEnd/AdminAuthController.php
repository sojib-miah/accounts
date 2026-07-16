<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    public function registerForm()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard.index');
        }
        return view('BackEnd.Auth.register');
    }

    public function registerStore(Request $request)
    {
        $request->validate([

            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('admin.login')->with('success', 'Registration successful');
    }

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    public function loginForm()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard.index');
        }
        return view('BackEnd.Auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);

        $field = filter_var(
            $request->login,
            FILTER_VALIDATE_EMAIL
        ) ? 'email' : 'name';

        if (
            Auth::attempt([
                $field => $request->login,
                'password' => $request->password
            ])
        ) {

            $request->session()->regenerate();

            return redirect()
                ->route('dashboard.index');
        }

        return back()->with(
            'error',
            'Invalid credentials'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    /*
    |--------------------------------------------------------------------------
    | Forgot Password
    |--------------------------------------------------------------------------
    */

    public function forgotPasswordForm()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard.index');
        }
        return view('BackEnd.Auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email not found');
        }
        $token = Str::random(64);
        DB::table('password_reset_tokens')
            ->updateOrInsert(
                ['email' => $user->email],
                [
                    'token' => Hash::make($token),
                    'created_at' => now()
                ]
            );

        $link = route('admin.password.reset', $token) . '?email=' . urlencode($user->email);

        Mail::raw(
            "Reset Password: $link",
            function ($message) use ($user) {
                $message->to($user->email)->subject('Reset Password');
            }
        );
        return back()->with('success', 'Reset link sent');
    }

    /*
    |--------------------------------------------------------------------------
    | Reset Password
    |--------------------------------------------------------------------------
    */

    public function resetPasswordForm(Request $request, $token)
    {
        if (auth()->check()) {
            return redirect()->route('dashboard.index');
        }
        return view(
            'BackEnd.Auth.reset-password',
            [
                'token' => $token,
                'email' => $request->email
            ]
        );
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->with('error', 'User not found');
        }
        $user->update([
            'password' => Hash::make(
                $request->password
            )
        ]);
        event(new PasswordReset($user));
        return redirect()->route('admin.login')->with('success', 'Password reset successfully');
    }
}
