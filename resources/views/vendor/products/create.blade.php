@extends('layouts.vendor')

@section('title', 'Add New Product')
@section('page-title', 'Add New Product')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Product Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Product Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Name (Arabic)</label>
                    <input type="text" name="name_ar" value="{{ old('name_ar') }}" dir="rtl" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>{{ old('description') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description (Arabic)</label>
                    <textarea name="description_ar" rows="4" dir="rtl" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description_ar') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @foreach($category->children as $child)
                                <option value="{{ $child->id }}">-- {{ $child->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" placeholder="Auto-generated if empty" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Pricing -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Pricing</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (TND) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Compare Price (TND)</label>
                    <input type="number" name="compare_price" value="{{ old('compare_price') }}" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Show discount percentage</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cost (TND)</label>
                    <input type="number" name="cost" value="{{ old('cost') }}" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Your cost (not shown)</p>
                </div>
            </div>
        </div>

        <!-- Inventory -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Inventory</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" value="{{ old('quantity', 0) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Low Stock Threshold</label>
                    <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', 5) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="track_inventory" value="1" checked class="rounded text-blue-600">
                        <span class="text-sm text-gray-700">Track inventory for this product</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Images -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Product Images</h3>
            <input type="file" name="images[]" multiple accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <p class="text-xs text-gray-500 mt-2">Upload multiple images (max 2MB each)</p>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 justify-end">
            <a href="{{ route('vendor.products.index') }}" class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                Create Product
            </button>
        </div>
    </form>
</div>
@endsection
