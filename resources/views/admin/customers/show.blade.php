@extends('layouts.admin')

@section('title', 'Customer Details')
@section('page-title', $customer->name)

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.customers.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Customers
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-3xl font-bold text-blue-600">{{ $stats['total_orders'] }}</div>
            <div class="text-gray-600">Total Orders</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-3xl font-bold text-green-600">{{ number_format($stats['total_spent'], 2) }}</div>
            <div class="text-gray-600">Total Spent (TND)</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-3xl font-bold text-yellow-600">{{ $stats['pending_orders'] }}</div>
            <div class="text-gray-600">Pending Orders</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <div class="text-3xl font-bold text-red-600">{{ $stats['cancelled_orders'] }}</div>
            <div class="text-gray-600">Cancelled</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Customer Information</h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm text-gray-600">Name</label>
                    <div class="font-medium">{{ $customer->name }}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Email</label>
                    <div class="font-medium">{{ $customer->email }}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Phone</label>
                    <div class="font-medium">{{ $customer->phone ?? '-' }}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Joined</label>
                    <div class="font-medium">{{ $customer->created_at->format('d M Y') }}</div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">Recent Orders</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y">
                        @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-800">
                                    #{{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 font-medium">{{ number_format($order->total, 2) }} TND</td>
                            <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded-full
                                            @if($order->status === 'delivered') bg-green-100 text-green-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">No orders yet</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
