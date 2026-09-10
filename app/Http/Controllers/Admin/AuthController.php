<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the admin login page.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Handle the admin login request.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');
        $email = $request->string('email')->trim()->lower()->value();
        $password = $request->string('password')->value();

        // 1. Check AdminUser table
        $adminUser = AdminUser::where('email', $email)->first();

        if ($adminUser) {
            if ($adminUser->status !== 'active') {
                return back()->withErrors([
                    'email' => 'Your administrative account has been deactivated. Please contact support.',
                ])->onlyInput('email');
            }

            if (Hash::check($password, $adminUser->password)) {
                // Ensure matching User record exists for standard Auth session
                $user = User::firstOrCreate(
                    ['email' => $adminUser->email],
                    [
                        'name' => $adminUser->name,
                        'password' => $adminUser->password,
                    ]
                );

                if ($user->name !== $adminUser->name || $user->password !== $adminUser->password) {
                    $user->update([
                        'name' => $adminUser->name,
                        'password' => $adminUser->password,
                    ]);
                }

                Auth::login($user, $remember);
                $request->session()->regenerate();

                return redirect()->intended(route('admin.dashboard'));
            }
        }

        // 2. Fallback to standard User table
        if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the admin out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('status', 'You have been successfully logged out.');
    }
}
