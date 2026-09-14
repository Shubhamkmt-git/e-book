<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\FirebaseAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;

class CustomerAuthController extends Controller
{
    /**
     * Authenticate or register customer using verified Firebase ID Token.
     */
    public function firebaseAuth(Request $request, FirebaseAuthService $firebaseAuthService): JsonResponse
    {
        $validated = $request->validate([
            'id_token' => ['required', 'string'],
            'name' => ['nullable', 'string', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:20'],
        ]);

        try {
            $firebaseUser = $firebaseAuthService->verifyIdToken($validated['id_token']);
        } catch (\Throwable $e) {
            Log::warning('Firebase ID token verification failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Firebase authentication verification failed: '.$e->getMessage(),
            ], 401);
        }

        $email = $firebaseUser['email'] ? strtolower(trim($firebaseUser['email'])) : null;
        if (! $email) {
            return response()->json([
                'success' => false,
                'message' => 'Firebase account does not have an associated email address.',
            ], 422);
        }

        $customer = Customer::where('email', $email)->first();

        if (! $customer) {
            $name = $validated['name']
                ?: $firebaseUser['name']
                ?: (explode('@', $email)[0] ?? 'Reader');

            $customer = Customer::create([
                'name' => ucwords(str_replace(['.', '_', '-'], ' ', $name)),
                'email' => $email,
                'mobile' => $validated['mobile'] ?? $firebaseUser['phone_number'] ?? null,
                'password' => Hash::make(Str::random(24)),
                'email_verified_at' => $firebaseUser['email_verified'] ? now() : null,
            ]);
        } else {
            if ($firebaseUser['email_verified'] && ! $customer->email_verified_at) {
                $customer->update(['email_verified_at' => now()]);
            }
            if (! empty($validated['name']) && $customer->name === 'Reader') {
                $customer->update(['name' => $validated['name']]);
            }
        }

        Auth::guard('customer')->login($customer, true);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Welcome, '.$customer->name.'! Signed in successfully.',
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
            ],
            'redirect_url' => route('home'),
        ]);
    }

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
