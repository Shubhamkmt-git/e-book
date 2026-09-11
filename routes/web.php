<?php

use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminPermissionController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AppSettingController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CtaController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\HeroBannerController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\SpotlightController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Frontend\BookController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\CustomerAuthController;
use App\Http\Controllers\Frontend\CustomerProfileController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\LegalPageController;
use App\Http\Controllers\Frontend\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/ebooks', [BookController::class, 'index'])->name('books.index');
Route::get('/ebooks/{identifier}', [BookController::class, 'show'])->name('books.show');
Route::post('/ebooks/{identifier}/reviews', [BookController::class, 'storeReview'])->name('books.reviews.store');
Route::get('/ebooks/{identifier}/preview', [BookController::class, 'downloadPreview'])->name('books.preview');
Route::match(['get', 'post'], '/ebooks/{identifier}/payments/easebuzz', [PaymentController::class, 'initiate'])->name('payments.initiate');
Route::get('/payments/easebuzz/mock/{purchase}', [PaymentController::class, 'mockCheckout'])->name('payments.mock-checkout');
Route::post('/payments/easebuzz/mock/{purchase}/process', [PaymentController::class, 'processMockPayment'])->name('payments.mock-process');
Route::match(['get', 'post'], '/payments/easebuzz/return/{purchase}', [PaymentController::class, 'handleReturn'])->name('payments.return');
Route::post('/payments/easebuzz/webhook', [PaymentController::class, 'handleWebhook'])->name('payments.webhook');
Route::post('/payments/webhook', [PaymentController::class, 'handleWebhook'])->name('payments.webhook.generic');

// Razorpay Routes
Route::match(['get', 'post'], '/ebooks/{identifier}/payments/razorpay', [PaymentController::class, 'initiateRazorpay'])->name('payments.razorpay.initiate');
Route::get('/payments/razorpay/mock/{purchase}', [PaymentController::class, 'mockRazorpayCheckout'])->name('payments.razorpay.mock-checkout');
Route::post('/payments/razorpay/mock/{purchase}/process', [PaymentController::class, 'processRazorpayMockPayment'])->name('payments.razorpay.mock-process');
Route::match(['get', 'post'], '/payments/razorpay/callback/{purchase}', [PaymentController::class, 'handleRazorpayCallback'])->name('payments.razorpay.callback');
Route::post('/payments/razorpay/webhook', [PaymentController::class, 'handleRazorpayWebhook'])->name('payments.razorpay.webhook');

Route::get('/purchases/{purchase}/download', [PaymentController::class, 'downloadPurchasedEbook'])->name('purchases.download');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/privacy-policy', [LegalPageController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms', [LegalPageController::class, 'termsOfService'])->name('terms');
Route::get('/terms-of-service', [LegalPageController::class, 'termsOfService'])->name('terms-of-service');

Route::middleware('guest:customer')->group(function () {
    Route::post('/customer/register', [CustomerAuthController::class, 'register'])->name('customer.register');
    Route::post('/customer/login', [CustomerAuthController::class, 'login'])->name('customer.login');
    Route::get('/auth/google', [CustomerAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [CustomerAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

Route::post('/customer/logout', [CustomerAuthController::class, 'logout'])
    ->middleware('auth:customer')
    ->name('customer.logout');

Route::middleware('auth:customer')->group(function () {
    Route::get('/profile', [CustomerProfileController::class, 'index'])->name('customer.profile');
    Route::get('/profile/edit', [CustomerProfileController::class, 'edit'])->name('customer.profile.edit');
    Route::put('/profile', [CustomerProfileController::class, 'update'])->name('customer.profile.update');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->middleware('admin.permission:dashboard')
            ->name('dashboard');

        // User Manage: Admin Users
        Route::middleware('admin.permission:admin-user')->group(function () {
            Route::patch('admin-users/{admin_user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('admin-users.toggle-status');
            Route::resource('admin-users', AdminUserController::class);
        });

        // User Manage: Roles
        Route::middleware('admin.permission:role')->group(function () {
            Route::patch('admin-roles/{admin_role}/toggle-status', [AdminRoleController::class, 'toggleStatus'])->name('admin-roles.toggle-status');
            Route::resource('admin-roles', AdminRoleController::class);
        });

        // User Manage: Permissions
        Route::middleware('admin.permission:permission')->group(function () {
            Route::patch('admin-permissions/{admin_permission}/toggle-status', [AdminPermissionController::class, 'toggleStatus'])->name('admin-permissions.toggle-status');
            Route::resource('admin-permissions', AdminPermissionController::class);
        });

        // App Setting Management Routes
        Route::middleware('admin.permission:app-setting')->group(function () {
            Route::get('app-setting', [AppSettingController::class, 'index'])->name('app-setting.index');
            Route::put('app-setting', [AppSettingController::class, 'update'])->name('app-setting.update');
        });

        // Categories
        Route::middleware('admin.permission:category')->group(function () {
            Route::patch('categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
            Route::resource('categories', AdminCategoryController::class);
        });

        // Books / E-Books
        Route::middleware('admin.permission:ebook')->group(function () {
            Route::patch('books/{book}/toggle-status', [AdminBookController::class, 'toggleStatus'])->name('books.toggle-status');
            Route::resource('books', AdminBookController::class);
        });

        // Orders / Purchases
        Route::middleware('admin.permission:order')->group(function () {
            Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
            Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'destroy']);
        });

        // Customers
        Route::middleware('admin.permission:customer')->group(function () {
            Route::resource('customers', AdminCustomerController::class);
        });

        // Hero Banners
        Route::middleware('admin.permission:hero-banner')->group(function () {
            Route::patch('hero-banners/{hero_banner}/toggle-status', [HeroBannerController::class, 'toggleStatus'])->name('hero-banners.toggle-status');
            Route::resource('hero-banners', HeroBannerController::class);
        });

        // FAQs
        Route::middleware('admin.permission:faq')->group(function () {
            Route::patch('faqs/{faq}/toggle-status', [FaqController::class, 'toggleStatus'])->name('faqs.toggle-status');
            Route::resource('faqs', FaqController::class)->except(['show']);
        });

        // Testimonials
        Route::middleware('admin.permission:testimonial')->group(function () {
            Route::patch('testimonials/{testimonial}/toggle-status', [TestimonialController::class, 'toggleStatus'])->name('testimonials.toggle-status');
            Route::resource('testimonials', TestimonialController::class);
        });

        // Book of the Week / Spotlight
        Route::middleware('admin.permission:spotlight')->group(function () {
            Route::get('spotlight', [SpotlightController::class, 'manage'])->name('spotlight.manage');
            Route::put('spotlight', [SpotlightController::class, 'update'])->name('spotlight.update');
            Route::patch('spotlight/toggle-status', [SpotlightController::class, 'toggleStatus'])->name('spotlight.toggle-status');
        });

        // CTAs
        Route::middleware('admin.permission:cta')->group(function () {
            Route::get('ctas', [CtaController::class, 'manage'])->name('ctas.manage');
            Route::put('ctas', [CtaController::class, 'update'])->name('ctas.update');
        });

        // Legal Pages Management (Privacy Policy & Terms of Service)
        Route::middleware('admin.permission:legal-page')->group(function () {
            Route::get('legal-pages', [App\Http\Controllers\Admin\LegalPageController::class, 'index'])->name('legal-pages.index');
            Route::get('legal-pages/{slug}/edit', [App\Http\Controllers\Admin\LegalPageController::class, 'edit'])->name('legal-pages.edit');
            Route::put('legal-pages/{slug}', [App\Http\Controllers\Admin\LegalPageController::class, 'update'])->name('legal-pages.update');
            Route::patch('legal-pages/{slug}/toggle-status', [App\Http\Controllers\Admin\LegalPageController::class, 'toggleStatus'])->name('legal-pages.toggle-status');
        });
    });
});
