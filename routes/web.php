<?php

use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminPermissionController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AppSettingController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HeroBannerController;
use App\Http\Controllers\Frontend\BookController;
use App\Http\Controllers\Frontend\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/ebooks', [BookController::class, 'index'])->name('books.index');
Route::get('/ebooks/{identifier}', [BookController::class, 'show'])->name('books.show');
Route::get('/ebooks/{identifier}/preview', [BookController::class, 'downloadPreview'])->name('books.preview');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::patch('admin-users/{admin_user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('admin-users.toggle-status');
        Route::patch('admin-roles/{admin_role}/toggle-status', [AdminRoleController::class, 'toggleStatus'])->name('admin-roles.toggle-status');
        Route::patch('admin-permissions/{admin_permission}/toggle-status', [AdminPermissionController::class, 'toggleStatus'])->name('admin-permissions.toggle-status');
        Route::patch('categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
        Route::patch('books/{book}/toggle-status', [AdminBookController::class, 'toggleStatus'])->name('books.toggle-status');
        Route::resource('admin-users', AdminUserController::class);
        Route::resource('admin-roles', AdminRoleController::class);
        Route::resource('admin-permissions', AdminPermissionController::class);
        Route::resource('categories', AdminCategoryController::class);
        Route::resource('books', AdminBookController::class);
        Route::resource('customers', AdminCustomerController::class);
        Route::patch('hero-banners/{hero_banner}/toggle-status', [HeroBannerController::class, 'toggleStatus'])->name('hero-banners.toggle-status');
        Route::resource('hero-banners', HeroBannerController::class);

        // App Setting Management Routes
        Route::get('app-setting', [AppSettingController::class, 'index'])->name('app-setting.index');
        Route::put('app-setting', [AppSettingController::class, 'update'])->name('app-setting.update');
    });
});
