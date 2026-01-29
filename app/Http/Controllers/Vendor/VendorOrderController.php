<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorOrderController extends Controller
{
    /**
     * Display vendor's orders
     */
    public function index(Request $request)
    {
        $vendor = Auth::user()->vendor;

        // Get orders that contain vendor's products
        $query = \App\Models\Order::whereHas('orderItems.product', function($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })->with(['user', 'orderItems' => function($q) use ($vendor) {
            $q->whereHas('product', function($pq) use ($vendor) {
                $pq->where('vendor_id', $vendor->id);
            })->with('product');
        }]);

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

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(15);

        // Stats for filters
        $baseQuery = \App\Models\Order::whereHas('orderItems.product', function($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        });

        $stats = [
            'all' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'confirmed' => (clone $baseQuery)->where('status', 'confirmed')->count(),
            'processing' => (clone $baseQuery)->where('status', 'processing')->count(),
            'shipped' => (clone $baseQuery)->where('status', 'shipped')->count(),
            'delivered' => (clone $baseQuery)->where('status', 'delivered')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
        ];

        return view('vendor.orders.index', compact('orders', 'stats'));
    }

    /**
     * Show order details
     */
    public function show(Order $order)
    {
        // Verify that this order belongs to vendor's products
        $hasVendorProducts = $order->orderItems()
            ->whereHas('product', function($q) {
                $q->where('vendor_id', Auth::user()->vendor->id);
            })->exists();

        if (!$hasVendorProducts) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load([
            'user',
            'orderItems' => function($q) {
                $q->whereHas('product', function($pq) {
                    $pq->where('vendor_id', Auth::user()->vendor->id);
                })->with('product.images');
            }
        ]);

        // Calculate vendor's portion
        $vendorTotal = $order->orderItems->sum(function($item) {
            return $item->subtotal - $item->commission_amount;
        });

        return view('vendor.orders.show', compact('order', 'vendorTotal'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Verify ownership
        $hasVendorProducts = $order->orderItems()
            ->whereHas('product', function($q) {
                $q->where('vendor_id', Auth::user()->vendor->id);
            })->exists();

        if (!$hasVendorProducts) {
            abort(403, 'Unauthorized access to this order.');
        }

        $request->validate([
            'status' => 'required|in:confirmed,processing,shipped,delivered',
        ]);

        // Vendors can only update certain statuses
        $allowedTransitions = [
            'pending' => ['confirmed'],
            'confirmed' => ['processing'],
            'processing' => ['shipped'],
            'shipped' => ['delivered'],
        ];

        if (!in_array($request->status, $allowedTransitions[$order->status] ?? [])) {
            return redirect()
                ->back()
                ->with('error', 'Invalid status transition.');
        }

        // Build update data with safe timestamp columns
        $updateData = ['status' => $request->status];

        // Map status to timestamp column (whitelist approach)
        $timestampColumns = [
            'confirmed' => 'confirmed_at',
            'processing' => 'processing_at',
            'shipped' => 'shipped_at',
            'delivered' => 'delivered_at',
        ];

        if (isset($timestampColumns[$request->status])) {
            $updateData[$timestampColumns[$request->status]] = now();
        }

        $order->update($updateData);

        // If status is confirmed, mark payment as paid for COD
        if ($request->status === 'confirmed' && $order->payment_method === 'cod') {
            $order->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'تم تحديث حالة الطلب بنجاح!');
    }

    /**
     * Update tracking number
     */
    public function updateTracking(Request $request, Order $order)
    {
        // Verify ownership
        $hasVendorProducts = $order->orderItems()
            ->whereHas('product', function($q) {
                $q->where('vendor_id', Auth::user()->vendor->id);
            })->exists();

        if (!$hasVendorProducts) {
            abort(403, 'Unauthorized access to this order.');
        }

        $request->validate([
            'tracking_number' => 'required|string|max:255',
        ]);

        $order->update([
            'tracking_number' => $request->tracking_number,
        ]);

        return redirect()
            ->back()
            ->with('success', 'تم تحديث رقم التتبع بنجاح!');
    }

    /**
     * Print order invoice
     */
    public function printInvoice(Order $order)
    {
        // Verify ownership
        $hasVendorProducts = $order->orderItems()
            ->whereHas('product', function($q) {
                $q->where('vendor_id', Auth::user()->vendor->id);
            })->exists();

        if (!$hasVendorProducts) {
            abort(403, 'Unauthorized access to this order.');
        }

        $vendor = Auth::user()->vendor;

        $order->load([
            'user',
            'orderItems' => function($q) {
                $q->whereHas('product', function($pq) {
                    $pq->where('vendor_id', Auth::user()->vendor->id);
                })->with('product');
            }
        ]);

        $vendorTotal = $order->orderItems->sum('subtotal');
        $vendorCommission = $order->orderItems->sum('commission_amount');
        $vendorEarnings = $vendorTotal - $vendorCommission;

        return view('vendor.orders.invoice', compact(
            'order',
            'vendor',
            'vendorTotal',
            'vendorCommission',
            'vendorEarnings'
        ));
    }
}
