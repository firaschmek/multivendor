@extends('layouts.vendor')

@section('title', 'My Products')
@section('page-title', 'Products Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">My Products</h2>
            <p class="text-gray-600">Manage your product catalog</p>
        </div>
        <a href="{{ route('vendor.products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition">
            <i class="fas fa-plus"></i>
            Add New Product
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <!-- Search -->
            <div class="flex-1 min-w-[250px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Category Filter -->
            <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
                </option>
                @endforeach
            </select>

            <!-- Status Filter -->
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>

            <!-- Stock Filter -->
            <select name="stock" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">All Stock</option>
                <option value="in" {{ request('stock') === 'in' ? 'selected' : '' }}>In Stock</option>
                <option value="low" {{ request('stock') === 'low' ? 'selected' : '' }}>Low Stock</option>
                <option value="out" {{ request('stock') === 'out' ? 'selected' : '' }}>Out of Stock</option>
            </select>

            <!-- Buttons -->
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-search mr-2"></i>Search
            </button>
            <a href="{{ route('vendor.products.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg transition">
                Reset
            </a>
        </form>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
            <!-- Product Image -->
            <div class="relative h-48 bg-gray-200">
                @if($product->images->first())
                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-4xl"></i>
                </div>
                @endif

                <!-- Status Badge -->
                <span class="absolute top-2 right-2 px-2 py-1 text-xs rounded-full {{ $product->is_active ? 'bg-green-500 text-white' : 'bg-gray-500 text-white' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>

                <!-- Stock Badge -->
                @if($product->quantity == 0)
                <span class="absolute top-2 left-2 px-2 py-1 text-xs rounded-full bg-red-500 text-white">
                            Out of Stock
                        </span>
                @elseif($product->quantity <= 5)
                <span class="absolute top-2 left-2 px-2 py-1 text-xs rounded-full bg-orange-500 text-white">
                            Low Stock
                        </span>
                @endif
            </div>

            <!-- Product Info -->
            <div class="p-4">
                <h3 class="font-bold text-gray-800 mb-1 truncate">{{ $product->name }}</h3>
                <p class="text-sm text-gray-600 mb-2">SKU: {{ $product->sku }}</p>

                <div class="flex items-center justify-between mb-3">
                    <div class="text-lg font-bold text-blue-600">{{ number_format($product->price, 2) }} TND</div>
                    <div class="text-sm text-gray-600">Stock: <span class="font-medium">{{ $product->quantity }}</span></div>
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <a href="{{ route('vendor.products.show', $product) }}" class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-600 py-2 rounded text-center text-sm transition">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="{{ route('vendor.products.edit', $product) }}" class="flex-1 bg-green-50 hover:bg-green-100 text-green-600 py-2 rounded text-center text-sm transition">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('vendor.products.toggle-status', $product) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-gray-50 hover:bg-gray-100 text-gray-600 px-3 py-2 rounded text-sm transition" title="{{ $product->is_active ? 'Deactivate' : 'Activate' }}">
                            <i class="fas fa-{{ $product->is_active ? 'eye-slash' : 'eye' }}"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-800 mb-2">No Products Found</h3>
            <p class="text-gray-600 mb-6">Start adding products to your store</p>
            <a href="{{ route('vendor.products.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg transition">
                <i class="fas fa-plus mr-2"></i>Add Your First Product
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="bg-white rounded-lg shadow-md p-4">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
