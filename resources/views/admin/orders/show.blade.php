@extends('layouts.admin')

@section('title', 'Order Details')
@section('page-title', 'Order #' . $order->order_number)

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Orders
        </a>
        <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank"
           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
            <i class="fas fa-print mr-2"></i>Print Invoice
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">Order Items</h3>
                </div>
                <div class="divide-y">
                    @foreach($order->orderItems as $item)
                    <div class="p-6 flex gap-4">
                        @if($item->product && $item->product->images->first())
                        <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}"
                             class="w-20 h-20 rounded object-cover">
                        @else
                        <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800">{{ $item->product_name }}</h4>
                            <p class="text-sm text-gray-600">SKU: {{ $item->product_sku }}</p>
                            <p class="text-sm text-gray-600">Vendor: {{ $item->product->vendor->shop_name }}</p>
                            <p class="text-sm text-gray-600">Price: {{ number_format($item->price, 2) }} TND</p>
                            <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-gray-800">{{ number_format($item->subtotal, 2) }} TND</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="p-6 border-t bg-gray-50">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium">{{ number_format($order->subtotal, 2) }} TND</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Shipping:</span>
                        <span class="font-medium">{{ number_format($order->shipping_cost, 2) }} TND</span>
                    </div>
                    @if($order->tax > 0)
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Tax:</span>
                        <span class="font-medium">{{ number_format($order->tax, 2) }} TND</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center text-lg font-bold pt-2 border-t">
                        <span>Total:</span>
                        <span>{{ number_format($order->total, 2) }} TND</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Update Order</h3>

                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="flex gap-4">
                        <select name="status" class="flex-1 px-4 py-2 border rounded-lg" required>
                            <option value="">Select Status</option>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Update Status
                        </button>
                    </div>
                </form>

                <form action="{{ route('admin.orders.update-payment', $order) }}" method="POST">
                    @csrf
                    <div class="flex gap-4">
                        <select name="payment_status" class="flex-1 px-4 py-2 border rounded-lg" required>
                            <option value="">Select Payment Status</option>
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                            Update Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Order Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-600">Order Number</label>
                        <div class="font-medium">#{{ $order->order_number }}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Date</label>
                        <div class="font-medium">{{ $order->created_at->format('d M Y, H:i') }}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Status</label>
                        <div>
                            <span class="px-2 py-1 text-xs rounded-full inline-block
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Payment</label>
                        <div class="font-medium">{{ strtoupper($order->payment_method) }}</div>
                        <span class="px-2 py-1 text-xs rounded-full inline-block mt-1
                            {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    @if($order->tracking_number)
                    <div>
                        <label class="text-sm text-gray-600">Tracking Number</label>
                        <div class="font-medium">{{ $order->tracking_number }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Customer</h3>
                <div class="space-y-2">
                    <div class="font-medium">{{ $order->user->name }}</div>
                    <div class="text-sm text-gray-600">{{ $order->user->email }}</div>
                    <div class="text-sm text-gray-600">{{ $order->user->phone }}</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Shipping Address</h3>
                <div class="text-sm text-gray-600">
                    <div>{{ $order->shipping_address }}</div>
                    <div>{{ $order->shipping_city }}, {{ $order->shipping_postal_code }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
