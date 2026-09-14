<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\CustomerOtpMail;
use App\Models\Customer;
use App\Models\CustomerOtp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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
     * Send email OTP for customer login.
     */
    public function sendLoginOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $email = strtolower(trim($validated['email']));

        // Rate-limiting: check if OTP was sent within the last 30 seconds
        $existingOtp = CustomerOtp::where('email', $email)->latest()->first();
        if ($existingOtp && $existingOtp->created_at && $existingOtp->created_at->diffInSeconds(now()) < 30) {
            $waitTime = 30 - $existingOtp->created_at->diffInSeconds(now());

            return response()->json([
                'success' => false,
                'message' => "Please wait {$waitTime}s before requesting a new code.",
            ], 429);
        }

        $customer = Customer::where('email', $email)->first();
        $customerName = $customer?->name;

        // Generate OTP record
        $otp = CustomerOtp::generateFor(
            email: $email,
            name: $customerName
        );

        // Send OTP email
        try {
            Mail::to($email)->send(
                new CustomerOtpMail(
                    otpCode: $otp->otp_code,
                    customerName: $customerName,
                    email: $email
                )
            );
        } catch (\Throwable $e) {
            Log::error('Failed to send login OTP email: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Unable to dispatch login verification email. Please try again.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'A 6-digit login code has been sent to your email.',
            'email' => $email,
            'is_existing' => (bool) $customer,
        ]);
    }

    /**
     * Verify login OTP and authenticate customer.
     */
    public function verifyLoginOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $email = strtolower(trim($validated['email']));
        $otpCode = trim($validated['otp']);

        $otpRecord = CustomerOtp::where('email', $email)->latest()->first();

        if (! $otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'No active verification code found for this email. Please request a new one.',
            ], 422);
        }

        if ($otpRecord->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Your verification code has expired. Please request a new code.',
            ], 422);
        }

        if ($otpRecord->attempts >= 5) {
            return response()->json([
                'success' => false,
                'message' => 'Too many incorrect attempts. Please request a new verification code.',
            ], 429);
        }

        if (! $otpRecord->isValid($otpCode)) {
            $otpRecord->increment('attempts');

            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code. Please check and try again.',
            ], 422);
        }

        // Find existing customer or auto-create account
        $customer = Customer::where('email', $email)->first();
        if (! $customer) {
            $defaultName = $otpRecord->name ?: (explode('@', $email)[0] ?? 'Reader');
            $customer = Customer::create([
                'name' => ucwords(str_replace(['.', '_', '-'], ' ', $defaultName)),
                'email' => $email,
                'mobile' => $otpRecord->mobile,
                'password' => $otpRecord->password_hash ?: Hash::make(Str::random(16)),
                'email_verified_at' => now(),
            ]);
        } else {
            if (! $customer->email_verified_at) {
                $customer->update(['email_verified_at' => now()]);
            }
        }

        // Delete OTP record
        CustomerOtp::where('email', $email)->delete();

        // Authenticate customer
        Auth::guard('customer')->login($customer, true);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Welcome back, '.$customer->name.'! Logged in successfully.',
            'customer' => [
                'name' => $customer->name,
                'email' => $customer->email,
            ],
            'redirect_url' => route('home'),
        ]);
    }

    /**
     * Resend login OTP.
     */
    public function resendLoginOtp(Request $request): JsonResponse
    {
        return $this->sendLoginOtp($request);
    }

    /**
     * Send email OTP for customer registration.
     */
    public function sendRegistrationOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:customers,email'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $email = strtolower(trim($validated['email']));

        // Check if existing recent OTP was sent within the last 30 seconds
        $existingOtp = CustomerOtp::where('email', $email)->latest()->first();
        if ($existingOtp && $existingOtp->created_at && $existingOtp->created_at->diffInSeconds(now()) < 30) {
            $waitTime = 30 - $existingOtp->created_at->diffInSeconds(now());

            return response()->json([
                'success' => false,
                'message' => "Please wait {$waitTime}s before requesting a new code.",
            ], 429);
        }

        // Generate OTP record
        $otp = CustomerOtp::generateFor(
            email: $email,
            name: $validated['name'],
            password: $validated['password'] ?? Str::random(24),
            mobile: $validated['mobile'] ?? null
        );

        // Send OTP email
        try {
            Mail::to($email)->send(
                new CustomerOtpMail(
                    otpCode: $otp->otp_code,
                    customerName: $validated['name'],
                    email: $email
                )
            );
        } catch (\Throwable $e) {
            Log::error('Failed to send customer OTP email: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Unable to dispatch verification email. Please check your email address and try again.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'A 6-digit verification code has been sent to your email.',
            'email' => $email,
        ]);
    }

    /**
     * Verify OTP and complete customer registration.
     */
    public function verifyOtpAndRegister(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $email = strtolower(trim($validated['email']));
        $otpCode = trim($validated['otp']);

        $otpRecord = CustomerOtp::where('email', $email)->latest()->first();

        if (! $otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'No active verification code found for this email. Please request a new one.',
            ], 422);
        }

        if ($otpRecord->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Your verification code has expired. Please request a new code.',
            ], 422);
        }

        if ($otpRecord->attempts >= 5) {
            return response()->json([
                'success' => false,
                'message' => 'Too many incorrect attempts. Please request a new verification code.',
            ], 429);
        }

        if (! $otpRecord->isValid($otpCode)) {
            $otpRecord->increment('attempts');

            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code. Please check and try again.',
            ], 422);
        }

        // Check if customer account already exists
        $customer = Customer::where('email', $email)->first();
        if ($customer) {
            return response()->json([
                'success' => false,
                'message' => 'An account with this email already exists. Please sign in.',
            ], 422);
        }

        // Create the new customer
        $customer = Customer::create([
            'name' => $otpRecord->name ?: 'Customer',
            'email' => $email,
            'mobile' => $otpRecord->mobile,
            'password' => $otpRecord->password_hash ?: Hash::make(Str::random(16)),
            'email_verified_at' => now(),
        ]);

        // Clean up OTP record
        CustomerOtp::where('email', $email)->delete();

        // Authenticate customer
        Auth::guard('customer')->login($customer, true);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Your email was verified and account created successfully!',
            'customer' => [
                'name' => $customer->name,
                'email' => $customer->email,
            ],
            'redirect_url' => route('home'),
        ]);
    }

    /**
     * Resend registration OTP.
     */
    public function resendRegistrationOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $email = strtolower(trim($validated['email']));

        $existingOtp = CustomerOtp::where('email', $email)->latest()->first();
        if (! $existingOtp) {
            return response()->json([
                'success' => false,
                'message' => 'Registration session expired. Please enter your registration details again.',
            ], 422);
        }

        if ($existingOtp->created_at && $existingOtp->created_at->diffInSeconds(now()) < 30) {
            $waitTime = 30 - $existingOtp->created_at->diffInSeconds(now());

            return response()->json([
                'success' => false,
                'message' => "Please wait {$waitTime}s before requesting a new code.",
            ], 429);
        }

        // Generate fresh OTP
        $otp = CustomerOtp::generateFor(
            email: $email,
            name: $existingOtp->name,
            password: null, // Keeps previous hash in generateFor if we pass it or preserve
            mobile: $existingOtp->mobile
        );
        $otp->update(['password_hash' => $existingOtp->password_hash]);

        try {
            Mail::to($email)->send(
                new CustomerOtpMail(
                    otpCode: $otp->otp_code,
                    customerName: $existingOtp->name,
                    email: $email
                )
            );
        } catch (\Throwable $e) {
            Log::error('Failed to resend customer OTP email: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Unable to resend verification email. Please try again.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'A fresh verification code has been sent to your email.',
        ]);
    }

    /**
     * Register and sign in a new customer (standard form fallback).
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
            'email_verified_at' => now(),
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
