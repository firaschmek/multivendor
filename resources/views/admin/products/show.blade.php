@extends('layouts.admin')

@section('title', $product->name)
@section('page-title', 'Product Details')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Products
        </a>
        <div class="flex gap-3">
            <a href="{{ route('admin.products.edit', $product) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-edit mr-2"></i>Edit Product
            </a>
            <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                    <i class="fas fa-{{ $product->is_active ? 'eye-slash' : 'eye' }} mr-2"></i>
                    {{ $product->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="font-bold text-gray-800 mb-4">Product Images</h3>
            <div class="space-y-4">
                @forelse($product->images as $image)
                <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full rounded-lg">
                @empty
                <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-4xl"></i>
                </div>
                @endforelse
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h2>
                        @if($product->name_ar)
                        <h3 class="text-lg text-gray-600" dir="rtl">{{ $product->name_ar }}</h3>
                        @endif
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="text-sm text-gray-600">SKU</label>
                        <div class="font-medium">{{ $product->sku }}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Category</label>
                        <div class="font-medium">{{ $product->category->name }}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Vendor</label>
                        <div class="font-medium">
                            <a href="{{ route('admin.vendors.show', $product->vendor) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $product->vendor->shop_name }}
                            </a>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Price</label>
                        <div class="font-bold text-blue-600 text-xl">{{ number_format($product->price, 2) }} TND</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Stock</label>
                        <div class="font-medium {{ $product->quantity == 0 ? 'text-red-600' : ($product->quantity <= 5 ? 'text-orange-600' : 'text-green-600') }}">
                            {{ $product->quantity }} units
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-sm text-gray-600">Description</label>
                    <p class="text-gray-800 mt-2">{{ $product->description }}</p>
                </div>

                @if($product->description_ar)
                <div>
                    <label class="text-sm text-gray-600">Description (Arabic)</label>
                    <p class="text-gray-800 mt-2" dir="rtl">{{ $product->description_ar }}</p>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow-md p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['total_sales'] }}</div>
                    <div class="text-sm text-gray-600">Total Sales</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4 text-center">
                    <div class="text-2xl font-bold text-green-600">{{ number_format($stats['total_revenue'], 0) }}</div>
                    <div class="text-sm text-gray-600">Revenue (TND)</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4 text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $stats['views'] }}</div>
                    <div class="text-sm text-gray-600">Views</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4 text-center">
                    <div class="text-2xl font-bold text-orange-600">{{ $stats['total_reviews'] }}</div>
                    <div class="text-sm text-gray-600">Reviews</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
