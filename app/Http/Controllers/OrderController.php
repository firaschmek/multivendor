<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // DELETE the __construct() method completely!

    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::where('user_id', auth()->id())
            ->with(['items.product.primaryImage', 'items.vendor'])
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    public function cancel($id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        if (!$order->canBeCancelled()) {
            return redirect()->back()
                ->with('error', 'لا يمكن إلغاء هذا الطلب');
        }

        $order->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);

        // Restore stock
        foreach ($order->items as $item) {
            $item->product->incrementStock($item->quantity);
        }

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'تم إلغاء الطلب بنجاح');
    }
}
