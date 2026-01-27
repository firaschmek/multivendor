@extends('layouts.admin')

@section('title', 'Products Management')
@section('page-title', 'All Products')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Products Management</h2>
            <p class="text-gray-600">Manage all products from all vendors</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="flex-1 min-w-[200px] px-4 py-2 border rounded-lg">

            <select name="vendor" class="px-4 py-2 border rounded-lg">
                <option value="">All Vendors</option>
                @foreach($vendors as $vendor)
                <option value="{{ $vendor->id }}" {{ request('vendor') == $vendor->id ? 'selected' : '' }}>
                {{ $vendor->shop_name }}
                </option>
                @endforeach
            </select>

            <select name="category" class="px-4 py-2 border rounded-lg">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
                </option>
                @endforeach
            </select>

            <select name="status" class="px-4 py-2 border rounded-lg">
                <option value="">All Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>

            <select name="stock" class="px-4 py-2 border rounded-lg">
                <option value="">All Stock</option>
                <option value="in" {{ request('stock') === 'in' ? 'selected' : '' }}>In Stock</option>
                <option value="low" {{ request('stock') === 'low' ? 'selected' : '' }}>Low Stock</option>
                <option value="out" {{ request('stock') === 'out' ? 'selected' : '' }}>Out of Stock</option>
            </select>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-search mr-2"></i>Search
            </button>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                Reset
            </a>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
            @forelse($products as $product)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        @if($product->images->first())
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-12 h-12 rounded object-cover">
                        @else
                        <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                        @endif
                        <div>
                            <div class="font-medium text-gray-800">{{ $product->name }}</div>
                            <div class="text-sm text-gray-500">{{ $product->sku }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $product->vendor->shop_name }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $product->category->name }}</td>
                <td class="px-6 py-4 font-medium text-gray-800">{{ number_format($product->price, 2) }} TND</td>
                <td class="px-6 py-4">
                            <span class="text-sm {{ $product->quantity == 0 ? 'text-red-600 font-bold' : ($product->quantity <= 5 ? 'text-orange-600' : 'text-gray-600') }}">
                                {{ $product->quantity }}
                            </span>
                </td>
                <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.products.show', $product) }}" class="text-blue-600 hover:text-blue-800" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-green-600 hover:text-green-800" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-800" title="{{ $product->is_active ? 'Deactivate' : 'Activate' }}">
                                <i class="fas fa-{{ $product->is_active ? 'eye-slash' : 'eye' }}"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-box text-4xl mb-3 opacity-50"></i>
                    <p>No products found</p>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="bg-white rounded-lg shadow-md p-4">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
