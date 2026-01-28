@extends('layouts.vendor')

@section('title', 'إعدادات المتجر')
@section('page-title', 'إعدادات المتجر')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('vendor.shop.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold mb-4">معلومات المتجر</h3>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            اسم المتجر <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="shop_name"
                            value="{{ old('shop_name', $vendor->shop_name) }}"
                            class="w-full px-4 py-2 border rounded-lg"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            اسم المتجر (بالعربية)
                        </label>
                        <input
                            type="text"
                            name="shop_name_ar"
                            value="{{ old('shop_name_ar', $vendor->shop_name_ar) }}"
                            dir="rtl"
                            class="w-full px-4 py-2 border rounded-lg"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">
                        الوصف
                    </label>
                    <textarea
                        name="description"
                        rows="4"
                        class="w-full px-4 py-2 border rounded-lg"
                    >{{ old('description', $vendor->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">
                        الوصف (بالعربية)
                    </label>
                    <textarea
                        name="description_ar"
                        rows="4"
                        dir="rtl"
                        class="w-full px-4 py-2 border rounded-lg"
                    >{{ old('description_ar', $vendor->description_ar) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            المدينة
                        </label>
                        <input
                            type="text"
                            name="city"
                            value="{{ old('city', $vendor->city) }}"
                            class="w-full px-4 py-2 border rounded-lg"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            رقم الهاتف
                        </label>
                        <input
                            type="tel"
                            name="phone"
                            value="{{ old('phone', auth()->user()->phone) }}"
                            class="w-full px-4 py-2 border rounded-lg"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">
                        العنوان
                    </label>
                    <input
                        type="text"
                        name="address"
                        value="{{ old('address', $vendor->address) }}"
                        class="w-full px-4 py-2 border rounded-lg"
                    >
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold mb-4">صور المتجر</h3>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-2">
                        شعار المتجر
                    </label>
                    @if($vendor->logo)
                    <img
                        src="{{ asset('storage/' . $vendor->logo) }}"
                        class="w-32 h-32 object-cover rounded mb-2"
                    >
                    @endif
                    <input type="file" name="logo" accept="image/*" class="w-full">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">
                        بانر المتجر
                    </label>
                    @if($vendor->banner)
                    <img
                        src="{{ asset('storage/' . $vendor->banner) }}"
                        class="w-full h-32 object-cover rounded mb-2"
                    >
                    @endif
                    <input type="file" name="banner" accept="image/*" class="w-full">
                </div>
            </div>
        </div>

        <div class="flex gap-4 justify-end">
            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg"
            >
                حفظ التغييرات
            </button>
        </div>
    </form>
</div>
@endsection
