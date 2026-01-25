@extends('layouts.app')

@section('title', 'الصفحة الرئيسية')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl font-bold mb-4">Multivendor Marketplace</h1>
        <p class="text-xl mb-8">منصة تونسية للتجارة الإلكترونية متعددة البائعين</p>
        <a href="{{ route('products.index') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-bold hover:bg-gray-100 inline-block">
            تسوق الآن
        </a>
    </div>
</div>

<!-- Categories Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-3xl font-bold mb-8 text-center">تصفح حسب الفئة</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($categories as $category)
        <a href="{{ route('products.index', ['category' => $category->id]) }}" 
           class="group bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition">
            <div class="bg-blue-100 w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center group-hover:bg-blue-200 transition">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
            </div>
            <h3 class="font-bold text-lg mb-2">{{ $category->name_ar }}</h3>
            <p class="text-gray-500 text-sm">{{ $category->products_count }} منتج</p>
        </a>
        @endforeach
    </div>
</div>

<!-- Featured Products -->
<div class="bg-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold">منتجات مميزة</h2>
            <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                عرض الكل ←
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition group">
                <a href="{{ route('products.show', $product->slug) }}">
                    <div class="relative">
                        @if($product->primaryImage)
                            <img src="{{ $product->primaryImage->getImageUrl() }}" 
                                 alt="{{ $product->name_ar }}"
                                 class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        @if($product->hasDiscount())
                        <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-sm font-bold">
                            -{{ $product->getDiscountPercentage() }}%
                        </div>
                        @endif

                        @if($product->is_featured)
                        <div class="absolute top-2 left-2 bg-yellow-500 text-white px-2 py-1 rounded text-sm font-bold">
                            ⭐ مميز
                        </div>
                        @endif
                    </div>
                    
                    <div class="p-4">
                        <div class="text-sm text-gray-500 mb-1">{{ $product->vendor->shop_name_ar }}</div>
                        <h3 class="font-bold text-lg mb-2 line-clamp-2">{{ $product->name_ar }}</h3>
                        
                        <div class="flex items-center gap-2 mb-2">
                            @if($product->rating_avg > 0)
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $product->rating_avg)
                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endif
                                    @endfor
                                    <span class="text-sm text-gray-600 mr-1">({{ $product->rating_count }})</span>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-blue-600">{{ number_format($product->price, 2) }}</span>
                                <span class="text-sm text-gray-600"> دت</span>
                                @if($product->hasDiscount())
                                    <div class="text-sm text-gray-400 line-through">{{ number_format($product->compare_price, 2) }} دت</div>
                                @endif
                            </div>
                        </div>

                        @if(!$product->isInStock())
                            <div class="mt-2 text-red-500 text-sm font-bold">غير متوفر</div>
                        @elseif($product->isLowStock())
                            <div class="mt-2 text-orange-500 text-sm">الكمية محدودة</div>
                        @endif
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- New Products -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold">أحدث المنتجات</h2>
        <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="text-blue-600 hover:text-blue-800 font-medium">
            عرض الكل ←
        </a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($newProducts as $product)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition group">
            <a href="{{ route('products.show', $product->slug) }}">
                <div class="relative">
                    @if($product->primaryImage)
                        <img src="{{ $product->primaryImage->getImageUrl() }}" 
                             alt="{{ $product->name_ar }}"
                             class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded text-sm font-bold">
                        جديد
                    </div>
                </div>
                
                <div class="p-4">
                    <div class="text-sm text-gray-500 mb-1">{{ $product->vendor->shop_name_ar }}</div>
                    <h3 class="font-bold text-lg mb-2 line-clamp-2">{{ $product->name_ar }}</h3>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-2xl font-bold text-blue-600">{{ number_format($product->price, 2) }}</span>
                            <span class="text-sm text-gray-600"> دت</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>

<!-- Features Section -->
<div class="bg-blue-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-xl mb-2">شحن سريع</h3>
                <p class="text-gray-600">توصيل إلى جميع ولايات تونس</p>
            </div>
            <div class="text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-xl mb-2">دفع آمن</h3>
                <p class="text-gray-600">دفع عند الاستلام أو بالبطاقة</p>
            </div>
            <div class="text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-xl mb-2">دعم العملاء</h3>
                <p class="text-gray-600">خدمة عملاء متاحة دائماً</p>
            </div>
        </div>
    </div>
</div>
@endsection
