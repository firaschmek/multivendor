@extends('layouts.app')

@section('title', 'إتمام الطلب')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">إتمام الطلب</h1>

    <form method="POST" action="{{ route('checkout.store') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf

        <!-- Shipping Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Contact Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">معلومات الاتصال</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">الاسم الكامل *</label>
                        <input type="text" name="shipping_name" required
                               value="{{ old('shipping_name', $user->name) }}"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('shipping_name')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">رقم الهاتف *</label>
                        <input type="tel" name="shipping_phone" required
                               value="{{ old('shipping_phone', $user->phone) }}"
                               placeholder="+216 20 123 456"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('shipping_phone')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">البريد الإلكتروني</label>
                        <input type="email" name="shipping_email"
                               value="{{ old('shipping_email', $user->email) }}"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('shipping_email')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">عنوان الشحن</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">العنوان الكامل *</label>
                        <textarea name="shipping_address" required rows="3"
                                  class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">المدينة/الولاية *</label>
                            <select name="shipping_city" required
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">اختر المدينة</option>
                                <option value="Tunis">تونس</option>
                                <option value="Ariana">أريانة</option>
                                <option value="Ben Arous">بن عروس</option>
                                <option value="Manouba">منوبة</option>
                                <option value="Nabeul">نابل</option>
                                <option value="Sousse">سوسة</option>
                                <option value="Monastir">المنستير</option>
                                <option value="Mahdia">المهدية</option>
                                <option value="Sfax">صفاقس</option>
                                <option value="Kairouan">القيروان</option>
                                <option value="Kasserine">القصرين</option>
                                <option value="Sidi Bouzid">سيدي بوزيد</option>
                                <option value="Gabes">قابس</option>
                                <option value="Medenine">مدنين</option>
                                <option value="Tataouine">تطاوين</option>
                                <option value="Gafsa">قفصة</option>
                                <option value="Tozeur">توزر</option>
                                <option value="Kebili">قبلي</option>
                                <option value="Bizerte">بنزرت</option>
                                <option value="Beja">باجة</option>
                                <option value="Jendouba">جندوبة</option>
                                <option value="Kef">الكاف</option>
                                <option value="Siliana">سليانة</option>
                                <option value="Zaghouan">زغوان</option>
                            </select>
                            @error('shipping_city')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">الرمز البريدي</label>
                            <input type="text" name="shipping_postal_code"
                                   value="{{ old('shipping_postal_code') }}"
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('shipping_postal_code')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">طريقة الدفع</h2>
                
                <div class="space-y-3">
                    <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="payment_method" value="cash_on_delivery" checked
                               class="ml-3">
                        <div class="flex-1">
                            <div class="font-semibold">الدفع عند الاستلام</div>
                            <div class="text-sm text-gray-600">ادفع نقداً عند استلام المنتج</div>
                        </div>
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </label>

                    <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 opacity-50">
                        <input type="radio" name="payment_method" value="credit_card" disabled
                               class="ml-3">
                        <div class="flex-1">
                            <div class="font-semibold">بطاقة الائتمان</div>
                            <div class="text-sm text-gray-600">قريباً</div>
                        </div>
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </label>
                </div>
                @error('payment_method')
                    <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                @enderror
            </div>

            <!-- Notes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">ملاحظات إضافية</h2>
                <textarea name="customer_notes" rows="4" placeholder="أي ملاحظات خاصة بالطلب..."
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('customer_notes') }}</textarea>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                <h2 class="text-xl font-bold mb-4">ملخص الطلب</h2>
                
                <!-- Cart Items -->
                <div class="space-y-3 mb-6 max-h-64 overflow-y-auto">
                    @foreach($cartItems as $item)
                    <div class="flex gap-3">
                        @if($item->product->primaryImage)
                            <img src="{{ $item->product->primaryImage->getThumbnailUrl() }}" 
                                 alt="{{ $item->product->name_ar }}"
                                 class="w-16 h-16 object-cover rounded">
                        @endif
                        <div class="flex-1">
                            <div class="font-semibold text-sm line-clamp-2">{{ $item->product->name_ar }}</div>
                            <div class="text-sm text-gray-600">الكمية: {{ $item->quantity }}</div>
                            <div class="text-sm font-semibold text-blue-600">
                                {{ number_format($item->product->price * $item->quantity, 2) }} دت
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Totals -->
                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">المجموع الفرعي</span>
                        <span class="font-semibold">{{ number_format($cartTotal, 2) }} دت</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">الشحن</span>
                        <span class="font-semibold">7.00 دت</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">الضريبة (19%)</span>
                        <span class="font-semibold">{{ number_format($cartTotal * 0.19, 2) }} دت</span>
                    </div>
                    <div class="border-t pt-2 flex justify-between text-lg">
                        <span class="font-bold">المجموع الإجمالي</span>
                        <span class="font-bold text-blue-600">
                            {{ number_format($cartTotal + 7 + ($cartTotal * 0.19), 2) }} دت
                        </span>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-bold mt-6">
                    تأكيد الطلب
                </button>

                <a href="{{ route('cart.index') }}" class="block text-center text-gray-600 hover:text-gray-800 mt-3">
                    ← العودة إلى السلة
                </a>

                <!-- Security Badge -->
                <div class="mt-6 pt-6 border-t text-center">
                    <div class="flex items-center justify-center gap-2 text-gray-600 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span>معاملات آمنة ومشفرة</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
