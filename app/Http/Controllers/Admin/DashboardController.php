<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Customer;
use App\Models\HeroBanner;
use App\Models\Purchase;
use App\Models\User;
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
        $purchasedCustomersCount = Customer::has('purchases')->count();

        $booksCount = Book::count();
        $activeBooksCount = Book::where('status', 'active')->count();
        $featuredBooksCount = Book::where('is_featured', true)->count();

        $categoriesCount = Category::count();
        $activeCategoriesCount = Category::where('status', 'active')->count();

        $ordersCount = Purchase::count();
        $paidOrdersCount = Purchase::where('status', 'paid')->count();
        $pendingOrdersCount = Purchase::where('status', 'pending')->count();
        $failedOrdersCount = Purchase::whereIn('status', ['failed', 'canceled', 'cancelled'])->count();

        $totalRevenue = (float) Purchase::where('status', 'paid')->sum('amount');
        $todayRevenue = (float) Purchase::where('status', 'paid')->whereDate('created_at', today())->sum('amount');
        $todayOrders = Purchase::whereDate('created_at', today())->count();

        $avgOrderValue = $paidOrdersCount > 0 ? ($totalRevenue / $paidOrdersCount) : 0;
        $conversionRate = $ordersCount > 0 ? round(($paidOrdersCount / $ordersCount) * 100, 1) : 0;

        $heroBannersCount = HeroBanner::where('is_active', true)->count();
        $recentOrders = Purchase::with('customer')->latest()->take(6)->get();

        // 7-Day Daily Revenue & Orders Trend
        $chartLabels = [];
        $chartRevenue = [];
        $chartOrders = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayLabel = now()->subDays($i)->format('D, M j');
            $chartLabels[] = $dayLabel;

            $dayRevenue = (float) Purchase::where('status', 'paid')
                ->whereDate('created_at', $date)
                ->sum('amount');
            $dayOrders = Purchase::whereDate('created_at', $date)->count();

            $chartRevenue[] = $dayRevenue;
            $chartOrders[] = $dayOrders;
        }

        // Order Status Distribution
        $orderStatusDistribution = [
            'paid' => $paidOrdersCount,
            'pending' => $pendingOrdersCount,
            'failed' => $failedOrdersCount,
        ];

        // Top categories by book count
        $topCategories = Category::withCount('books')
            ->orderByDesc('books_count')
            ->take(5)
            ->get();

        $categoryChartLabels = $topCategories->pluck('title')->toArray();
        $categoryChartCounts = $topCategories->pluck('books_count')->toArray();

        return view('admin.dashboard', [
            'usersCount' => $usersCount,
            'customersCount' => $customersCount,
            'purchasedCustomersCount' => $purchasedCustomersCount,
            'booksCount' => $booksCount,
            'activeBooksCount' => $activeBooksCount,
            'featuredBooksCount' => $featuredBooksCount,
            'categoriesCount' => $categoriesCount,
            'activeCategoriesCount' => $activeCategoriesCount,
            'ordersCount' => $ordersCount,
            'paidOrdersCount' => $paidOrdersCount,
            'pendingOrdersCount' => $pendingOrdersCount,
            'failedOrdersCount' => $failedOrdersCount,
            'totalRevenue' => $totalRevenue,
            'todayRevenue' => $todayRevenue,
            'todayOrders' => $todayOrders,
            'avgOrderValue' => $avgOrderValue,
            'conversionRate' => $conversionRate,
            'heroBannersCount' => $heroBannersCount,
            'recentOrders' => $recentOrders,
            'chartLabels' => $chartLabels,
            'chartRevenue' => $chartRevenue,
            'chartOrders' => $chartOrders,
            'orderStatusDistribution' => $orderStatusDistribution,
            'categoryChartLabels' => $categoryChartLabels,
            'categoryChartCounts' => $categoryChartCounts,
            'topCategories' => $topCategories,
        ]);
    }
}
