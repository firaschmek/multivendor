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
                $cartItem = CartItem::updateOrCreate(
                    [
                        'user_id' => auth()->id(),
                        'product_id' => $product->id
                    ],
                    [
                        'quantity' => \DB::raw('quantity + ' . $validated['quantity'])
                    ]
                );
            } else {
                // Guest - save to session
                $cart = session()->get('cart', []);

                if (isset($cart[$product->id])) {
                    $cart[$product->id]['quantity'] += $validated['quantity'];
                } else {
                    $cart[$product->id] = [
                        'name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $validated['quantity'],
                        'image' => $product->primaryImage ? $product->primaryImage->image_path : null
                    ];
                }

                session()->put('cart', $cart);
            }

            // Get cart count
            $cartCount = auth()->check()
                ? CartItem::where('user_id', auth()->id())->sum('quantity')
                : array_sum(array_column(session()->get('cart', []), 'quantity'));

            return response()->json([
                'success' => true,
                'message' => 'تمت الإضافة إلى السلة بنجاح',
                'cartCount' => $cartCount
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
