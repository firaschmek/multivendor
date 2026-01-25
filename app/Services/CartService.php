<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected function getSessionId(): string
    {
        if (!Session::has('cart_session_id')) {
            Session::put('cart_session_id', uniqid('cart_', true));
        }
        return Session::get('cart_session_id');
    }

    protected function getCartQuery()
    {
        if (Auth::check()) {
            return CartItem::where('user_id', Auth::id());
        }
        return CartItem::where('session_id', $this->getSessionId());
    }

    public function getItems()
    {
        return $this->getCartQuery()->with(['product.vendor', 'product.primaryImage'])->get();
    }

    public function getCount(): int
    {
        return $this->getCartQuery()->sum('quantity');
    }

    public function getTotal(): float
    {
        $items = $this->getItems();
        return $items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }

    public function add(Product $product, int $quantity = 1): array
    {
        if (!$product->isInStock()) {
            return ['success' => false, 'message' => 'Product is out of stock'];
        }

        if ($product->track_inventory && $product->quantity < $quantity) {
            return ['success' => false, 'message' => 'Insufficient stock'];
        }

        $cartItem = $this->getCartQuery()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            
            if ($product->track_inventory && $newQuantity > $product->quantity) {
                return ['success' => false, 'message' => 'Cannot add more than available stock'];
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $data = [
                'product_id' => $product->id,
                'quantity' => $quantity,
            ];

            if (Auth::check()) {
                $data['user_id'] = Auth::id();
            } else {
                $data['session_id'] = $this->getSessionId();
            }

            CartItem::create($data);
        }

        return ['success' => true, 'message' => 'Product added to cart'];
    }

    public function update(int $cartItemId, int $quantity): array
    {
        $cartItem = $this->getCartQuery()->findOrFail($cartItemId);
        
        if ($quantity <= 0) {
            return $this->remove($cartItemId);
        }

        if ($cartItem->product->track_inventory && $quantity > $cartItem->product->quantity) {
            return ['success' => false, 'message' => 'Insufficient stock'];
        }

        $cartItem->update(['quantity' => $quantity]);
        
        return ['success' => true, 'message' => 'Cart updated'];
    }

    public function remove(int $cartItemId): array
    {
        $cartItem = $this->getCartQuery()->findOrFail($cartItemId);
        $cartItem->delete();
        
        return ['success' => true, 'message' => 'Item removed from cart'];
    }

    public function clear(): void
    {
        $this->getCartQuery()->delete();
    }

    public function mergeGuestCart(): void
    {
        if (!Auth::check()) {
            return;
        }

        $guestItems = CartItem::where('session_id', $this->getSessionId())->get();

        foreach ($guestItems as $guestItem) {
            $userItem = CartItem::where('user_id', Auth::id())
                                ->where('product_id', $guestItem->product_id)
                                ->first();

            if ($userItem) {
                $newQuantity = $userItem->quantity + $guestItem->quantity;
                if (!$guestItem->product->track_inventory || 
                    $newQuantity <= $guestItem->product->quantity) {
                    $userItem->update(['quantity' => $newQuantity]);
                }
                $guestItem->delete();
            } else {
                $guestItem->update([
                    'user_id' => Auth::id(),
                    'session_id' => null,
                ]);
            }
        }
    }

    public function validateStock(): array
    {
        $items = $this->getItems();
        $errors = [];

        foreach ($items as $item) {
            if (!$item->product->isInStock()) {
                $errors[] = "{$item->product->name} is out of stock";
            } elseif ($item->product->track_inventory && $item->quantity > $item->product->quantity) {
                $errors[] = "Only {$item->product->quantity} units of {$item->product->name} available";
            }
        }

        return $errors;
    }

    public function groupByVendor()
    {
        return $this->getItems()->groupBy('product.vendor_id');
    }
}
