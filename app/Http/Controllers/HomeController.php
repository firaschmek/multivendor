<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get all active categories
        $categories = Category::whereNull('parent_id')
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();

        // Get featured products (you can add is_featured column or use high-rated products)
        $featuredProducts = Product::with(['vendor', 'images', 'reviews'])
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->inRandomOrder()
            ->limit(8)
            ->get();

        // Get newest products
        $newProducts = Product::with(['vendor', 'images'])
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->latest()
            ->limit(8)
            ->get();

        return view('home', compact('categories', 'featuredProducts', 'newProducts'));
    }
}
