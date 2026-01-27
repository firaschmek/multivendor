@extends('layouts.admin')

@section('title', 'Customers')
@section('page-title', 'Customers Management')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customers..." class="flex-1 px-4 py-2 border rounded-lg">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                Search
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Orders</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y">
            @forelse($customers as $customer)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-medium">{{ $customer->name }}</td>
                <td class="px-6 py-4 text-sm">{{ $customer->email }}</td>
                <td class="px-6 py-4 text-sm">{{ $customer->phone ?? '-' }}</td>
                <td class="px-6 py-4 text-sm">{{ $customer->orders_count }}</td>
                <td class="px-6 py-4 text-sm">{{ $customer->created_at->format('d M Y') }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.customers.show', $customer) }}" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-eye"></i> View
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">No customers</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($customers->hasPages())
    <div class="bg-white rounded-lg shadow-md p-4">
        {{ $customers->links() }}
    </div>
    @endif
</div>
@endsection
