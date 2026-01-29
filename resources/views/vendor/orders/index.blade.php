@extends('layouts.vendor')

@section('title', 'الطلبات')
@section('page-title', 'إدارة الطلبات')

@section('content')
<div class="space-y-6">
    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-lg shadow text-center">
            <div class="text-2xl font-bold text-gray-800">{{ $stats['all'] }}</div>
            <div class="text-sm text-gray-600">الإجمالي</div>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg shadow text-center">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
            <div class="text-sm text-gray-600">قيد الانتظار</div>
        </div>
        <div class="bg-blue-50 p-4 rounded-lg shadow text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['processing'] }}</div>
            <div class="text-sm text-gray-600">قيد التجهيز</div>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg shadow text-center">
            <div class="text-2xl font-bold text-purple-600">{{ $stats['shipped'] }}</div>
            <div class="text-sm text-gray-600">تم الشحن</div>
        </div>
        <div class="bg-green-50 p-4 rounded-lg shadow text-center">
            <div class="text-2xl font-bold text-green-600">{{ $stats['delivered'] }}</div>
            <div class="text-sm text-gray-600">تم التسليم</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="البحث في الطلبات..." class="flex-1 min-w-[200px] px-4 py-2 border rounded-lg">
            <select name="status" class="px-4 py-2 border rounded-lg">
                <option value="">كل الحالات</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>قيد التجهيز</option>
                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg">بحث</button>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الطلب</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">العميل</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المجموع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium">#{{ $order->order_number }}</div>
                            <div class="text-sm text-gray-500">{{ $order->orderItems->count() }} منتج</div>
                        </td>
                        <td class="px-6 py-4">
                            <div>{{ $order->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $order->created_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 font-bold">{{ number_format($order->orderItems->sum('subtotal'), 2) }} د.ت</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                @elseif($order->status === 'processing') bg-indigo-100 text-indigo-800
                                @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                @endif">
                                @if($order->status === 'pending') قيد الانتظار
                                @elseif($order->status === 'confirmed') مؤكد
                                @elseif($order->status === 'processing') قيد التجهيز
                                @elseif($order->status === 'shipped') تم الشحن
                                @elseif($order->status === 'delivered') تم التسليم
                                @else {{ $order->status }}
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('vendor.orders.show', $order) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i> عرض
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-shopping-cart text-4xl mb-3"></i>
                            <p>لا توجد طلبات بعد</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="bg-white rounded-lg shadow-md p-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
