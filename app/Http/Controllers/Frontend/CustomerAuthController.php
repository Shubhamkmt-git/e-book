<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;

class CustomerAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            return redirect()->route('home')->with('error', 'Google Sign-In is not configured yet. Please provide GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET in .env');
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google and authenticate.
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            /** @var User $googleUser */
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect()->route('home')->with('error', 'Google authentication failed or was cancelled. Please try again.');
        }

        $email = strtolower($googleUser->getEmail() ?? '');
        if (empty($email)) {
            return redirect()->route('home')->with('error', 'Unable to retrieve email from your Google account.');
        }

        $customer = Customer::where('google_id', $googleUser->getId())
            ->orWhere('email', $email)
            ->first();

        if ($customer) {
            $customer->update([
                'google_id' => $customer->google_id ?: $googleUser->getId(),
                'avatar' => $customer->avatar ?: $googleUser->getAvatar(),
                'email_verified_at' => $customer->email_verified_at ?: now(),
            ]);
        } else {
            $customer = Customer::create([
                'name' => $googleUser->getName() ?: ($googleUser->getNickname() ?: 'Customer'),
                'email' => $email,
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => now(),
            ]);
        }

        Auth::guard('customer')->login($customer, true);
        $request->session()->regenerate();

        return redirect()->intended(route('customer.profile'))
            ->with('status', 'Welcome, '.$customer->name.'! You have logged in with Google.');
    }

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
