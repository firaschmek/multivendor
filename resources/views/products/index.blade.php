@extends('layouts.app')

@section('title', 'المنتجات')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar Filters -->
        <div class="w-full md:w-64 flex-shrink-0">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-bold text-lg mb-4">فلترة المنتجات</h3>
                
                <form method="GET" action="{{ route('products.index') }}">
                    <!-- Categories -->
                    <div class="mb-6">
                        <h4 class="font-semibold mb-3">الفئات</h4>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="category" value="" 
                                       {{ !request('category') ? 'checked' : '' }}
                                       class="ml-2">
                                <span>جميع الفئات</span>
                            </label>
                            @foreach($categories as $category)
                            <label class="flex items-center">
                                <input type="radio" name="category" value="{{ $category->id }}" 
                                       {{ request('category') == $category->id ? 'checked' : '' }}
                                       class="ml-2">
                                <span>{{ $category->name_ar }} ({{ $category->products_count }})</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <h4 class="font-semibold mb-3">نطاق السعر</h4>
                        <div class="space-y-2">
                            <input type="number" name="min_price" placeholder="من" 
                                   value="{{ request('min_price') }}"
                                   class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="number" name="max_price" placeholder="إلى" 
                                   value="{{ request('max_price') }}"
                                   class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Sorting -->
                    <div class="mb-6">
                        <h4 class="font-semibold mb-3">ترتيب</h4>
                        <select name="sort" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>الأكثر مبيعاً</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم</option>
                        </select>
                    </div>

                    <input type="hidden" name="search" value="{{ request('search') }}">

                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 font-medium">
                        تطبيق الفلترة
                    </button>
                    
                    @if(request()->hasAny(['category', 'min_price', 'max_price', 'sort']))
                    <a href="{{ route('products.index') }}" class="block text-center mt-2 text-gray-600 hover:text-gray-800">
                        إعادة تعيين
                    </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="flex-1">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold">المنتجات</h1>
                        <p class="text-gray-600">{{ $products->total() }} منتج</p>
                    </div>
                    @if(request('search'))
                    <div class="text-gray-600">
                        نتائج البحث عن: <strong>"{{ request('search') }}"</strong>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Products -->
            @if($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
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
                                ⭐
                            </div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <div class="text-xs text-gray-500 mb-1">{{ $product->vendor->shop_name_ar }}</div>
                            <h3 class="font-bold mb-2 line-clamp-2 h-12">{{ $product->name_ar }}</h3>
                            
                            @if($product->rating_avg > 0)
                            <div class="flex items-center gap-1 mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3 h-3 {{ $i <= $product->rating_avg ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                                <span class="text-xs text-gray-600">({{ $product->rating_count }})</span>
                            </div>
                            @endif

                            <div>
                                <span class="text-xl font-bold text-blue-600">{{ number_format($product->price, 2) }}</span>
                                <span class="text-sm text-gray-600"> دت</span>
                                @if($product->hasDiscount())
                                    <div class="text-sm text-gray-400 line-through">{{ number_format($product->compare_price, 2) }} دت</div>
                                @endif
                            </div>

                            @if(!$product->isInStock())
                                <div class="mt-2 text-red-500 text-xs font-bold">غير متوفر</div>
                            @elseif($product->isLowStock())
                                <div class="mt-2 text-orange-500 text-xs">الكمية محدودة</div>
                            @endif
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
            @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="text-xl font-bold mb-2">لا توجد منتجات</h3>
                <p class="text-gray-600">جرب تغيير معايير البحث</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
