<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    /**
     * Display all customers
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')
            ->withCount('orders');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $active = $request->status == '1';
            if ($active) {
                $query->whereNull('deleted_at');
            } else {
                $query->onlyTrashed();
            }
        }

        $customers = $query->latest()->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show customer details
     */
    public function show(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $customer->load(['orders.orderItems']);

        $stats = [
            'total_orders' => $customer->orders()->count(),
            'total_spent' => $customer->orders()
                ->where('payment_status', 'paid')
                ->sum('total'),
            'pending_orders' => $customer->orders()
                ->where('status', 'pending')
                ->count(),
            'cancelled_orders' => $customer->orders()
                ->where('status', 'cancelled')
                ->count(),
        ];

        $recentOrders = $customer->orders()
            ->with('orderItems.product')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.customers.show', compact('customer', 'stats', 'recentOrders'));
    }

    /**
     * Deactivate customer
     */
    public function deactivate(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $customer->delete();

        return redirect()
            ->back()
            ->with('success', 'Customer deactivated successfully!');
    }

    /**
     * Reactivate customer
     */
    public function reactivate($id)
    {
        $customer = User::onlyTrashed()->findOrFail($id);

        if ($customer->role !== 'customer') {
            abort(404);
        }

        $customer->restore();

        return redirect()
            ->back()
            ->with('success', 'Customer reactivated successfully!');
    }
}
