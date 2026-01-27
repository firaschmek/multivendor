<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Display all orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product.vendor']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by vendor
        if ($request->has('vendor') && $request->vendor) {
            $query->whereHas('orderItems.product', function($q) use ($request) {
                $q->where('vendor_id', $request->vendor);
            });
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20);
        $vendors = Vendor::where('status', 'approved')->get();

        // Stats
        $stats = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'vendors', 'stats'));
    }

    /**
     * Show order details
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product.vendor.user']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        $order->update([
            'status' => $request->status,
            $request->status . '_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Order status updated successfully!');
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        $order->update([
            'payment_status' => $request->payment_status,
            'paid_at' => $request->payment_status === 'paid' ? now() : null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Payment status updated successfully!');
    }

    /**
     * Cancel order
     */
    public function cancel(Order $order)
    {
        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return redirect()
                ->back()
                ->with('error', 'Cannot cancel order in current status.');
        }

        $order->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Order cancelled successfully!');
    }

    /**
     * Print invoice
     */
    public function printInvoice(Order $order)
    {
        $order->load(['user', 'orderItems.product.vendor']);

        return view('admin.orders.invoice', compact('order'));
    }
}
