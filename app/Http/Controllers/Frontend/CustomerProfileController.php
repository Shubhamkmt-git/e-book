<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerProfileController extends Controller
{
    /**
     * Display the customer profile and purchase history.
     */
    public function index(Request $request): View
    {
        /** @var Customer $customer */
        $customer = auth('customer')->user();

        // Fetch purchase history ordered by latest
        $purchases = $customer->purchases()
            ->latest()
            ->get();

        return view('frontend.profile.index', [
            'customer' => $customer,
            'purchases' => $purchases,
        ]);
    }

    /**
     * Show the form for editing the customer profile.
     */
    public function edit(): View
    {
        return view('frontend.profile.edit', [
            'customer' => auth('customer')->user(),
        ]);
    }

    /**
     * Update the customer's profile.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = auth('customer')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:customers,email,'.$customer->id],
            'mobile' => ['nullable', 'string', 'max:20', 'unique:customers,mobile,'.$customer->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $customer->name = $validated['name'];
        $customer->email = $validated['email'];
        $customer->mobile = $validated['mobile'] ?? null;

        if (! empty($validated['password'])) {
            $customer->password = Hash::make($validated['password']);
        }

        $customer->save();

        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully!');
    }
}
