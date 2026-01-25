@extends('layouts.app')

@section('title', 'التسجيل كبائع')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold mb-4">انضم كبائع إلى Multivendor</h1>
        <p class="text-xl text-gray-600">ابدأ في بيع منتجاتك لآلاف العملاء في تونس</p>
    </div>

    <!-- Benefits -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="bg-blue-100 w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="font-bold text-lg mb-2">عمولة منخفضة</h3>
            <p class="text-gray-600">عمولة تنافسية تبدأ من 10% فقط</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="bg-green-100 w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h3 class="font-bold text-lg mb-2">آلاف العملاء</h3>
            <p class="text-gray-600">وصول مباشر لقاعدة عملاء واسعة</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="bg-purple-100 w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <h3 class="font-bold text-lg mb-2">دعم كامل</h3>
            <p class="text-gray-600">فريق دعم متواصل لمساعدتك</p>
        </div>
    </div>

    <!-- Registration Form -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-6">نموذج التسجيل</h2>
        
        @guest
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <p class="text-gray-700">
                يجب أن يكون لديك حساب أولاً. 
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium">سجل الآن</a>
                أو 
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">سجل الدخول</a>
            </p>
        </div>
        @endguest

        <form method="POST" action="{{ route('vendor.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Shop Name -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">اسم المتجر *</label>
                <input type="text" name="shop_name" required
                       value="{{ old('shop_name') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('shop_name')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Shop Name Arabic -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">اسم المتجر بالعربية *</label>
                <input type="text" name="shop_name_ar" required
                       value="{{ old('shop_name_ar') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       dir="rtl">
                @error('shop_name_ar')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">وصف المتجر</label>
                <textarea name="description_ar" rows="4"
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                          dir="rtl">{{ old('description_ar') }}</textarea>
                @error('description_ar')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Contact Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium mb-2">رقم الهاتف *</label>
                    <input type="tel" name="phone" required
                           value="{{ old('phone') }}"
                           placeholder="+216 20 123 456"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('phone')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">البريد الإلكتروني *</label>
                    <input type="email" name="email" required
                           value="{{ old('email') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('email')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Address -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">العنوان *</label>
                <textarea name="address_ar" rows="2" required
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                          dir="rtl">{{ old('address_ar') }}</textarea>
                @error('address_ar')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Business Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium mb-2">رقم السجل التجاري</label>
                    <input type="text" name="tax_number"
                           value="{{ old('tax_number') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('tax_number')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">رخصة العمل</label>
                    <input type="text" name="business_license"
                           value="{{ old('business_license') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('business_license')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Terms -->
            <div class="mb-6">
                <label class="flex items-start">
                    <input type="checkbox" name="terms" required class="mt-1 ml-2">
                    <span class="text-sm text-gray-700">
                        أوافق على <a href="#" class="text-blue-600 hover:text-blue-800">شروط وأحكام</a> البيع على منصة Multivendor
                    </span>
                </label>
                @error('terms')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit" 
                    class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-bold"
                    @guest disabled @endguest>
                @guest
                    يجب تسجيل الدخول أولاً
                @else
                    إرسال طلب التسجيل
                @endguest
            </button>
        </form>
    </div>

    <!-- How it Works -->
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-center mb-8">كيف تبدأ؟</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="bg-blue-100 w-12 h-12 rounded-full mx-auto mb-3 flex items-center justify-center font-bold text-blue-600">1</div>
                <h3 class="font-bold mb-2">سجل</h3>
                <p class="text-sm text-gray-600">املأ نموذج التسجيل</p>
            </div>
            <div class="text-center">
                <div class="bg-blue-100 w-12 h-12 rounded-full mx-auto mb-3 flex items-center justify-center font-bold text-blue-600">2</div>
                <h3 class="font-bold mb-2">انتظر الموافقة</h3>
                <p class="text-sm text-gray-600">سنراجع طلبك خلال 24 ساعة</p>
            </div>
            <div class="text-center">
                <div class="bg-blue-100 w-12 h-12 rounded-full mx-auto mb-3 flex items-center justify-center font-bold text-blue-600">3</div>
                <h3 class="font-bold mb-2">أضف منتجاتك</h3>
                <p class="text-sm text-gray-600">ابدأ في إضافة منتجاتك</p>
            </div>
            <div class="text-center">
                <div class="bg-blue-100 w-12 h-12 rounded-full mx-auto mb-3 flex items-center justify-center font-bold text-blue-600">4</div>
                <h3 class="font-bold mb-2">ابدأ البيع</h3>
                <p class="text-sm text-gray-600">استقبل الطلبات وحقق الأرباح</p>
            </div>
        </div>
    </div>
</div>
@endsection
