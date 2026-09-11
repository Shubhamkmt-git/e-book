<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    /**
     * Register and sign in a new customer.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:customers,email'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $customer = Customer::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'mobile' => $validated['mobile'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::guard('customer')->login($customer, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('home'))
            ->with('status', 'Your customer account has been created successfully.');
    }

    /**
     * Authenticate an existing customer created by the admin or registration form.
     */
    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials = [
            'email' => strtolower($validated['email']),
            'password' => $validated['password'],
        ];

        if (! Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'The provided customer credentials do not match our records.',
            ])->withInput($request->only('email'))->with('auth_tab', 'signin');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    /**
     * Sign the current customer out.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'You have been successfully logged out.');
    }
}
