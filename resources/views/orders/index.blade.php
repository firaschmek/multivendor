@extends('layouts.app')

@section('title', 'طلباتي')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">طلباتي</h1>

    @if($orders->count() > 0)
    <div class="space-y-4">
        @foreach($orders as $order)
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex flex-wrap justify-between items-start gap-4 mb-4">
                <div>
                    <div class="text-sm text-gray-600">رقم الطلب</div>
                    <div class="font-bold text-lg">{{ $order->order_number }}</div>
                    <div class="text-sm text-gray-600 mt-1">{{ $order->created_at->format('Y/m/d H:i') }}</div>
                </div>

                <div>
                    <div class="text-sm text-gray-600">المبلغ الإجمالي</div>
                    <div class="font-bold text-xl text-blue-600">{{ number_format($order->total, 2) }} دت</div>
                </div>

                <div>
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
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$order->status] }}">
                        {{ $statusLabels[$order->status] }}
                    </span>
                </div>
            </div>

            <!-- Order Items Preview -->
            <div class="border-t pt-4 mb-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($order->items->take(3) as $item)
                    <div class="flex gap-2 items-center">
                        @if($item->product->primaryImage ?? false)
                            <img src="{{ $item->product->primaryImage->getThumbnailUrl() }}" 
                                 alt="{{ $item->product_name_ar }}"
                                 class="w-12 h-12 object-cover rounded">
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-semibold truncate">{{ $item->product_name_ar }}</div>
                            <div class="text-xs text-gray-600">الكمية: {{ $item->quantity }}</div>
                        </div>
                    </div>
                    @endforeach
                    
                    @if($order->items->count() > 3)
                    <div class="text-sm text-gray-600 flex items-center">
                        +{{ $order->items->count() - 3 }} منتج آخر
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 border-t pt-4">
                <a href="{{ route('orders.show', $order->id) }}" 
                   class="flex-1 sm:flex-none bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium text-center">
                    عرض التفاصيل
                </a>
                
                @if($order->canBeCancelled())
                <form method="POST" action="{{ route('orders.cancel', $order->id) }}" 
                      onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')">
                    @csrf
                    <button type="submit" class="border border-red-500 text-red-500 px-6 py-2 rounded-lg hover:bg-red-50 font-medium">
                        إلغاء الطلب
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $orders->links() }}
    </div>
    @else
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
        </svg>
        <h2 class="text-2xl font-bold mb-4">لا توجد طلبات</h2>
        <p class="text-gray-600 mb-6">لم تقم بأي طلبات بعد</p>
        <a href="{{ route('products.index') }}" 
           class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-bold">
            تصفح المنتجات
        </a>
    </div>
    @endif
</div>
@endsection
