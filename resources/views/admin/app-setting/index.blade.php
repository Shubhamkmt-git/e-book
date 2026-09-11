@extends('admin.layouts.app')

@section('title', 'App Settings')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">

    <!-- Top Header: Title + Primary Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200/80">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-lg shadow-2xs">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">App Settings</h1>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Manage core system identity, logo assets, contact information, social links, and SEO configuration.</p>
        </div>

        <div class="flex items-center gap-3">
            <button 
                type="submit" 
                form="app-settings-form" 
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs sm:text-sm font-semibold shadow-sm shadow-brand-600/20 transition cursor-pointer"
            >
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save All Settings</span>
            </button>
        </div>
    </div>

    <!-- Main Form -->
    <form id="app-settings-form" method="POST" action="{{ route('admin.app-setting.update') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- SECTION 1: General Information -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs p-5 sm:p-7 space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                <div class="w-8 h-8 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 font-roboto">General Information</h2>
                    <p class="text-xs text-slate-500">System title and high-level platform summary.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- App Name -->
                <div class="space-y-2 md:col-span-2 lg:col-span-1">
                    <label for="app_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        App Name <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-cube text-xs"></i>
                        </div>
                        <input 
                            type="text" 
                            id="app_name" 
                            name="app_name" 
                            value="{{ old('app_name', $setting->app_name) }}" 
                            placeholder="e.g. E-Book CMS" 
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 placeholder-slate-400 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('app_name') border-rose-500 @enderror"
                        >
                    </div>
                    @error('app_name')
                    <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Short Description -->
                <div class="space-y-2 md:col-span-2">
                    <label for="app_short_description" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        App Short Description
                    </label>
                    <div class="relative">
                        <textarea 
                            id="app_short_description" 
                            name="app_short_description" 
                            rows="2" 
                            placeholder="Brief tagline or description of the platform..." 
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 placeholder-slate-400 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium resize-y @error('app_short_description') border-rose-500 @enderror"
                        >{{ old('app_short_description', $setting->app_short_description) }}</textarea>
                    </div>
                    @error('app_short_description')
                    <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- SECTION 2: Logos & Favicon with Instant Frames and Previews -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs p-5 sm:p-7 space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                <div class="w-8 h-8 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-palette"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 font-roboto">Brand Logos & Favicon</h2>
                    <p class="text-xs text-slate-500">Upload graphical identity assets with instant live preview frames.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- 1. Logo for Dark / Black Background -->
                <div class="flex flex-col justify-between p-5 rounded-2xl border border-slate-200/90 bg-slate-50/50 space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-800">Logo (Dark BG)</span>
                            <span class="px-2 py-0.5 rounded-md bg-slate-900 text-slate-200 text-[10px] font-bold">Dark Canvas</span>
                        </div>
                        <p class="text-[11px] text-slate-500">Optimized for headers & dark sidebars (PNG, SVG, WebP).</p>
                    </div>

                    <!-- Dark Canvas Preview Frame -->
                    <div class="relative group w-full h-36 rounded-xl bg-gradient-to-b from-slate-950 via-brand-950 to-slate-900 border border-slate-800 flex items-center justify-center p-4 shadow-inner overflow-hidden">
                        <div id="logo-dark-placeholder" class="{{ $setting->logo_dark_url ? 'hidden' : 'flex' }} flex-col items-center justify-center text-center text-slate-500 gap-2">
                            <i class="fa-solid fa-image text-2xl text-slate-600"></i>
                            <span class="text-[11px] font-medium text-slate-400">No Dark Logo Set</span>
                        </div>

                        <img 
                            id="logo-dark-preview" 
                            src="{{ $setting->logo_dark_url ?? '' }}" 
                            alt="Logo Dark Background Preview" 
                            class="{{ $setting->logo_dark_url ? '' : 'hidden' }} max-h-24 max-w-full object-contain transition-transform duration-200 hover:scale-105"
                        >

                        <!-- Hidden removal flag -->
                        <input type="hidden" name="remove_logo_dark" id="remove_logo_dark" value="0">
                    </div>

                    <!-- Upload & Remove Actions -->
                    <div class="space-y-2">
                        <input 
                            type="file" 
                            name="logo_dark" 
                            id="logo_dark_input" 
                            accept="image/png,image/jpeg,image/svg+xml,image/webp" 
                            class="hidden"
                            onchange="handleImagePreview(this, 'logo-dark-preview', 'logo-dark-placeholder', 'remove-logo-dark-btn', 'remove_logo_dark')"
                        >
                        <div class="flex items-center gap-2">
                            <button 
                                type="button" 
                                onclick="document.getElementById('logo_dark_input').click()" 
                                class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-800 border border-slate-200 text-xs font-semibold shadow-2xs transition cursor-pointer"
                            >
                                <i class="fa-solid fa-cloud-arrow-up text-brand-600"></i>
                                <span>Select Image</span>
                            </button>
                            <button 
                                type="button" 
                                id="remove-logo-dark-btn"
                                onclick="clearImagePreview('logo_dark_input', 'logo-dark-preview', 'logo-dark-placeholder', 'remove-logo-dark-btn', 'remove_logo_dark')" 
                                class="{{ $setting->logo_dark_url ? 'inline-flex' : 'hidden' }} items-center justify-center px-3 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 text-xs font-semibold transition cursor-pointer"
                                title="Remove Logo"
                            >
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                        @error('logo_dark')
                        <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- 2. Logo for Light / White Background -->
                <div class="flex flex-col justify-between p-5 rounded-2xl border border-slate-200/90 bg-slate-50/50 space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-800">Logo (White BG)</span>
                            <span class="px-2 py-0.5 rounded-md bg-white border border-slate-200 text-slate-700 text-[10px] font-bold">Light Canvas</span>
                        </div>
                        <p class="text-[11px] text-slate-500">Optimized for invoices & bright web pages (PNG, SVG, WebP).</p>
                    </div>

                    <!-- Light Canvas Preview Frame -->
                    <div class="relative group w-full h-36 rounded-xl bg-white border border-slate-200 flex items-center justify-center p-4 shadow-inner overflow-hidden">
                        <div id="logo-light-placeholder" class="{{ $setting->logo_light_url ? 'hidden' : 'flex' }} flex-col items-center justify-center text-center text-slate-400 gap-2">
                            <i class="fa-solid fa-image text-2xl text-slate-300"></i>
                            <span class="text-[11px] font-medium text-slate-400">No Light Logo Set</span>
                        </div>

                        <img 
                            id="logo-light-preview" 
                            src="{{ $setting->logo_light_url ?? '' }}" 
                            alt="Logo Light Background Preview" 
                            class="{{ $setting->logo_light_url ? '' : 'hidden' }} max-h-24 max-w-full object-contain transition-transform duration-200 hover:scale-105"
                        >

                        <!-- Hidden removal flag -->
                        <input type="hidden" name="remove_logo_light" id="remove_logo_light" value="0">
                    </div>

                    <!-- Upload & Remove Actions -->
                    <div class="space-y-2">
                        <input 
                            type="file" 
                            name="logo_light" 
                            id="logo_light_input" 
                            accept="image/png,image/jpeg,image/svg+xml,image/webp" 
                            class="hidden"
                            onchange="handleImagePreview(this, 'logo-light-preview', 'logo-light-placeholder', 'remove-logo-light-btn', 'remove_logo_light')"
                        >
                        <div class="flex items-center gap-2">
                            <button 
                                type="button" 
                                onclick="document.getElementById('logo_light_input').click()" 
                                class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-800 border border-slate-200 text-xs font-semibold shadow-2xs transition cursor-pointer"
                            >
                                <i class="fa-solid fa-cloud-arrow-up text-brand-600"></i>
                                <span>Select Image</span>
                            </button>
                            <button 
                                type="button" 
                                id="remove-logo-light-btn"
                                onclick="clearImagePreview('logo_light_input', 'logo-light-preview', 'logo-light-placeholder', 'remove-logo-light-btn', 'remove_logo_light')" 
                                class="{{ $setting->logo_light_url ? 'inline-flex' : 'hidden' }} items-center justify-center px-3 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 text-xs font-semibold transition cursor-pointer"
                                title="Remove Logo"
                            >
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                        @error('logo_light')
                        <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- 3. Favicon with Browser Tab Preview -->
                <div class="flex flex-col justify-between p-5 rounded-2xl border border-slate-200/90 bg-slate-50/50 space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-800">Favicon</span>
                            <span class="px-2 py-0.5 rounded-md bg-brand-50 text-brand-700 text-[10px] font-bold">Browser Tab</span>
                        </div>
                        <p class="text-[11px] text-slate-500">Browser icon in tabs & bookmarks (ICO, PNG, SVG).</p>
                    </div>

                    <!-- Minimal Favicon Preview -->
                    <div class="w-full h-36 rounded-xl bg-slate-100 border border-slate-200/80 flex items-center justify-center shadow-inner">
                        <div class="flex flex-col items-center gap-2">
                            <!-- Icon container: relative so img and placeholder stack correctly -->
                            <div class="relative w-14 h-14 rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
                                <img
                                    id="favicon-preview"
                                    src="{{ $setting->favicon_url ?? '' }}"
                                    alt="Favicon Preview"
                                    class="{{ $setting->favicon_url ? 'block' : 'hidden' }} absolute inset-0 w-full h-full object-contain p-1"
                                >
                                <span id="favicon-placeholder" class="{{ $setting->favicon_url ? 'hidden' : 'flex' }} absolute inset-0 items-center justify-center">
                                    <i class="fa-solid fa-globe text-2xl text-slate-300"></i>
                                </span>
                            </div>
                            <span class="text-[10px] font-medium text-slate-400 tracking-wide">Favicon Preview</span>
                        </div>

                        <!-- Hidden removal flag -->
                        <input type="hidden" name="remove_favicon" id="remove_favicon" value="0">
                    </div>


                    <!-- Upload & Remove Actions -->
                    <div class="space-y-2">
                        <input 
                            type="file" 
                            name="favicon" 
                            id="favicon_input" 
                            accept=".ico,image/png,image/jpeg,image/svg+xml,image/webp" 
                            class="hidden"
                            onchange="handleImagePreview(this, 'favicon-preview', 'favicon-placeholder', 'remove-favicon-btn', 'remove_favicon')"
                        >
                        <div class="flex items-center gap-2">
                            <button 
                                type="button" 
                                onclick="document.getElementById('favicon_input').click()" 
                                class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-800 border border-slate-200 text-xs font-semibold shadow-2xs transition cursor-pointer"
                            >
                                <i class="fa-solid fa-cloud-arrow-up text-brand-600"></i>
                                <span>Select Favicon</span>
                            </button>
                            <button 
                                type="button" 
                                id="remove-favicon-btn"
                                onclick="clearImagePreview('favicon_input', 'favicon-preview', 'favicon-placeholder', 'remove-favicon-btn', 'remove_favicon')" 
                                class="{{ $setting->favicon_url ? 'inline-flex' : 'hidden' }} items-center justify-center px-3 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 text-xs font-semibold transition cursor-pointer"
                                title="Remove Favicon"
                            >
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                        @error('favicon')
                        <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>
        </div>

        <!-- SECTION 3: Contact Information -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs p-5 sm:p-7 space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                <div class="w-8 h-8 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 font-roboto">Contact Information</h2>
                    <p class="text-xs text-slate-500">Official communication channels displayed across support & footers.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Mail -->
                <div class="space-y-2">
                    <label for="contact_email" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Official Mail Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-envelope text-xs"></i>
                        </div>
                        <input 
                            type="email" 
                            id="contact_email" 
                            name="contact_email" 
                            value="{{ old('contact_email', $setting->contact_email) }}" 
                            placeholder="support@domain.com" 
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 placeholder-slate-400 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('contact_email') border-rose-500 @enderror"
                        >
                    </div>
                    @error('contact_email')
                    <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mobile Number -->
                <div class="space-y-2">
                    <label for="contact_phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Mobile Number
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-phone text-xs"></i>
                        </div>
                        <input 
                            type="text" 
                            id="contact_phone" 
                            name="contact_phone" 
                            value="{{ old('contact_phone', $setting->contact_phone) }}" 
                            placeholder="+1 (555) 000-0000" 
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 placeholder-slate-400 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('contact_phone') border-rose-500 @enderror"
                        >
                    </div>
                    @error('contact_phone')
                    <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- WhatsApp Number -->
                <div class="space-y-2">
                    <label for="contact_whatsapp" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        WhatsApp Number
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-600">
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                        </div>
                        <input 
                            type="text" 
                            id="contact_whatsapp" 
                            name="contact_whatsapp" 
                            value="{{ old('contact_whatsapp', $setting->contact_whatsapp) }}" 
                            placeholder="+1 (555) 000-0000" 
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 placeholder-slate-400 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('contact_whatsapp') border-rose-500 @enderror"
                        >
                    </div>
                    @error('contact_whatsapp')
                    <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="space-y-2 md:col-span-3">
                    <label for="contact_address" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Physical Address
                    </label>
                    <div class="relative">
                        <textarea 
                            id="contact_address" 
                            name="contact_address" 
                            rows="2" 
                            placeholder="Street Address, City, State, ZIP, Country" 
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 placeholder-slate-400 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium resize-y @error('contact_address') border-rose-500 @enderror"
                        >{{ old('contact_address', $setting->contact_address) }}</textarea>
                    </div>
                    @error('contact_address')
                    <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- SECTION 4: Social Media Links -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs p-5 sm:p-7 space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                <div class="w-8 h-8 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-share-nodes"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 font-roboto">Social Links</h2>
                    <p class="text-xs text-slate-500">Official social media profiles linked in header, footer, and emails.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Facebook -->
                <div class="space-y-2">
                    <label for="facebook_url" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Facebook URL
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-600">
                            <i class="fa-brands fa-facebook text-sm"></i>
                        </div>
                        <input 
                            type="url" 
                            id="facebook_url" 
                            name="facebook_url" 
                            value="{{ old('facebook_url', $setting->facebook_url) }}" 
                            placeholder="https://facebook.com/yourbrand" 
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 placeholder-slate-400 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('facebook_url') border-rose-500 @enderror"
                        >
                    </div>
                    @error('facebook_url')
                    <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Instagram -->
                <div class="space-y-2">
                    <label for="instagram_url" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Instagram URL
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-pink-600">
                            <i class="fa-brands fa-instagram text-sm"></i>
                        </div>
                        <input 
                            type="url" 
                            id="instagram_url" 
                            name="instagram_url" 
                            value="{{ old('instagram_url', $setting->instagram_url) }}" 
                            placeholder="https://instagram.com/yourbrand" 
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 placeholder-slate-400 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('instagram_url') border-rose-500 @enderror"
                        >
                    </div>
                    @error('instagram_url')
                    <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Twitter / X -->
                <div class="space-y-2">
                    <label for="twitter_url" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Twitter / X URL
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-800">
                            <i class="fa-brands fa-x-twitter text-sm"></i>
                        </div>
                        <input 
                            type="url" 
                            id="twitter_url" 
                            name="twitter_url" 
                            value="{{ old('twitter_url', $setting->twitter_url) }}" 
                            placeholder="https://x.com/yourbrand" 
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 placeholder-slate-400 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('twitter_url') border-rose-500 @enderror"
                        >
                    </div>
                    @error('twitter_url')
                    <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- LinkedIn -->
                <div class="space-y-2">
                    <label for="linkedin_url" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        LinkedIn URL
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-sky-700">
                            <i class="fa-brands fa-linkedin text-sm"></i>
                        </div>
                        <input 
                            type="url" 
                            id="linkedin_url" 
                            name="linkedin_url" 
                            value="{{ old('linkedin_url', $setting->linkedin_url) }}" 
                            placeholder="https://linkedin.com/company/yourbrand" 
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 placeholder-slate-400 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('linkedin_url') border-rose-500 @enderror"
                        >
                    </div>
                    @error('linkedin_url')
                    <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- YouTube -->
                <div class="space-y-2">
                    <label for="youtube_url" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        YouTube URL
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-red-600">
                            <i class="fa-brands fa-youtube text-sm"></i>
                        </div>
                        <input 
                            type="url" 
                            id="youtube_url" 
                            name="youtube_url" 
                            value="{{ old('youtube_url', $setting->youtube_url) }}" 
                            placeholder="https://youtube.com/@yourbrand" 
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 placeholder-slate-400 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('youtube_url') border-rose-500 @enderror"
                        >
                    </div>
                    @error('youtube_url')
                    <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- SECTION 5: App SEO Content -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs p-5 sm:p-7 space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                <div class="w-8 h-8 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-magnifying-glass-chart"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 font-roboto">App SEO Content</h2>
                    <p class="text-xs text-slate-500">Search engine optimization meta tags and search snippets.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Meta Title -->
                <div class="space-y-2 md:col-span-2">
                    <div class="flex items-center justify-between">
                        <label for="meta_title" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Meta Title
                        </label>
                        <span id="meta-title-counter" class="text-[11px] text-slate-400 font-medium">0 / 60 chars</span>
                    </div>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="meta_title" 
                            name="meta_title" 
                            value="{{ old('meta_title', $setting->meta_title) }}" 
                            placeholder="e.g. E-Book CMS - Read & Discover Digital Books" 
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 placeholder-slate-400 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('meta_title') border-rose-500 @enderror"
                            oninput="updateSeoPreview()"
                        >
                    </div>
                    @error('meta_title')
                    <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meta Keywords -->
                <div class="space-y-2 md:col-span-2">
                    <label for="meta_keywords" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Meta Keywords <span class="text-[11px] font-normal text-slate-400 lowercase">(comma separated)</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="meta_keywords" 
                            name="meta_keywords" 
                            value="{{ old('meta_keywords', $setting->meta_keywords) }}" 
                            placeholder="ebooks, reading, online library, pdf viewer" 
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 placeholder-slate-400 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium @error('meta_keywords') border-rose-500 @enderror"
                        >
                    </div>
                    @error('meta_keywords')
                    <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meta Description -->
                <div class="space-y-2 md:col-span-2">
                    <div class="flex items-center justify-between">
                        <label for="meta_description" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Meta Description
                        </label>
                        <span id="meta-desc-counter" class="text-[11px] text-slate-400 font-medium">0 / 160 chars</span>
                    </div>
                    <div class="relative">
                        <textarea 
                            id="meta_description" 
                            name="meta_description" 
                            rows="3" 
                            placeholder="Concise overview summarizing platform value proposition for search engines..." 
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-slate-800 placeholder-slate-400 text-xs sm:text-sm border border-slate-200 focus:border-brand-500 focus:outline-none transition font-medium resize-y @error('meta_description') border-rose-500 @enderror"
                            oninput="updateSeoPreview()"
                        >{{ old('meta_description', $setting->meta_description) }}</textarea>
                    </div>
                    @error('meta_description')
                    <p class="text-xs font-semibold text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        <!-- SECTION 6: Payment Gateways & Methods -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs p-5 sm:p-7 space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 font-roboto">Payment Gateways & Methods</h2>
                        <p class="text-xs text-slate-500">Enable or disable payment gateways. Credentials are loaded securely from environment (<code class="text-[11px] text-slate-700 font-mono bg-slate-100 px-1 py-0.5 rounded">.env</code>).</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Easebuzz Gateway Card -->
                <div class="p-5 rounded-2xl border border-slate-200/90 bg-slate-50/50 hover:bg-slate-50 transition flex flex-col justify-between space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-xs">
                                EB
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Easebuzz Payment Gateway</h3>
                                <p class="text-xs text-slate-500">UPI, Net Banking, Cards & Wallets</p>
                            </div>
                        </div>

                        <!-- Toggle Switch -->
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="easebuzz_enabled" value="1" class="sr-only peer" {{ old('easebuzz_enabled', $setting->easebuzz_enabled) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <div class="pt-3 border-t border-slate-200/60 flex items-center justify-between text-xs">
                        <span class="text-slate-500 flex items-center gap-1.5">
                            <i class="fa-solid fa-key text-[10px] text-slate-400"></i>
                            Environment Status:
                        </span>
                        @if(config('services.easebuzz.key') && config('services.easebuzz.salt'))
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <i class="fa-solid fa-circle-check text-[9px]"></i> Configured
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                <i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Key/Salt Missing in .env
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Razorpay Gateway Card -->
                <div class="p-5 rounded-2xl border border-slate-200/90 bg-slate-50/50 hover:bg-slate-50 transition flex flex-col justify-between space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-black text-xs">
                                RZ
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Razorpay Payment Gateway</h3>
                                <p class="text-xs text-slate-500">Fast UPI, Cards, International & Net Banking</p>
                            </div>
                        </div>

                        <!-- Toggle Switch -->
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="razorpay_enabled" value="1" class="sr-only peer" {{ old('razorpay_enabled', $setting->razorpay_enabled) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <div class="pt-3 border-t border-slate-200/60 flex items-center justify-between text-xs">
                        <span class="text-slate-500 flex items-center gap-1.5">
                            <i class="fa-solid fa-key text-[10px] text-slate-400"></i>
                            Environment Status:
                        </span>
                        @if(config('services.razorpay.key') && config('services.razorpay.secret'))
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <i class="fa-solid fa-circle-check text-[9px]"></i> Configured
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                <i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Key/Secret Missing in .env
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Actions Sticky Bar -->
        <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-200/80">
            <button 
                type="submit" 
                class="inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-sm font-semibold shadow-md shadow-brand-600/20 transition cursor-pointer"
            >
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Save All Settings</span>
            </button>
        </div>
    </form>

</div>

@push('scripts')
<script>
    function handleImagePreview(input, imgId, placeholderId, removeBtnId, removeFlagId) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.getElementById(imgId);
                const placeholder = document.getElementById(placeholderId);
                const removeBtn = document.getElementById(removeBtnId);
                const removeFlag = document.getElementById(removeFlagId);

                if (img) {
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    img.classList.add('block');
                }
                if (placeholder) {
                    placeholder.classList.add('hidden');
                    placeholder.classList.remove('flex', 'inline-block');
                }
                if (removeBtn) {
                    removeBtn.classList.remove('hidden');
                    removeBtn.classList.add('inline-flex');
                }
                if (removeFlag) {
                    removeFlag.value = '0';
                }
            };

            reader.readAsDataURL(file);
        }
    }

    function clearImagePreview(inputId, imgId, placeholderId, removeBtnId, removeFlagId) {
        const input = document.getElementById(inputId);
        const img = document.getElementById(imgId);
        const placeholder = document.getElementById(placeholderId);
        const removeBtn = document.getElementById(removeBtnId);
        const removeFlag = document.getElementById(removeFlagId);

        if (input) input.value = '';
        if (img) {
            img.src = '';
            img.classList.add('hidden');
            img.classList.remove('block');
        }
        if (placeholder) {
            placeholder.classList.remove('hidden');
            placeholder.classList.add('flex');
        }
        if (removeBtn) {
            removeBtn.classList.add('hidden');
            removeBtn.classList.remove('inline-flex');
        }
        if (removeFlag) {
            removeFlag.value = '1';
        }
    }

    function updateSeoPreview() {
        const titleInput = document.getElementById('meta_title');
        const descInput = document.getElementById('meta_description');
        const appNameInput = document.getElementById('app_name');

        const titleCounter = document.getElementById('meta-title-counter');
        const descCounter = document.getElementById('meta-desc-counter');

        const previewTitle = document.getElementById('seo-preview-title');
        const previewDesc = document.getElementById('seo-preview-desc');
        const faviconTabTitle = document.getElementById('favicon-tab-title');

        if (titleInput && titleCounter && previewTitle) {
            const length = titleInput.value.length;
            titleCounter.textContent = `${length} / 60 chars`;
            titleCounter.className = length > 60 ? 'text-[11px] text-amber-500 font-bold' : 'text-[11px] text-slate-400 font-medium';
            previewTitle.textContent = titleInput.value.trim() || (appNameInput ? appNameInput.value.trim() : 'E-Book CMS');
        }

        if (descInput && descCounter && previewDesc) {
            const length = descInput.value.length;
            descCounter.textContent = `${length} / 160 chars`;
            descCounter.className = length > 160 ? 'text-[11px] text-amber-500 font-bold' : 'text-[11px] text-slate-400 font-medium';
            previewDesc.textContent = descInput.value.trim() || 'Browse through curated collections of e-books, articles, and interactive publications on our platform.';
        }

        if (appNameInput && faviconTabTitle) {
            faviconTabTitle.textContent = appNameInput.value.trim() || 'E-Book CMS';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateSeoPreview();
        const appNameInput = document.getElementById('app_name');
        if (appNameInput) {
            appNameInput.addEventListener('input', updateSeoPreview);
        }
    });
</script>
@endpush

@endsection
