<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()
            ->featured()
            ->with(['vendor', 'primaryImage', 'category'])
            ->limit(8)
            ->get();

        $newProducts = Product::active()
            ->with(['vendor', 'primaryImage', 'category'])
            ->latest('published_at')
            ->limit(8)
            ->get();

        $categories = Category::active()
            ->parent()
            ->ordered()
            ->withCount('products')
            ->get();

        return view('home', compact('featuredProducts', 'newProducts', 'categories'));
    }
}
