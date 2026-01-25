<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    public function index()
    {
        $cartItems = $this->cartService->getItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'السلة فارغة');
        }

        // Validate stock
        $stockErrors = $this->cartService->validateStock();
        if (!empty($stockErrors)) {
            return redirect()->route('cart.index')
                ->with('error', 'بعض المنتجات غير متوفرة: ' . implode(', ', $stockErrors));
        }

        $cartTotal = $this->cartService->getTotal();
        $user = auth()->user();

        return view('checkout.index', compact('cartItems', 'cartTotal', 'user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_email' => 'nullable|email',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'nullable|string|max:100',
            'shipping_postal_code' => 'nullable|string|max:20',
            'payment_method' => 'required|in:cash_on_delivery,credit_card,bank_transfer',
            'customer_notes' => 'nullable|string|max:500',
        ]);

        try {
            $shippingData = [
                'name' => $validated['shipping_name'],
                'phone' => $validated['shipping_phone'],
                'email' => $validated['shipping_email'] ?? null,
                'address' => $validated['shipping_address'],
                'city' => $validated['shipping_city'],
                'state' => $validated['shipping_state'] ?? null,
                'postal_code' => $validated['shipping_postal_code'] ?? null,
                'notes' => $validated['customer_notes'] ?? null,
            ];

            $order = $this->orderService->createFromCart($shippingData, $validated['payment_method']);

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'تم إنشاء الطلب بنجاح! رقم الطلب: ' . $order->order_number);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }
}
