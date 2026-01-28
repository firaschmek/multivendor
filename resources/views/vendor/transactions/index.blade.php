@extends('layouts.vendor')

@section('title', 'الأرباح والمعاملات')
@section('page-title', 'الأرباح والمعاملات')

@section('content')
<div class="space-y-6">
    <!-- ملخص الرصيد -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm opacity-90">الرصيد الحالي</h4>
                <i class="fas fa-wallet text-2xl opacity-75"></i>
            </div>
            <div class="text-3xl font-bold">
                {{ number_format($totals['current_balance'], 2) }} د.ت
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm text-gray-600">إجمالي المبيعات</h4>
                <i class="fas fa-chart-line text-blue-500 text-xl"></i>
            </div>
            <div class="text-2xl font-bold text-gray-800">
                {{ number_format($totals['total_sales'], 2) }} د.ت
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm text-gray-600">العمولات المدفوعة</h4>
                <i class="fas fa-percentage text-orange-500 text-xl"></i>
            </div>
            <div class="text-2xl font-bold text-gray-800">
                {{ number_format($totals['total_commission'], 2) }} د.ت
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm text-gray-600">عمليات السحب</h4>
                <i class="fas fa-money-bill-wave text-purple-500 text-xl"></i>
            </div>
            <div class="text-2xl font-bold text-gray-800">
                {{ number_format($totals['total_withdrawals'], 2) }} د.ت
            </div>
        </div>
    </div>

    <!-- طلب سحب -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-800 mb-1">طلب سحب أرباح</h3>
                <p class="text-sm text-gray-600">
                    الحد الأدنى للسحب هو 50 د.ت
                </p>
            </div>
            <a href="{{ route('vendor.transactions.withdrawal.request') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition">
                <i class="fas fa-download mr-2"></i>طلب سحب
            </a>
        </div>
    </div>

    <!-- الفلاتر -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <select name="type" class="px-4 py-2 border rounded-lg">
                <option value="">كل الأنواع</option>
                <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>مبيعات</option>
                <option value="commission" {{ request('type') == 'commission' ? 'selected' : '' }}>عمولة</option>
                <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>سحب</option>
            </select>

            <select name="status" class="px-4 py-2 border rounded-lg">
                <option value="">كل الحالات</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتملة</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-4 py-2 border rounded-lg">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-4 py-2 border rounded-lg">

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                تصفية
            </button>
            <a href="{{ route('vendor.transactions.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                إعادة تعيين
            </a>
        </form>
    </div>

    <!-- جدول المعاملات -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">النوع</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">الوصف</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">الرصيد</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">الحالة</th>
            </tr>
            </thead>
            <tbody class="divide-y">
            @forelse($transactions as $transaction)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm">
                    {{ $transaction->created_at->format('d M Y, H:i') }}
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded-full
                        @if($transaction->type == 'sale') bg-green-100 text-green-800
                        @elseif($transaction->type == 'commission') bg-orange-100 text-orange-800
                        @else bg-purple-100 text-purple-800
                        @endif">
                        @switch($transaction->type)
                            @case('sale') مبيعات @break
                            @case('commission') عمولة @break
                            @case('withdrawal') سحب @break
                            @default {{ $transaction->type }}
                        @endswitch
                    </span>
                </td>
                <td class="px-6 py-4 text-sm">
                    {{ $transaction->description ?? '-' }}
                    @if($transaction->order)
                    <a href="{{ route('vendor.orders.show', $transaction->order) }}"
                       class="text-blue-600 hover:text-blue-800 ml-2">
                        #{{ $transaction->order->order_number }}
                    </a>
                    @endif
                </td>
                <td class="px-6 py-4 font-bold {{ $transaction->amount < 0 ? 'text-red-600' : 'text-green-600' }}">
                    {{ $transaction->amount < 0 ? '-' : '+' }}
                    {{ number_format(abs($transaction->amount), 2) }} د.ت
                </td>
                <td class="px-6 py-4 text-sm">
                    {{ number_format($transaction->balance_after, 2) }} د.ت
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded-full
                        {{ $transaction->status == 'completed'
                            ? 'bg-green-100 text-green-800'
                            : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $transaction->status == 'completed' ? 'مكتملة' : 'قيد الانتظار' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-receipt text-4xl mb-3 opacity-50"></i>
                    <p>لا توجد معاملات بعد</p>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- التصفح -->
    @if($transactions->hasPages())
    <div class="bg-white rounded-lg shadow-md p-4">
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection
