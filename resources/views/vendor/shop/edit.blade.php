@extends('layouts.vendor')

@section('title', 'Shop Settings')
@section('page-title', 'Shop Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('vendor.shop.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold mb-4">Shop Information</h3>
            
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Shop Name <span class="text-red-500">*</span></label>
                        <input type="text" name="shop_name" value="{{ old('shop_name', $vendor->shop_name) }}" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Shop Name (Arabic)</label>
                        <input type="text" name="shop_name_ar" value="{{ old('shop_name_ar', $vendor->shop_name_ar) }}" dir="rtl" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-2 border rounded-lg">{{ old('description', $vendor->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Description (Arabic)</label>
                    <textarea name="description_ar" rows="4" dir="rtl" class="w-full px-4 py-2 border rounded-lg">{{ old('description_ar', $vendor->description_ar) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">City</label>
                        <input type="text" name="city" value="{{ old('city', $vendor->city) }}" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Phone</label>
                        <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Address</label>
                    <input type="text" name="address" value="{{ old('address', $vendor->address) }}" class="w-full px-4 py-2 border rounded-lg">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold mb-4">Shop Images</h3>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-2">Shop Logo</label>
                    @if($vendor->logo)
                        <img src="{{ asset('storage/' . $vendor->logo) }}" class="w-32 h-32 object-cover rounded mb-2">
                    @endif
                    <input type="file" name="logo" accept="image/*" class="w-full">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Shop Banner</label>
                    @if($vendor->banner)
                        <img src="{{ asset('storage/' . $vendor->banner) }}" class="w-full h-32 object-cover rounded mb-2">
                    @endif
                    <input type="file" name="banner" accept="image/*" class="w-full">
                </div>
            </div>
        </div>

        <div class="flex gap-4 justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
