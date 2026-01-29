<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    protected function getSessionId(): string
    {
        if (!session()->has('cart_session_id')) {
            session()->put('cart_session_id', uniqid('cart_', true));
        }
        return session()->get('cart_session_id');
    }

    public function index()
    {
        $cartItems = $this->cartService->getItems();
        $cartTotal = $this->cartService->getTotal();
        $cartCount = $this->cartService->getCount();

        return view('cart.index', compact('cartItems', 'cartTotal', 'cartCount'));
    }

    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $product = Product::findOrFail($validated['product_id']);

            // Check stock
            if ($product->quantity < $validated['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكمية المطلوبة غير متوفرة في المخزون'
                ], 400);
            }

            // Add to cart
            if (auth()->check()) {
                // Logged in user - save to database
                $existingItem = CartItem::where('user_id', auth()->id())
                    ->where('product_id', $product->id)
                    ->first();

                if ($existingItem) {
                    $existingItem->increment('quantity', $validated['quantity']);
                } else {
                    CartItem::create([
                        'user_id' => auth()->id(),
                        'product_id' => $product->id,
                        'quantity' => $validated['quantity']
                    ]);
                }
            } else {
                // Guest - save to database with session_id
                $sessionId = $this->getSessionId();

                $existingItem = CartItem::where('session_id', $sessionId)
                    ->where('product_id', $product->id)
                    ->first();

                if ($existingItem) {
                    $existingItem->increment('quantity', $validated['quantity']);
                } else {
                    CartItem::create([
                        'session_id' => $sessionId,
                        'product_id' => $product->id,
                        'quantity' => $validated['quantity']
                    ]);
                }
            }

            // Get cart count
            $cartCount = auth()->check()
                ? CartItem::where('user_id', auth()->id())->sum('quantity')
                : CartItem::where('session_id', $this->getSessionId())->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'تمت الإضافة إلى السلة بنجاح',
                'cart_count' => $cartCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الإضافة إلى السلة: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        $result = $this->cartService->update($id, $request->quantity);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'cart_count' => $this->cartService->getCount(),
            'cart_total' => $this->cartService->getTotal()
        ]);
    }

    public function remove($id)
    {
        $result = $this->cartService->remove($id);

        return redirect()->route('cart.index')
            ->with('success', $result['message']);
    }

    public function clear()
    {
        $this->cartService->clear();

        return redirect()->route('cart.index')
            ->with('success', 'تم تفريغ السلة بنجاح');
    }
}
