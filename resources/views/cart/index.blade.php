@extends('layouts.app')

@section('title', 'سلة التسوق')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">سلة التسوق</h1>

    @if($cartItems->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md">
                @foreach($cartItems as $item)
                <div class="p-6 border-b last:border-b-0">
                    <div class="flex gap-4">
                        <!-- Product Image -->
                        <div class="flex-shrink-0">
                            @if($item->product->primaryImage)
                                <img src="{{ $item->product->primaryImage->getThumbnailUrl() }}" 
                                     alt="{{ $item->product->name_ar }}"
                                     class="w-24 h-24 object-cover rounded">
                            @else
                                <div class="w-24 h-24 bg-gray-200 rounded flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <div>
                                    <a href="{{ route('products.show', $item->product->slug) }}" 
                                       class="font-bold text-lg hover:text-blue-600">
                                        {{ $item->product->name_ar }}
                                    </a>
                                    <div class="text-sm text-gray-600 mt-1">
                                        البائع: {{ $item->product->vendor->shop_name_ar }}
                                    </div>
                                </div>
                                
                                <!-- Remove Button -->
                                <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>

                            <div class="flex items-center justify-between mt-4">
                                <!-- Quantity -->
                                <div class="flex items-center border rounded-lg">
                                    <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" 
                                            class="px-3 py-1 hover:bg-gray-100"
                                            {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                        -
                                    </button>
                                    <span class="px-4 py-1 border-x">{{ $item->quantity }}</span>
                                    <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" 
                                            class="px-3 py-1 hover:bg-gray-100"
                                            {{ $item->quantity >= $item->product->quantity ? 'disabled' : '' }}>
                                        +
                                    </button>
                                </div>

                                <!-- Price -->
                                <div class="text-left">
                                    <div class="text-xl font-bold text-blue-600">
                                        {{ number_format($item->product->price * $item->quantity, 2) }} دت
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{ number_format($item->product->price, 2) }} دت للوحدة
                                    </div>
                                </div>
                            </div>

                            <!-- Stock Warning -->
                            @if($item->product->track_inventory && $item->quantity > $item->product->quantity)
                            <div class="mt-2 text-red-500 text-sm">
                                متوفر {{ $item->product->quantity }} وحدة فقط
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Clear Cart -->
            <div class="mt-4">
                <form method="POST" action="{{ route('cart.clear') }}" 
                      onsubmit="return confirm('هل أنت متأكد من تفريغ السلة؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium">
                        تفريغ السلة
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                <h2 class="text-xl font-bold mb-4">ملخص الطلب</h2>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">المجموع الفرعي</span>
                        <span class="font-semibold">{{ number_format($cartTotal, 2) }} دت</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">الشحن</span>
                        <span class="font-semibold">7.00 دت</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">الضريبة (19%)</span>
                        <span class="font-semibold">{{ number_format($cartTotal * 0.19, 2) }} دت</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between text-lg">
                        <span class="font-bold">المجموع الإجمالي</span>
                        <span class="font-bold text-blue-600">
                            {{ number_format($cartTotal + 7 + ($cartTotal * 0.19), 2) }} دت
                        </span>
                    </div>
                </div>

                <div class="space-y-3">
                    @auth
                        <a href="{{ route('checkout.index') }}" 
                           class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700 font-bold">
                            إتمام الطلب
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700 font-bold">
                            تسجيل الدخول لإتمام الطلب
                        </a>
                    @endauth
                    
                    <a href="{{ route('products.index') }}" 
                       class="block w-full bg-gray-200 text-gray-800 text-center py-3 rounded-lg hover:bg-gray-300 font-medium">
                        مواصلة التسوق
                    </a>
                </div>

                <!-- Payment Methods -->
                <div class="mt-6 pt-6 border-t">
                    <div class="text-sm text-gray-600 mb-2">طرق الدفع المتاحة:</div>
                    <div class="flex gap-2">
                        <div class="bg-gray-100 px-3 py-2 rounded text-xs">الدفع عند الاستلام</div>
                        <div class="bg-gray-100 px-3 py-2 rounded text-xs">بطاقة الائتمان</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <h2 class="text-2xl font-bold mb-4">سلة التسوق فارغة</h2>
        <p class="text-gray-600 mb-6">لم تقم بإضافة أي منتجات إلى سلة التسوق بعد</p>
        <a href="{{ route('products.index') }}" 
           class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-bold">
            تصفح المنتجات
        </a>
    </div>
    @endif
</div>

<script>
async function updateQuantity(cartItemId, quantity) {
    if (quantity < 1) return;
    
    try {
        const response = await fetch(`/cart/update/${cartItemId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ quantity })
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message);
        }
    } catch (error) {
        alert('حدث خطأ أثناء تحديث الكمية');
    }
}
</script>
@endsection
