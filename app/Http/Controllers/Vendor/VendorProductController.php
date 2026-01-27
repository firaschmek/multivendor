<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VendorProductController extends Controller
{
    /**
     * Display vendor's products
     */
    public function index(Request $request)
    {
        $vendor = Auth::user()->vendor;
        
        $query = $vendor->products()->with('category', 'images');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('name_ar', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
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

        $products = $query->latest()->paginate(12);
        $categories = Category::whereNull('parent_id')->get();

        return view('vendor.products.index', compact('products', 'categories'));
    }

    /**
     * Show create product form
     */
    public function create()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return view('vendor.products.create', compact('categories'));
    }

    /**
     * Store new product
     */
    public function store(Request $request)
    {
        $vendor = Auth::user()->vendor;

        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'required|string',
            'description_ar' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0|gt:price',
            'cost' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'quantity' => 'required|integer|min:0',
            'track_inventory' => 'boolean',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Create product
            $product = Product::create([
                'vendor_id' => $vendor->id,
                'category_id' => $request->category_id,
                'name' => $request->name,
                'name_ar' => $request->name_ar,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'description_ar' => $request->description_ar,
                'price' => $request->price,
                'compare_price' => $request->compare_price,
                'cost' => $request->cost,
                'sku' => $request->sku ?? $this->generateSKU(),
                'quantity' => $request->quantity,
                'track_inventory' => $request->has('track_inventory'),
                'low_stock_threshold' => $request->low_stock_threshold ?? 5,
                'is_active' => true,
            ]);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $index === 0,
                        'sort_order' => $index,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('vendor.products.show', $product)
                ->with('success', 'Product created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    /**
     * Show product details
     */
    public function show(Product $product)
    {
        // Verify ownership
        if ($product->vendor_id !== Auth::user()->vendor->id) {
            abort(403, 'Unauthorized access to this product.');
        }

        $product->load(['category', 'images', 'reviews.user']);

        $stats = [
            'total_sales' => $product->sales_count,
            'total_revenue' => $product->orderItems()->sum('subtotal'),
            'total_reviews' => $product->reviews()->count(),
            'average_rating' => $product->rating,
            'views' => $product->views_count,
        ];

        return view('vendor.products.show', compact('product', 'stats'));
    }

    /**
     * Show edit form
     */
    public function edit(Product $product)
    {
        // Verify ownership
        if ($product->vendor_id !== Auth::user()->vendor->id) {
            abort(403, 'Unauthorized access to this product.');
        }

        $product->load('images');
        $categories = Category::with('children')->whereNull('parent_id')->get();

        return view('vendor.products.edit', compact('product', 'categories'));
    }

    /**
     * Update product
     */
    public function update(Request $request, Product $product)
    {
        // Verify ownership
        if ($product->vendor_id !== Auth::user()->vendor->id) {
            abort(403, 'Unauthorized access to this product.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'required|string',
            'description_ar' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0|gt:price',
            'cost' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'quantity' => 'required|integer|min:0',
            'track_inventory' => 'boolean',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Update product
            $product->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'name_ar' => $request->name_ar,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'description_ar' => $request->description_ar,
                'price' => $request->price,
                'compare_price' => $request->compare_price,
                'cost' => $request->cost,
                'sku' => $request->sku,
                'quantity' => $request->quantity,
                'track_inventory' => $request->has('track_inventory'),
                'low_stock_threshold' => $request->low_stock_threshold ?? 5,
            ]);

            // Handle new image uploads
            if ($request->hasFile('images')) {
                $lastOrder = $product->images()->max('sort_order') ?? -1;
                
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => false,
                        'sort_order' => $lastOrder + $index + 1,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('vendor.products.show', $product)
                ->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    /**
     * Delete product image
     */
    public function deleteImage(ProductImage $image)
    {
        // Verify ownership
        if ($image->product->vendor_id !== Auth::user()->vendor->id) {
            abort(403, 'Unauthorized access.');
        }

        // Delete file from storage
        Storage::disk('public')->delete($image->image_path);
        if ($image->thumbnail_path) {
            Storage::disk('public')->delete($image->thumbnail_path);
        }

        $image->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Toggle product active status
     */
    public function toggleStatus(Product $product)
    {
        // Verify ownership
        if ($product->vendor_id !== Auth::user()->vendor->id) {
            abort(403, 'Unauthorized access to this product.');
        }

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
        // Verify ownership
        if ($product->vendor_id !== Auth::user()->vendor->id) {
            abort(403, 'Unauthorized access to this product.');
        }

        try {
            DB::beginTransaction();

            // Check if product has orders
            if ($product->orderItems()->count() > 0) {
                return redirect()
                    ->back()
                    ->with('error', 'Cannot delete product with existing orders. Consider deactivating it instead.');
            }

            // Delete images
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                if ($image->thumbnail_path) {
                    Storage::disk('public')->delete($image->thumbnail_path);
                }
                $image->delete();
            }

            // Delete product
            $product->delete();

            DB::commit();

            return redirect()
                ->route('vendor.products.index')
                ->with('success', 'Product deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }

    /**
     * Generate unique SKU
     */
    private function generateSKU(): string
    {
        do {
            $sku = 'PRD-' . strtoupper(Str::random(8));
        } while (Product::where('sku', $sku)->exists());

        return $sku;
    }
}
