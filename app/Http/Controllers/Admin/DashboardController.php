<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        $usersCount = User::count();
        $customersCount = Customer::count();
        $booksCount = Book::count();
        $ordersCount = Purchase::count();
        $paidOrdersCount = Purchase::where('status', 'paid')->count();
        $totalRevenue = (float) Purchase::where('status', 'paid')->sum('amount');
        $recentOrders = Purchase::with('customer')->latest()->take(5)->get();

        $dbConnected = false;

        try {
            DB::connection()->getPdo();
            $dbConnected = true;
        } catch (\Throwable $e) {
            $dbConnected = false;
        }

        return view('admin.dashboard', [
            'usersCount' => $usersCount,
            'customersCount' => $customersCount,
            'booksCount' => $booksCount,
            'ordersCount' => $ordersCount,
            'paidOrdersCount' => $paidOrdersCount,
            'totalRevenue' => $totalRevenue,
            'recentOrders' => $recentOrders,
            'dbConnected' => $dbConnected,
            'phpVersion' => PHP_VERSION,
            'laravelVersion' => app()->version(),
        ]);
    }
}
