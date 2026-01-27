@extends('layouts.vendor')

@section('title', 'Earnings & Transactions')
@section('page-title', 'Earnings & Transactions')

@section('content')
<div class="space-y-6">
    <!-- Balance Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm opacity-90">Current Balance</h4>
                <i class="fas fa-wallet text-2xl opacity-75"></i>
            </div>
            <div class="text-3xl font-bold">{{ number_format($totals['current_balance'], 2) }} TND</div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm text-gray-600">Total Sales</h4>
                <i class="fas fa-chart-line text-blue-500 text-xl"></i>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($totals['total_sales'], 2) }} TND</div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm text-gray-600">Commission Paid</h4>
                <i class="fas fa-percentage text-orange-500 text-xl"></i>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($totals['total_commission'], 2) }} TND</div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm text-gray-600">Withdrawals</h4>
                <i class="fas fa-money-bill-wave text-purple-500 text-xl"></i>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($totals['total_withdrawals'], 2) }} TND</div>
        </div>
    </div>

    <!-- Withdrawal Button -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-800 mb-1">Request Withdrawal</h3>
                <p class="text-sm text-gray-600">Minimum withdrawal amount is 50 TND</p>
            </div>
            <a href="{{ route('vendor.transactions.withdrawal.request') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition">
                <i class="fas fa-download mr-2"></i>Request Withdrawal
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <select name="type" class="px-4 py-2 border rounded-lg">
                <option value="">All Types</option>
                <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>Sales</option>
                <option value="commission" {{ request('type') == 'commission' ? 'selected' : '' }}>Commission</option>
                <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>Withdrawals</option>
            </select>

            <select name="status" class="px-4 py-2 border rounded-lg">
                <option value="">All Status</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-4 py-2 border rounded-lg">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-4 py-2 border rounded-lg">

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                Filter
            </button>
            <a href="{{ route('vendor.transactions.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                Reset
            </a>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Balance</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            </tr>
            </thead>
            <tbody class="divide-y">
            @forelse($transactions as $transaction)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm">{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($transaction->type == 'sale') bg-green-100 text-green-800
                                @elseif($transaction->type == 'commission') bg-orange-100 text-orange-800
                                @else bg-purple-100 text-purple-800
                                @endif">
                                {{ ucfirst($transaction->type) }}
                            </span>
                </td>
                <td class="px-6 py-4 text-sm">
                    {{ $transaction->description ?? '-' }}
                    @if($transaction->order)
                    <a href="{{ route('vendor.orders.show', $transaction->order) }}" class="text-blue-600 hover:text-blue-800 ml-2">
                        #{{ $transaction->order->order_number }}
                    </a>
                    @endif
                </td>
                <td class="px-6 py-4 font-bold {{ $transaction->amount < 0 ? 'text-red-600' : 'text-green-600' }}">
                    {{ $transaction->amount < 0 ? '-' : '+' }}{{ number_format(abs($transaction->amount), 2) }} TND
                </td>
                <td class="px-6 py-4 text-sm">{{ number_format($transaction->balance_after, 2) }} TND</td>
                <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $transaction->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-receipt text-4xl mb-3 opacity-50"></i>
                    <p>No transactions yet</p>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($transactions->hasPages())
    <div class="bg-white rounded-lg shadow-md p-4">
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection
