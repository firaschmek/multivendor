<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorDashboardController extends Controller
{
    /**
     * Display vendor dashboard with statistics
     */
    public function index()
    {
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            return redirect()->route('home')->with('error', 'You are not registered as a vendor.');
        }

        // Check vendor status
        if ($vendor->status === 'pending') {
            return view('vendor.pending');
        }

        if ($vendor->status === 'suspended') {
            return view('vendor.suspended');
        }

        // Get order items that belong to this vendor's products
        $vendorOrderItems = \App\Models\OrderItem::whereHas('product', function($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        });

        // Get unique orders from vendor's order items
        $vendorOrders = \App\Models\Order::whereHas('orderItems.product', function($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        });

        // Get statistics
        $stats = [
            // Products
            'total_products' => $vendor->products()->count(),
            'active_products' => $vendor->products()->where('is_active', true)->count(),
            'out_of_stock' => $vendor->products()->where('quantity', 0)->count(),
            'low_stock' => $vendor->products()->where('quantity', '>', 0)->where('quantity', '<=', 5)->count(),

            // Orders (via order items)
            'total_orders' => (clone $vendorOrders)->count(),
            'pending_orders' => (clone $vendorOrders)->where('status', 'pending')->count(),
            'processing_orders' => (clone $vendorOrders)->where('status', 'processing')->count(),
            'completed_orders' => (clone $vendorOrders)->where('status', 'delivered')->count(),

            // Revenue (sum of vendor's order items, not full orders)
            'total_revenue' => (clone $vendorOrderItems)->whereHas('order', function($q) {
                $q->where('payment_status', 'paid');
            })->sum('subtotal'),
            'pending_revenue' => (clone $vendorOrderItems)->whereHas('order', function($q) {
                $q->where('payment_status', 'pending');
            })->sum('subtotal'),
            'current_balance' => $vendor->balance,
            'total_commission' => (clone $vendorOrderItems)->sum('commission_amount'),

            // Recent stats (last 30 days)
            'monthly_orders' => (clone $vendorOrders)
                ->where('created_at', '>=', now()->subDays(30))
                ->count(),
            'monthly_revenue' => (clone $vendorOrderItems)
                ->whereHas('order', function($q) {
                    $q->where('created_at', '>=', now()->subDays(30))
                        ->where('payment_status', 'paid');
                })->sum('subtotal'),

            // Reviews
            'total_reviews' => $vendor->products()->withCount('reviews')->get()->sum('reviews_count'),
            'average_rating' => 0, // TODO: Calculate from reviews table
        ];

        // Recent orders (that contain vendor's products)
        $recent_orders = \App\Models\Order::whereHas('orderItems.product', function($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })
            ->with(['user', 'orderItems' => function($q) use ($vendor) {
                $q->whereHas('product', function($pq) use ($vendor) {
                    $pq->where('vendor_id', $vendor->id);
                })->with('product');
            }])
            ->latest()
            ->take(5)
            ->get();

        // Low stock products
        $low_stock_products = $vendor->products()
            ->where('track_inventory', true)
            ->where('quantity', '>', 0)
            ->where('quantity', '<=', 5)
            ->orderBy('quantity', 'asc')
            ->take(5)
            ->get();

        // Top selling products
        $top_products = $vendor->products()
            ->orderBy('sales_count', 'desc')
            ->take(5)
            ->get();

        return view('vendor.dashboard', compact(
            'vendor',
            'stats',
            'recent_orders',
            'low_stock_products',
            'top_products'
        ));
    }

    /**
     * Show pending approval page
     */
    public function pending()
    {
        return view('vendor.pending');
    }

    /**
     * Show suspended account page
     */
    public function suspended()
    {
        return view('vendor.suspended');
    }
}
