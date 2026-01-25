@extends('layouts.app')

@section('title', 'تفاصيل الطلب ' . $order->order_number)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            → العودة إلى الطلبات
        </a>
        <h1 class="text-3xl font-bold">طلب رقم: {{ $order->order_number }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">حالة الطلب</h2>
                
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'confirmed' => 'bg-blue-100 text-blue-800',
                        'processing' => 'bg-purple-100 text-purple-800',
                        'shipped' => 'bg-indigo-100 text-indigo-800',
                        'delivered' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                    ];
                    $statusLabels = [
                        'pending' => 'قيد الانتظار',
                        'confirmed' => 'مؤكد',
                        'processing' => 'قيد المعالجة',
                        'shipped' => 'تم الشحن',
                        'delivered' => 'تم التوصيل',
                        'cancelled' => 'ملغى',
                    ];
                @endphp

                <div class="flex items-center gap-3 mb-4">
                    <span class="px-4 py-2 rounded-full text-lg font-medium {{ $statusColors[$order->status] }}">
                        {{ $statusLabels[$order->status] }}
                    </span>
                </div>

                <!-- Timeline -->
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white">✓</div>
                        <div class="flex-1">
                            <div class="font-semibold">تم إنشاء الطلب</div>
                            <div class="text-sm text-gray-600">{{ $order->created_at->format('Y/m/d H:i') }}</div>
                        </div>
                    </div>

                    @if($order->confirmed_at)
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white">✓</div>
                        <div class="flex-1">
                            <div class="font-semibold">تم تأكيد الطلب</div>
                            <div class="text-sm text-gray-600">{{ $order->confirmed_at->format('Y/m/d H:i') }}</div>
                        </div>
                    </div>
                    @endif

                    @if($order->shipped_at)
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white">✓</div>
                        <div class="flex-1">
                            <div class="font-semibold">تم الشحن</div>
                            <div class="text-sm text-gray-600">{{ $order->shipped_at->format('Y/m/d H:i') }}</div>
                            @if($order->tracking_number)
                            <div class="text-sm text-blue-600">رقم التتبع: {{ $order->tracking_number }}</div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($order->delivered_at)
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white">✓</div>
                        <div class="flex-1">
                            <div class="font-semibold">تم التوصيل</div>
                            <div class="text-sm text-gray-600">{{ $order->delivered_at->format('Y/m/d H:i') }}</div>
                        </div>
                    </div>
                    @endif

                    @if($order->cancelled_at)
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-500 flex items-center justify-center text-white">✗</div>
                        <div class="flex-1">
                            <div class="font-semibold">تم إلغاء الطلب</div>
                            <div class="text-sm text-gray-600">{{ $order->cancelled_at->format('Y/m/d H:i') }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">المنتجات</h2>
                
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex gap-4 pb-4 border-b last:border-b-0">
                        <div class="flex-shrink-0">
                            @if($item->product->primaryImage ?? false)
                                <img src="{{ $item->product->primaryImage->getThumbnailUrl() }}" 
                                     alt="{{ $item->product_name_ar }}"
                                     class="w-20 h-20 object-cover rounded">
                            @else
                                <div class="w-20 h-20 bg-gray-200 rounded"></div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <div class="font-bold">{{ $item->product_name_ar }}</div>
                            <div class="text-sm text-gray-600">البائع: {{ $item->vendor->shop_name_ar }}</div>
                            <div class="text-sm text-gray-600 mt-2">الكمية: {{ $item->quantity }}</div>
                        </div>

                        <div class="text-left">
                            <div class="font-bold text-blue-600">{{ number_format($item->subtotal, 2) }} دت</div>
                            <div class="text-sm text-gray-600">{{ number_format($item->price, 2) }} دت × {{ $item->quantity }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">عنوان الشحن</h2>
                <div class="text-gray-700">
                    <div class="font-semibold">{{ $order->shipping_name }}</div>
                    <div>{{ $order->shipping_phone }}</div>
                    @if($order->shipping_email)
                    <div>{{ $order->shipping_email }}</div>
                    @endif
                    <div class="mt-2">{{ $order->shipping_address }}</div>
                    <div>{{ $order->shipping_city }}, {{ $order->shipping_postal_code }}</div>
                </div>
            </div>

            @if($order->customer_notes)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">ملاحظات</h2>
                <div class="text-gray-700">{{ $order->customer_notes }}</div>
            </div>
            @endif
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                <h2 class="text-xl font-bold mb-4">ملخص الطلب</h2>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">المجموع الفرعي</span>
                        <span class="font-semibold">{{ number_format($order->subtotal, 2) }} دت</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">الشحن</span>
                        <span class="font-semibold">{{ number_format($order->shipping_cost, 2) }} دت</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">الضريبة</span>
                        <span class="font-semibold">{{ number_format($order->tax, 2) }} دت</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>الخصم</span>
                        <span class="font-semibold">-{{ number_format($order->discount, 2) }} دت</span>
                    </div>
                    @endif
                    <div class="border-t pt-3 flex justify-between text-lg">
                        <span class="font-bold">المجموع الإجمالي</span>
                        <span class="font-bold text-blue-600">{{ number_format($order->total, 2) }} دت</span>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="border-t pt-4 mb-4">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">طريقة الدفع</span>
                        <span class="font-semibold">
                            @if($order->payment_method == 'cash_on_delivery')
                                الدفع عند الاستلام
                            @elseif($order->payment_method == 'credit_card')
                                بطاقة الائتمان
                            @else
                                {{ $order->payment_method }}
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">حالة الدفع</span>
                        <span class="font-semibold {{ $order->isPaid() ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $order->isPaid() ? 'مدفوع' : 'غير مدفوع' }}
                        </span>
                    </div>
                </div>

                <!-- Actions -->
                @if($order->canBeCancelled())
                <form method="POST" action="{{ route('orders.cancel', $order->id) }}" 
                      onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')">
                    @csrf
                    <button type="submit" class="w-full border border-red-500 text-red-500 py-2 rounded-lg hover:bg-red-50 font-medium">
                        إلغاء الطلب
                    </button>
                </form>
                @endif

                <!-- Contact Support -->
                <div class="mt-6 pt-6 border-t text-center">
                    <div class="text-sm text-gray-600 mb-2">تحتاج مساعدة؟</div>
                    <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">
                        اتصل بنا
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
