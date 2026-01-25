<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\VendorTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function createFromCart(array $shippingData, string $paymentMethod = 'cash_on_delivery'): Order
    {
        return DB::transaction(function () use ($shippingData, $paymentMethod) {
            $cartItems = $this->cartService->getItems();

            if ($cartItems->isEmpty()) {
                throw new \Exception('Cart is empty');
            }

            // Validate stock
            $stockErrors = $this->cartService->validateStock();
            if (!empty($stockErrors)) {
                throw new \Exception('Stock validation failed: ' . implode(', ', $stockErrors));
            }

            // Calculate totals
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item->product->price * $item->quantity;
            }

            $tax = $this->calculateTax($subtotal);
            $shippingCost = $this->calculateShipping($cartItems);
            $total = $subtotal + $tax + $shippingCost;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'status' => 'pending',
                'shipping_name' => $shippingData['name'],
                'shipping_phone' => $shippingData['phone'],
                'shipping_email' => $shippingData['email'] ?? null,
                'shipping_address' => $shippingData['address'],
                'shipping_city' => $shippingData['city'],
                'shipping_state' => $shippingData['state'] ?? null,
                'shipping_postal_code' => $shippingData['postal_code'] ?? null,
                'shipping_country' => $shippingData['country'] ?? 'Tunisia',
                'customer_notes' => $shippingData['notes'] ?? null,
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                $vendor = $product->vendor;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'vendor_id' => $vendor->id,
                    'product_name' => $product->name,
                    'product_name_ar' => $product->name_ar,
                    'product_sku' => $product->sku,
                    'price' => $product->price,
                    'quantity' => $cartItem->quantity,
                    'commission_rate' => $vendor->commission_rate,
                    'status' => 'pending',
                ]);

                // Decrease product stock
                $product->decrementStock($cartItem->quantity);
                $product->increment('sales_count', $cartItem->quantity);
            }

            // Clear cart
            $this->cartService->clear();

            return $order;
        });
    }

    public function updateStatus(Order $order, string $status): void
    {
        $order->update(['status' => $status]);

        // Update timestamps based on status
        switch ($status) {
            case 'confirmed':
                $order->update(['confirmed_at' => now()]);
                break;
            case 'shipped':
                $order->update(['shipped_at' => now()]);
                break;
            case 'delivered':
                $order->update(['delivered_at' => now()]);
                $this->processPayment($order);
                break;
            case 'cancelled':
                $order->update(['cancelled_at' => now()]);
                $this->restoreStock($order);
                break;
        }
    }

    public function updatePaymentStatus(Order $order, string $paymentStatus): void
    {
        $order->update(['payment_status' => $paymentStatus]);

        if ($paymentStatus === 'paid') {
            $order->update(['paid_at' => now()]);
            $this->processCommissions($order);
        }
    }

    protected function processCommissions(Order $order): void
    {
        foreach ($order->items as $item) {
            // Create commission transaction for platform
            VendorTransaction::create([
                'vendor_id' => $item->vendor_id,
                'order_id' => $order->id,
                'order_item_id' => $item->id,
                'type' => 'commission',
                'amount' => -$item->commission_amount,
                'balance_after' => $item->vendor->balance - $item->commission_amount,
                'description' => "Commission for order {$order->order_number}",
                'status' => 'completed',
            ]);

            // Create sale transaction for vendor
            $vendor = $item->vendor;
            $vendor->increment('balance', $item->vendor_amount);

            VendorTransaction::create([
                'vendor_id' => $item->vendor_id,
                'order_id' => $order->id,
                'order_item_id' => $item->id,
                'type' => 'sale',
                'amount' => $item->vendor_amount,
                'balance_after' => $vendor->fresh()->balance,
                'description' => "Sale from order {$order->order_number}",
                'status' => 'completed',
            ]);
        }
    }

    protected function restoreStock(Order $order): void
    {
        foreach ($order->items as $item) {
            $item->product->incrementStock($item->quantity);
            $item->product->decrement('sales_count', $item->quantity);
        }
    }

    protected function calculateTax(float $subtotal): float
    {
        // Tunisia VAT is 19% for most items, 13% for some, 7% for others
        // For now, we'll use a flat 19%
        $taxRate = 0.19;
        return round($subtotal * $taxRate, 2);
    }

    protected function calculateShipping($cartItems): float
    {
        // Simple flat rate shipping
        // You can implement more complex logic based on weight, location, etc.
        return 7.00; // 7 TND flat rate for Tunisia
    }

    protected function processPayment(Order $order): void
    {
        // For cash on delivery, mark as paid when delivered
        if ($order->payment_method === 'cash_on_delivery' && $order->status === 'delivered') {
            $this->updatePaymentStatus($order, 'paid');
        }
    }
}
