@extends('layouts.vendor')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">إجمالي المنتجات</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $stats['total_products'] }}</h3>
                    <p class="text-sm text-green-600 mt-2">
                        <i class="fas fa-check-circle"></i> {{ $stats['active_products'] }} نشط
                    </p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-box text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">إجمالي الطلبات</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $stats['total_orders'] }}</h3>
                    <p class="text-sm text-yellow-600 mt-2">
                        <i class="fas fa-clock"></i> {{ $stats['pending_orders'] }} قيد الانتظار
                    </p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">إجمالي الإيرادات</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_revenue'], 0) }}</h3>
                    <p class="text-sm text-gray-500 mt-2">دينار تونسي</p>
                </div>
                <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- Current Balance -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">الرصيد الحالي</p>
                    <h3 class="text-3xl font-bold text-green-600">{{ number_format($stats['current_balance'], 2) }}</h3>
                    <p class="text-sm text-gray-500 mt-2">دينار تونسي</p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-wallet text-2xl text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Monthly Stats -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold">هذا الشهر</h4>
                <i class="fas fa-calendar-alt text-2xl opacity-75"></i>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>الطلبات:</span>
                    <span class="font-bold">{{ $stats['monthly_orders'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span>الإيرادات:</span>
                    <span class="font-bold">{{ number_format($stats['monthly_revenue'], 0) }} دينار تونسي</span>
                </div>
            </div>
        </div>

        <!-- Stock Alerts -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold">تنبيهات المخزون</h4>
                <i class="fas fa-exclamation-triangle text-2xl opacity-75"></i>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>نفد من المخزون:</span>
                    <span class="font-bold">{{ $stats['out_of_stock'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span>مخزون منخفض:</span>
                    <span class="font-bold">{{ $stats['low_stock'] }}</span>
                </div>
            </div>
        </div>

        <!-- Commission -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold">العمولة</h4>
                <i class="fas fa-percentage text-2xl opacity-75"></i>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>النسبة:</span>
                    <span class="font-bold">{{ $vendor->commission_rate }}%</span>
                </div>
                <div class="flex justify-between">
                    <span>إجمالي المدفوع:</span>
                    <span class="font-bold">{{ number_format($stats['total_commission'], 0) }} دينار تونسي</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">أحدث الطلبات</h3>
                <a href="{{ route('vendor.orders.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    عرض الكل <i class="fas fa-arrow-left ml-1"></i>
                </a>
            </div>
            <div class="p-6">
                @forelse($recent_orders as $order)
                <div class="flex items-center justify-between py-3 border-b last:border-b-0">
                    <div class="flex-1">
                        <div class="font-medium text-gray-800">#{{ $order->order_number }}</div>
                        <div class="text-sm text-gray-600">{{ $order->user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-gray-800">{{ number_format($order->orderItems->sum('subtotal'), 2) }} دينار تونسي</div>
                        <span class="inline-block px-2 py-1 text-xs rounded-full
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-shopping-cart text-4xl mb-3 opacity-50"></i>
                    <p>لا توجد طلبات بعد</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">تنبيه انخفاض المخزون</h3>
                <a href="{{ route('vendor.products.index') }}?stock=low" class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                    عرض الكل <i class="fas fa-arrow-left ml-1"></i>
                </a>
            </div>
            <div class="p-6">
                @forelse($low_stock_products as $product)
                <div class="flex items-center justify-between py-3 border-b last:border-b-0">
                    <div class="flex items-center gap-3 flex-1">
                        @if($product->images->first())
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                             alt="{{ $product->name }}"
                             class="w-12 h-12 rounded object-cover">
                        @else
                        <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                        @endif
                        <div>
                            <div class="font-medium text-gray-800">{{ $product->name }}</div>
                            <div class="text-sm text-gray-600">رمز المنتج: {{ $product->sku }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold text-orange-600">{{ $product->quantity }}</div>
                        <div class="text-xs text-gray-500">في المخزون</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-check-circle text-4xl mb-3 text-green-500"></i>
                    <p>جميع المنتجات متوفرة بكمية كافية</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b">
            <h3 class="text-lg font-bold text-gray-800">المنتجات الأكثر مبيعًا</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">المنتج</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">السعر</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">المخزون</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">المبيعات</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">الحالة</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @forelse($top_products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($product->images->first())
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                 alt="{{ $product->name }}"
                                 class="w-10 h-10 rounded object-cover">
                            @else
                            <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                                <i class="fas fa-image text-gray-400"></i>
                            </div>
                            @endif
                            <div>
                                <div class="font-medium text-gray-800">{{ $product->name }}</div>
                                <div class="text-sm text-gray-500">{{ $product->sku }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-800 font-medium">{{ number_format($product->price, 2) }} دينار تونسي</td>
                    <td class="px-6 py-4">
                        <span class="text-gray-800 font-medium">{{ $product->quantity }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-blue-600 font-bold">{{ $product->sales_count ?? 0 }}</span>
                    </td>
                    <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $product->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-box text-4xl mb-3 opacity-50"></i>
                        <p>لا توجد منتجات بعد</p>
                        <a href="{{ route('vendor.products.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                            أنشئ أول منتج لك
                        </a>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">إجراءات سريعة</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('vendor.products.create') }}" class="bg-blue-50 hover:bg-blue-100 text-blue-700 p-4 rounded-lg text-center transition">
                <i class="fas fa-plus-circle text-3xl mb-2"></i>
                <div class="font-medium">إضافة منتج</div>
            </a>
            <a href="{{ route('vendor.orders.index') }}" class="bg-green-50 hover:bg-green-100 text-green-700 p-4 rounded-lg text-center transition">
                <i class="fas fa-shopping-cart text-3xl mb-2"></i>
                <div class="font-medium">عرض الطلبات</div>
            </a>
            <a href="{{ route('vendor.shop.edit') }}" class="bg-purple-50 hover:bg-purple-100 text-purple-700 p-4 rounded-lg text-center transition">
                <i class="fas fa-store text-3xl mb-2"></i>
                <div class="font-medium">إعدادات المتجر</div>
            </a>
            <a href="{{ route('vendor.transactions.index') }}" class="bg-orange-50 hover:bg-orange-100 text-orange-700 p-4 rounded-lg text-center transition">
                <i class="fas fa-wallet text-3xl mb-2"></i>
                <div class="font-medium">الأرباح</div>
            </a>
        </div>
    </div>
</div>
@endsection
