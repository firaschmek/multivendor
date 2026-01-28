@extends('layouts.vendor')

@section('title', 'الحساب معلق')

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="text-center max-w-md">
        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-ban text-5xl text-red-600"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-4">الحساب معلق</h1>
        <p class="text-gray-600 mb-8">
            تم تعليق حسابك كبائع مؤقتًا. قد يكون السبب انتهاك السياسات أو وجود مشكلات معلقة تحتاج إلى حل.
        </p>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-left mb-6">
            <h3 class="font-bold text-red-900 mb-2">ماذا تفعل؟</h3>
            <p class="text-sm text-red-800">
                يرجى التواصل مع فريق الدعم لدينا لحل هذه المشكلة وإعادة تفعيل حسابك.
            </p>
        </div>
        <a href="mailto:support@multivendor.test" class="inline-block bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg">
            <i class="fas fa-envelope mr-2"></i>تواصل مع الدعم
        </a>
    </div>
</div>
@endsection
