@extends('layouts.vendor')

@section('title', 'تفاصيل الطلب')
@section('page-title', 'الطلب رقم #' . $order->order_number)

@section('content')
<div class="space-y-6">
    <!-- العنوان -->
    <div class="flex justify-between items-center">
        <a href="{{ route('vendor.orders.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>العودة إلى الطلبات
        </a>
        <a href="{{ route('vendor.orders.invoice', $order) }}" target="_blank" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
            <i class="fas fa-print mr-2"></i>طباعة الفاتورة
        </a>
    </div>

    <!-- ملخص الطلب -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- عناصر الطلب -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">عناصر الطلب</h3>
                </div>
                <div class="divide-y">
                    @foreach($order->orderItems as $item)
                    <div class="p-6 flex gap-4">
                        @if($item->product && $item->product->images->first())
                        <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" class="w-20 h-20 rounded object-cover">
                        @else
                        <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800">{{ $item->product_name }}</h4>
                            <p class="text-sm text-gray-600">رمز المنتج (SKU): {{ $item->product_sku }}</p>
                            <p class="text-sm text-gray-600">السعر: {{ number_format($item->price, 2) }} د.ت</p>
                            <p class="text-sm text-gray-600">الكمية: {{ $item->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-gray-800">{{ number_format($item->subtotal, 2) }} د.ت</div>
                            <div class="text-sm text-gray-500">العمولة: {{ number_format($item->commission_amount, 2) }} د.ت</div>
                            <div class="text-sm text-green-600 font-medium">ربحك: {{ number_format($item->vendor_amount, 2) }} د.ت</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="p-6 border-t bg-gray-50">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">المجموع الفرعي:</span>
                        <span class="font-bold">{{ number_format($order->orderItems->sum('subtotal'), 2) }} د.ت</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">العمولة:</span>
                        <span class="text-red-600">-{{ number_format($order->orderItems->sum('commission_amount'), 2) }} د.ت</span>
                    </div>
                    <div class="flex justify-between items-center text-lg font-bold text-green-600 pt-2 border-t">
                        <span>إجمالي أرباحك:</span>
                        <span>{{ number_format($vendorTotal, 2) }} د.ت</span>
                    </div>
                </div>
            </div>

            <!-- تحديث حالة الطلب -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">تحديث حالة الطلب</h3>
                <form action="{{ route('vendor.orders.update-status', $order) }}" method="POST" class="flex gap-4">
                    @csrf
                    <select name="status" class="flex-1 px-4 py-2 border rounded-lg" required>
                        <option value="">اختر الحالة</option>
                        @if($order->status == 'pending')
                        <option value="confirmed">تأكيد الطلب</option>
                        @elseif($order->status == 'confirmed')
                        <option value="processing">بدء المعالجة</option>
                        @elseif($order->status == 'processing')
                        <option value="shipped">تحديد كتم الشحن</option>
                        @elseif($order->status == 'shipped')
                        <option value="delivered">تحديد كتم التسليم</option>
                        @endif
                    </select>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                        تحديث الحالة
                    </button>
                </form>

                <!-- رقم التتبع -->
                @if($order->status == 'processing' || $order->status == 'shipped')
                <form action="{{ route('vendor.orders.update-tracking', $order) }}" method="POST" class="flex gap-4 mt-4">
                    @csrf
                    <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" placeholder="رقم التتبع" class="flex-1 px-4 py-2 border rounded-lg">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                        تحديث التتبع
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- معلومات الطلب -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">معلومات الطلب</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-600">رقم الطلب</label>
                        <div class="font-medium">#{{ $order->order_number }}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">التاريخ</label>
                        <div class="font-medium">{{ $order->created_at->format('d M Y, H:i') }}</div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">الحالة</label>
                        <div>
                            <span class="px-2 py-1 text-xs rounded-full inline-block
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                @endif">
                                @switch($order->status)
                                    @case('pending') قيد الانتظار @break
                                    @case('processing') قيد المعالجة @break
                                    @case('shipped') تم الشحن @break
                                    @case('delivered') تم التسليم @break
                                    @default {{ $order->status }}
                                @endswitch
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">الدفع</label>
                        <div class="font-medium">{{ strtoupper($order->payment_method) }}</div>
                        <span class="px-2 py-1 text-xs rounded-full inline-block mt-1
                            {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $order->payment_status == 'paid' ? 'مدفوع' : 'غير مدفوع' }}
                        </span>
                    </div>
                    @if($order->tracking_number)
                    <div>
                        <label class="text-sm text-gray-600">رقم التتبع</label>
                        <div class="font-medium">{{ $order->tracking_number }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">العميل</h3>
                <div class="space-y-2">
                    <div class="font-medium">{{ $order->user->name }}</div>
                    <div class="text-sm text-gray-600">{{ $order->user->email }}</div>
                    <div class="text-sm text-gray-600">{{ $order->user->phone }}</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">عنوان الشحن</h3>
                <div class="text-sm text-gray-600">
                    <div>{{ $order->shipping_address }}</div>
                    <div>{{ $order->shipping_city }}, {{ $order->shipping_postal_code }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
