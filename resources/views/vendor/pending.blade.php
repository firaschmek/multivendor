@extends('layouts.vendor')

@section('title', 'في انتظار الموافقة')

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="text-center max-w-md">
        <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-clock text-5xl text-yellow-600"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-4">الحساب في انتظار الموافقة</h1>
        <p class="text-gray-600 mb-8">
            حساب البائع الخاص بك قيد المراجعة حالياً. سيقوم فريقنا بمراجعة طلبك والتواصل معك خلال 24-48 ساعة.
        </p>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-left">
            <h3 class="font-bold text-blue-900 mb-2">ما الذي سيحدث بعد ذلك؟</h3>
            <ul class="text-sm text-blue-800 space-y-1">
                <li>✓ سنقوم بالتحقق من معلومات نشاطك التجاري</li>
                <li>✓ مراجعة مستنداتك</li>
                <li>✓ سنرسل لك بريدًا إلكترونيًا بمجرد الموافقة</li>
            </ul>
        </div>
        <a href="{{ route('home') }}" class="inline-block mt-6 text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>العودة إلى الصفحة الرئيسية
        </a>
    </div>
</div>
@endsection
