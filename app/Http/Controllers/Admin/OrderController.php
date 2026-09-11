<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of all customer orders/purchases.
     */
    public function index(Request $request): View
    {
        $query = Purchase::with('customer');

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                    ->orWhere('book_title', 'like', "%{$search}%")
                    ->orWhere('book_identifier', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('mobile', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        $totalCount = Purchase::count();
        $paidCount = Purchase::where('status', 'paid')->count();
        $pendingCount = Purchase::where('status', 'pending')->count();
        $failedCount = Purchase::where('status', 'failed')->count();
        $totalRevenue = (float) Purchase::where('status', 'paid')->sum('amount');

        return view('admin.orders.index', compact(
            'orders',
            'totalCount',
            'paidCount',
            'pendingCount',
            'failedCount',
            'totalRevenue'
        ));
    }

    /**
     * Display the specified order details.
     */
    public function show(Purchase $order): View
    {
        $order->load('customer');
        $book = $order->book;

        return view('admin.orders.show', compact('order', 'book'));
    }

    /**
     * Update the status of an existing order.
     */
    public function updateStatus(Request $request, Purchase $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:paid,pending,failed,refunded,cancelled'],
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        return redirect()->back()
            ->with('success', "Order #{$order->transaction_id} status updated to '".ucfirst($validated['status'])."'.");
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Purchase $order): RedirectResponse
    {
        $txnid = $order->transaction_id;
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', "Order #{$txnid} deleted successfully.");
    }
}
