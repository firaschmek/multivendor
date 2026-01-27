<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    /**
     * Display all products
     */
    public function index(Request $request)
    {
        $query = Product::with(['vendor', 'category', 'images']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_ar', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by vendor
        if ($request->has('vendor') && $request->vendor) {
            $query->where('vendor_id', $request->vendor);
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        // Filter by stock
        if ($request->has('stock')) {
            if ($request->stock === 'out') {
                $query->where('quantity', 0);
            } elseif ($request->stock === 'low') {
                $query->where('quantity', '>', 0)->where('quantity', '<=', 5);
            } elseif ($request->stock === 'in') {
                $query->where('quantity', '>', 5);
            }
        }

        $products = $query->latest()->paginate(20);
        $vendors = Vendor::where('status', 'approved')->get();
        $categories = Category::whereNull('parent_id')->get();

        return view('admin.products.index', compact('products', 'vendors', 'categories'));
    }

    /**
     * Show product details
     */
    public function show(Product $product)
    {
        $product->load(['vendor.user', 'category', 'images', 'reviews.user']);

        $stats = [
            'total_sales' => $product->orderItems()->count(),
            'total_revenue' => $product->orderItems()->sum('subtotal'),
            'total_reviews' => $product->reviews()->count(),
            'average_rating' => $product->reviews()->avg('rating'),
            'views' => $product->views_count ?? 0,
        ];

        return view('admin.products.show', compact('product', 'stats'));
    }

    /**
     * Show edit form
     */
    public function edit(Product $product)
    {
        $product->load('images');
        $vendors = Vendor::where('status', 'approved')->get();
        $categories = Category::with('children')->whereNull('parent_id')->get();

        return view('admin.products.edit', compact('product', 'vendors', 'categories'));
    }

    /**
     * Update product
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $product->update([
            'vendor_id' => $request->vendor_id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'description' => $request->description,
            'description_ar' => $request->description_ar,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'quantity' => $request->quantity,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.products.show', $product)
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Toggle product active status
     */
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        return redirect()
            ->back()
            ->with('success', 'Product status updated!');
    }

    /**
     * Delete product
     */
    public function destroy(Product $product)
    {
        // Check if product has orders
        if ($product->orderItems()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete product with existing orders.');
        }

        // Delete images
        foreach ($product->images as $image) {
            \Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
        ]);

        $products = Product::whereIn('id', $request->products);

        switch ($request->action) {
            case 'activate':
                $products->update(['is_active' => true]);
                $message = 'Products activated successfully!';
                break;
            case 'deactivate':
                $products->update(['is_active' => false]);
                $message = 'Products deactivated successfully!';
                break;
            case 'delete':
                // Check if any has orders
                if ($products->whereHas('orderItems')->exists()) {
                    return redirect()
                        ->back()
                        ->with('error', 'Cannot delete products with existing orders.');
                }
                $products->delete();
                $message = 'Products deleted successfully!';
                break;
        }

        return redirect()
            ->back()
            ->with('success', $message);
    }
}
