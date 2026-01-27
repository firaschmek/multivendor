@extends('layouts.admin')

@section('title', 'Payouts')
@section('page-title', 'Withdrawal Requests')

@section('content')
<div class="space-y-6">
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-yellow-50 p-6 rounded-lg shadow">
            <div class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
            <div class="text-gray-600">Pending Requests</div>
            <div class="text-sm text-gray-500 mt-2">{{ number_format(abs($stats['pending_amount']), 2) }} TND</div>
        </div>
        <div class="bg-green-50 p-6 rounded-lg shadow">
            <div class="text-3xl font-bold text-green-600">{{ $stats['completed'] }}</div>
            <div class="text-gray-600">Completed</div>
            <div class="text-sm text-gray-500 mt-2">{{ number_format(abs($stats['completed_amount']), 2) }} TND</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <select name="status" class="px-4 py-2 border rounded-lg">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>

            <select name="vendor" class="px-4 py-2 border rounded-lg">
                <option value="">All Vendors</option>
                @foreach($vendors as $vendor)
                <option value="{{ $vendor->id }}" {{ request('vendor') == $vendor->id ? 'selected' : '' }}>
                {{ $vendor->shop_name }}
                </option>
                @endforeach
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-4 py-2 border rounded-lg">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-4 py-2 border rounded-lg">

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">Filter</button>
            <a href="{{ route('admin.payouts.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">Reset</a>
        </form>
    </div>

    <!-- Withdrawals Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y">
            @forelse($withdrawals as $withdrawal)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="font-medium">{{ $withdrawal->vendor->shop_name }}</div>
                    <div class="text-sm text-gray-500">{{ $withdrawal->vendor->user->email }}</div>
                </td>
                <td class="px-6 py-4 font-bold text-red-600">{{ number_format(abs($withdrawal->amount), 2) }} TND</td>
                <td class="px-6 py-4 text-sm">{{ $withdrawal->created_at->format('d M Y, H:i') }}</td>
                <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($withdrawal->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($withdrawal->status === 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($withdrawal->status) }}
                            </span>
                </td>
                <td class="px-6 py-4">
                    @if($withdrawal->status === 'pending')
                    <div class="flex gap-2">
                        <form action="{{ route('admin.payouts.approve', $withdrawal) }}" method="POST" onsubmit="return confirm('Approve this withdrawal?')">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-800" title="Approve">
                                <i class="fas fa-check-circle"></i>
                            </button>
                        </form>
                        <a href="{{ route('admin.payouts.show', $withdrawal) }}" class="text-blue-600 hover:text-blue-800" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                    @else
                    <a href="{{ route('admin.payouts.show', $withdrawal) }}" class="text-blue-600 hover:text-blue-800" title="View Details">
                        <i class="fas fa-eye"></i>
                    </a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-gray-500">No withdrawal requests</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($withdrawals->hasPages())
    <div class="bg-white rounded-lg shadow-md p-4">
        {{ $withdrawals->links() }}
    </div>
    @endif
</div>
@endsection
