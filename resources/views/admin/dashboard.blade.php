@extends('layouts.admin')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Vendors -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">إجمالي البائعين</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ \App\Models\Vendor::count() }}</h3>
                    <p class="text-sm text-green-600 mt-2">
                        <i class="fas fa-check-circle"></i> {{ \App\Models\Vendor::where('status', 'approved')->count() }} نشط
                    </p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-store text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">إجمالي المنتجات</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ \App\Models\Product::count() }}</h3>
                    <p class="text-sm text-green-600 mt-2">
                        <i class="fas fa-check-circle"></i> {{ \App\Models\Product::where('is_active', true)->count() }} نشط
                    </p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-box text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">إجمالي الطلبات</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ \App\Models\Order::count() }}</h3>
                    <p class="text-sm text-yellow-600 mt-2">
                        <i class="fas fa-clock"></i> {{ \App\Models\Order::where('status', 'pending')->count() }} قيد الانتظار
                    </p>
                </div>
                <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">إجمالي الإيرادات</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format(\App\Models\Order::where('payment_status', 'paid')->sum('total'), 0) }}</h3>
                    <p class="text-sm text-gray-500 mt-2">د.ت</p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-2xl text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">إجراءات سريعة</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.vendors.index') }}" class="bg-blue-50 hover:bg-blue-100 text-blue-700 p-4 rounded-lg text-center transition">
                <i class="fas fa-store text-3xl mb-2"></i>
                <div class="font-medium">إدارة البائعين</div>
            </a>
            <a href="{{ route('admin.vendors.index') }}?status=pending" class="bg-yellow-50 hover:bg-yellow-100 text-yellow-700 p-4 rounded-lg text-center transition">
                <i class="fas fa-user-clock text-3xl mb-2"></i>
                <div class="font-medium">طلبات البائعين</div>
                @php
                $pending = \App\Models\Vendor::where('status', 'pending')->count();
                @endphp
                @if($pending > 0)
                <span class="inline-block bg-yellow-500 text-white text-xs px-2 py-1 rounded-full mt-1">{{ $pending }}</span>
                @endif
            </a>
            <a href="{{ route('admin.orders.index') }}" class="bg-green-50 hover:bg-green-100 text-green-700 p-4 rounded-lg text-center transition">
                <i class="fas fa-chart-line text-3xl mb-2"></i>
                <div class="font-medium">الطلبات</div>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="bg-purple-50 hover:bg-purple-100 text-purple-700 p-4 rounded-lg text-center transition">
                <i class="fas fa-tags text-3xl mb-2"></i>
                <div class="font-medium">الأقسام</div>
            </a>
        </div>
    </div>

    <!-- Recent Vendors -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">أحدث البائعين</h3>
            <a href="{{ route('admin.vendors.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                عرض الكل <i class="fas fa-arrow-left mr-1"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">البائع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">البريد</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">إجراءات</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @foreach(\App\Models\Vendor::with('user')->latest()->take(5)->get() as $vendor)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($vendor->logo)
                            <img src="{{ asset('storage/' . $vendor->logo) }}" class="w-10 h-10 rounded-full object-cover">
                            @else
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">
                                {{ substr($vendor->shop_name, 0, 1) }}
                            </div>
                            @endif
                            <div>
                                <div class="font-medium text-gray-800">{{ $vendor->shop_name_ar ?? $vendor->shop_name }}</div>
                                <div class="text-sm text-gray-500">{{ $vendor->user->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $vendor->user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($vendor->status === 'approved') bg-green-100 text-green-800
                            @elseif($vendor->status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            @if($vendor->status === 'approved') مفعّل
                            @elseif($vendor->status === 'pending') قيد الانتظار
                            @else معلّق
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $vendor->created_at->format('Y-m-d') }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.vendors.show', $vendor) }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-eye"></i> عرض
                        </a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
