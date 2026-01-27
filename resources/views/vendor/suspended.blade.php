@extends('layouts.vendor')

@section('title', 'Account Suspended')

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="text-center max-w-md">
        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-ban text-5xl text-red-600"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Account Suspended</h1>
        <p class="text-gray-600 mb-8">
            Your vendor account has been temporarily suspended. This may be due to policy violations or pending issues that need to be resolved.
        </p>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-left mb-6">
            <h3 class="font-bold text-red-900 mb-2">What to do?</h3>
            <p class="text-sm text-red-800">
                Please contact our support team to resolve this issue and reactivate your account.
            </p>
        </div>
        <a href="mailto:support@multivendor.test" class="inline-block bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg">
            <i class="fas fa-envelope mr-2"></i>Contact Support
        </a>
    </div>
</div>
@endsection
