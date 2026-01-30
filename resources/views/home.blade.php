@extends('layouts.app')

@section('title', 'الصفحة الرئيسية')

@push('styles')
<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    @keyframes pulse-slow {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    .animate-float { animation: float 3s ease-in-out infinite; }
    .animate-pulse-slow { animation: pulse-slow 2s ease-in-out infinite; }
    .hero-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .card-hover:hover {
        transform: translateY(-8px);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-700 to-purple-800 text-white hero-pattern">
    <!-- Decorative elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
        <div class="absolute top-20 left-10 w-72 h-72 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse-slow" style="animation-delay: 1s;"></div>
        <div class="absolute top-40 right-1/4 w-64 h-64 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-15 animate-pulse-slow" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
            <div class="inline-flex items-center bg-white/10 backdrop-blur-sm rounded-full px-4 py-2 mb-6">
                <span class="animate-pulse w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                <span class="text-sm font-medium">منصة تونسية للتجارة الإلكترونية</span>
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
                <span class="block">مرحباً بك في</span>
                <span class="block bg-gradient-to-r from-yellow-300 via-orange-300 to-pink-300 bg-clip-text text-transparent">السوق الإلكتروني</span>
            </h1>
            <p class="text-xl sm:text-2xl mb-10 text-blue-100 max-w-2xl mx-auto">
                اكتشف آلاف المنتجات من أفضل البائعين في تونس
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products.index') }}" class="group inline-flex items-center justify-center bg-white text-blue-600 px-8 py-4 rounded-xl font-bold hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <span>تسوق الآن</span>
                    <svg class="w-5 h-5 mr-2 transform rotate-180 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-transparent border-2 border-white text-white px-8 py-4 rounded-xl font-bold hover:bg-white hover:text-blue-600 transition-all duration-300">
                    سجل كبائع
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-3xl sm:text-4xl font-extrabold text-white">+1000</div>
                <div class="text-blue-200 mt-1">منتج متاح</div>
            </div>
            <div class="text-center">
                <div class="text-3xl sm:text-4xl font-extrabold text-white">+50</div>
                <div class="text-blue-200 mt-1">بائع موثوق</div>
            </div>
            <div class="text-center">
                <div class="text-3xl sm:text-4xl font-extrabold text-white">+5000</div>
                <div class="text-blue-200 mt-1">عميل سعيد</div>
            </div>
            <div class="text-center">
                <div class="text-3xl sm:text-4xl font-extrabold text-white">24/7</div>
                <div class="text-blue-200 mt-1">دعم متواصل</div>
            </div>
        </div>
    </div>

    <!-- Wave -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#f3f4f6"/>
        </svg>
    </div>
</div>

<!-- Categories Section -->
<div class="bg-gray-100 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">تصفح حسب الفئة</h2>
            <p class="text-gray-600 max-w-xl mx-auto">اختر من بين مجموعة متنوعة من الفئات</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->id]) }}"
               class="group bg-white rounded-2xl shadow-md p-6 text-center hover:shadow-2xl transition-all duration-300 card-hover border border-gray-100">
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 w-20 h-20 rounded-2xl mx-auto mb-4 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-lg mb-2 text-gray-900 group-hover:text-blue-600 transition-colors">{{ $category->name_ar }}</h3>
                <p class="text-gray-500 text-sm">{{ $category->products_count }} منتج</p>
            </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Featured Products -->
<div class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">منتجات مميزة</h2>
                <p class="text-gray-600 mt-2">اكتشف أفضل المنتجات المختارة لك</p>
            </div>
            <a href="{{ route('products.index') }}" class="hidden sm:inline-flex items-center text-blue-600 hover:text-blue-800 font-medium group">
                عرض الكل
                <svg class="w-5 h-5 mr-1 transform rotate-180 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
            <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 group card-hover border border-gray-100">
                <a href="{{ route('products.show', $product->slug) }}">
                    <div class="relative overflow-hidden">
                        @if($product->primaryImage)
                            <img src="{{ $product->primaryImage->getImageUrl() }}"
                                 alt="{{ $product->name_ar }}"
                                 class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-56 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif

                        <!-- Badges -->
                        <div class="absolute top-3 right-3 flex flex-col gap-2">
                            @if($product->hasDiscount())
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                                -{{ $product->getDiscountPercentage() }}%
                            </span>
                            @endif
                            @if($product->is_featured)
                            <span class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                                مميز
                            </span>
                            @endif
                        </div>

                        <!-- Quick add overlay -->
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <span class="bg-white text-gray-900 px-6 py-2 rounded-full font-bold transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                عرض التفاصيل
                            </span>
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="text-sm text-blue-600 font-medium mb-1">{{ $product->vendor->shop_name_ar }}</div>
                        <h3 class="font-bold text-lg mb-2 line-clamp-2 text-gray-900 group-hover:text-blue-600 transition-colors">{{ $product->name_ar }}</h3>

                        @if($product->rating_avg > 0)
                        <div class="flex items-center gap-1 mb-3">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $product->rating_avg ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-500">({{ $product->rating_count }})</span>
                        </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-extrabold text-blue-600">{{ number_format($product->price, 2) }}</span>
                                <span class="text-sm text-gray-500 mr-1">دت</span>
                                @if($product->hasDiscount())
                                    <div class="text-sm text-gray-400 line-through">{{ number_format($product->compare_price, 2) }} دت</div>
                                @endif
                            </div>
                        </div>

                        @if(!$product->isInStock())
                            <div class="mt-3 inline-block bg-red-100 text-red-600 text-sm font-bold px-3 py-1 rounded-full">غير متوفر</div>
                        @elseif($product->isLowStock())
                            <div class="mt-3 inline-block bg-orange-100 text-orange-600 text-sm font-medium px-3 py-1 rounded-full">الكمية محدودة</div>
                        @endif
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-8 sm:hidden">
            <a href="{{ route('products.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                عرض جميع المنتجات
                <svg class="w-5 h-5 mr-1 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- New Products -->
<div class="bg-gradient-to-br from-gray-50 to-blue-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">أحدث المنتجات</h2>
                <p class="text-gray-600 mt-2">منتجات جديدة تضاف يومياً</p>
            </div>
            <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="hidden sm:inline-flex items-center text-blue-600 hover:text-blue-800 font-medium group">
                عرض الكل
                <svg class="w-5 h-5 mr-1 transform rotate-180 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($newProducts as $product)
            <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 group card-hover border border-gray-100">
                <a href="{{ route('products.show', $product->slug) }}">
                    <div class="relative overflow-hidden">
                        @if($product->primaryImage)
                            <img src="{{ $product->primaryImage->getImageUrl() }}"
                                 alt="{{ $product->name_ar }}"
                                 class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-56 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif

                        <div class="absolute top-3 right-3">
                            <span class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg animate-pulse">
                                جديد
                            </span>
                        </div>

                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <span class="bg-white text-gray-900 px-6 py-2 rounded-full font-bold transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                عرض التفاصيل
                            </span>
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="text-sm text-blue-600 font-medium mb-1">{{ $product->vendor->shop_name_ar }}</div>
                        <h3 class="font-bold text-lg mb-3 line-clamp-2 text-gray-900 group-hover:text-blue-600 transition-colors">{{ $product->name_ar }}</h3>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-extrabold text-blue-600">{{ number_format($product->price, 2) }}</span>
                                <span class="text-sm text-gray-500 mr-1">دت</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">لماذا تختار السوق؟</h2>
            <p class="text-gray-600 max-w-xl mx-auto">نوفر لك أفضل تجربة تسوق إلكتروني في تونس</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 hover:shadow-xl transition-all duration-300 card-hover">
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 w-20 h-20 rounded-2xl mx-auto mb-6 flex items-center justify-center shadow-lg animate-float">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-xl mb-3 text-gray-900">شحن سريع</h3>
                <p class="text-gray-600">توصيل سريع إلى جميع ولايات تونس مع إمكانية تتبع الطلب</p>
            </div>
            <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-green-50 to-emerald-50 hover:shadow-xl transition-all duration-300 card-hover">
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 w-20 h-20 rounded-2xl mx-auto mb-6 flex items-center justify-center shadow-lg animate-float" style="animation-delay: 0.5s;">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-xl mb-3 text-gray-900">دفع آمن</h3>
                <p class="text-gray-600">دفع عند الاستلام أو عبر البطاقة البنكية بكل أمان</p>
            </div>
            <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-purple-50 to-pink-50 hover:shadow-xl transition-all duration-300 card-hover">
                <div class="bg-gradient-to-br from-purple-500 to-pink-600 w-20 h-20 rounded-2xl mx-auto mb-6 flex items-center justify-center shadow-lg animate-float" style="animation-delay: 1s;">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-xl mb-3 text-gray-900">دعم العملاء</h3>
                <p class="text-gray-600">فريق دعم متخصص متاح على مدار الساعة لمساعدتك</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-gradient-to-r from-blue-600 via-indigo-700 to-purple-800 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">هل أنت بائع؟</h2>
        <p class="text-xl text-blue-100 mb-8">انضم إلى منصتنا وابدأ ببيع منتجاتك لآلاف العملاء في تونس</p>
        <a href="{{ route('register') }}" class="inline-flex items-center bg-white text-blue-600 px-8 py-4 rounded-xl font-bold hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
            سجل الآن مجاناً
            <svg class="w-5 h-5 mr-2 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
        </a>
    </div>
</div>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-2 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold">السوق</span>
                </div>
                <p class="text-gray-400">منصة تونسية للتجارة الإلكترونية تربط بين البائعين والمشترين</p>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-4">روابط سريعة</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition">الرئيسية</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-white transition">المنتجات</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white transition">سجل كبائع</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-4">الدعم</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white transition">مركز المساعدة</a></li>
                    <li><a href="#" class="hover:text-white transition">سياسة الإرجاع</a></li>
                    <li><a href="#" class="hover:text-white transition">الشروط والأحكام</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-4">تواصل معنا</h4>
                <ul class="space-y-2 text-gray-400">
                    <li class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        contact@souq.tn
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        +216 XX XXX XXX
                    </li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; {{ date('Y') }} السوق. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</footer>
@endsection
