<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AppSettingController extends Controller
{
    /**
     * Display the manage app settings blade.
     */
    public function index(): View
    {
        $setting = AppSetting::getSettings();

        return view('admin.app-setting.index', [
            'setting' => $setting,
        ]);
    }

    /**
     * Update app settings in storage.
     */
    public function update(Request $request): RedirectResponse
    {
        $setting = AppSetting::getSettings();

        $validated = $request->validate([
            'app_name' => ['nullable', 'string', 'max:255'],
            'app_short_description' => ['nullable', 'string', 'max:1000'],
            'logo_dark' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:3072'],
            'logo_light' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:3072'],
            'favicon' => ['nullable', 'file', 'mimes:ico,png,jpg,jpeg,svg,webp', 'max:2048'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_whatsapp' => ['nullable', 'string', 'max:50'],
            'contact_address' => ['nullable', 'string', 'max:1000'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'remove_logo_dark' => ['nullable', 'boolean'],
            'remove_logo_light' => ['nullable', 'boolean'],
            'remove_favicon' => ['nullable', 'boolean'],
        ]);

        // Handle Dark Logo removal / upload
        if ($request->boolean('remove_logo_dark')) {
            if ($setting->logo_dark && Storage::disk('public')->exists($setting->logo_dark)) {
                Storage::disk('public')->delete($setting->logo_dark);
            }
            $validated['logo_dark'] = null;
        } elseif ($request->hasFile('logo_dark')) {
            if ($setting->logo_dark && Storage::disk('public')->exists($setting->logo_dark)) {
                Storage::disk('public')->delete($setting->logo_dark);
            }
            $validated['logo_dark'] = $request->file('logo_dark')->store('settings/logos', 'public');
        } else {
            unset($validated['logo_dark']);
        }

        // Handle Light Logo removal / upload
        if ($request->boolean('remove_logo_light')) {
            if ($setting->logo_light && Storage::disk('public')->exists($setting->logo_light)) {
                Storage::disk('public')->delete($setting->logo_light);
            }
            $validated['logo_light'] = null;
        } elseif ($request->hasFile('logo_light')) {
            if ($setting->logo_light && Storage::disk('public')->exists($setting->logo_light)) {
                Storage::disk('public')->delete($setting->logo_light);
            }
            $validated['logo_light'] = $request->file('logo_light')->store('settings/logos', 'public');
        } else {
            unset($validated['logo_light']);
        }

        // Handle Favicon removal / upload
        if ($request->boolean('remove_favicon')) {
            if ($setting->favicon && Storage::disk('public')->exists($setting->favicon)) {
                Storage::disk('public')->delete($setting->favicon);
            }
            $validated['favicon'] = null;
        } elseif ($request->hasFile('favicon')) {
            if ($setting->favicon && Storage::disk('public')->exists($setting->favicon)) {
                Storage::disk('public')->delete($setting->favicon);
            }
            $validated['favicon'] = $request->file('favicon')->store('settings/favicons', 'public');
        } else {
            unset($validated['favicon']);
        }

        unset($validated['remove_logo_dark'], $validated['remove_logo_light'], $validated['remove_favicon']);

        $setting->update($validated);

        return redirect()
            ->route('admin.app-setting.index')
            ->with('success', 'App settings updated successfully.');
    }
}
