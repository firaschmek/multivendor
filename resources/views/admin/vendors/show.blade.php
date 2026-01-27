@extends('layouts.admin')

@section('title', 'Vendor Details - ' . $vendor->shop_name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.vendors.index') }}" 
               class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $vendor->shop_name }}</h1>
                <p class="text-gray-600 mt-1">Vendor Details & Statistics</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.vendors.edit', $vendor) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Vendor
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Status Actions -->
    @if($vendor->status === 'pending')
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-yellow-800 font-medium">This vendor is pending approval</p>
                </div>
                <form action="{{ route('admin.vendors.approve', $vendor) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition"
                            onclick="return confirm('Approve this vendor?')">
                        Approve Vendor
                    </button>
                </form>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Shop Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Shop Information</h2>
                
                <div class="space-y-4">
                    @if($vendor->banner)
                        <div>
                            <img src="{{ asset('storage/' . $vendor->banner) }}" 
                                 alt="Shop Banner" 
                                 class="w-full h-48 object-cover rounded-lg">
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Shop Name</p>
                            <p class="font-medium">{{ $vendor->shop_name }}</p>
                        </div>
                        @if($vendor->shop_name_ar)
                            <div>
                                <p class="text-sm text-gray-600">Shop Name (Arabic)</p>
                                <p class="font-medium" dir="rtl">{{ $vendor->shop_name_ar }}</p>
                            </div>
                        @endif
                    </div>

                    @if($vendor->description)
                        <div>
                            <p class="text-sm text-gray-600">Description</p>
                            <p class="text-gray-800">{{ $vendor->description }}</p>
                        </div>
                    @endif

                    @if($vendor->description_ar)
                        <div>
                            <p class="text-sm text-gray-600">Description (Arabic)</p>
                            <p class="text-gray-800" dir="rtl">{{ $vendor->description_ar }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">City</p>
                            <p class="font-medium">{{ $vendor->city ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Country</p>
                            <p class="font-medium">{{ $vendor->country ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($vendor->address)
                        <div>
                            <p class="text-sm text-gray-600">Address</p>
                            <p class="text-gray-800">{{ $vendor->address }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Commission Rate</p>
                            <p class="font-medium text-lg">{{ $vendor->commission_rate }}%</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Current Balance</p>
                            <p class="font-medium text-lg">{{ number_format($vendor->balance, 2) }} TND</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Joined Date</p>
                        <p class="font-medium">{{ $vendor->created_at->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Vendor Owner Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Vendor Owner</h2>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Full Name</p>
                            <p class="font-medium">{{ $vendor->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-medium">{{ $vendor->user->email }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Phone</p>
                            <p class="font-medium">{{ $vendor->user->phone }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Account Status</p>
                            <p class="font-medium">
                                @if($vendor->user->email_verified_at)
                                    <span class="text-green-600">✓ Verified</span>
                                @else
                                    <span class="text-yellow-600">⚠ Not Verified</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Status</h3>
                
                <div class="text-center mb-4">
                    @if($vendor->status === 'approved')
                        <span class="inline-block px-4 py-2 text-lg font-semibold rounded-full bg-green-100 text-green-800">
                            ✓ Approved
                        </span>
                    @elseif($vendor->status === 'pending')
                        <span class="inline-block px-4 py-2 text-lg font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            ⏳ Pending
                        </span>
                    @else
                        <span class="inline-block px-4 py-2 text-lg font-semibold rounded-full bg-red-100 text-red-800">
                            ⊗ Suspended
                        </span>
                    @endif
                </div>

                @if($vendor->logo)
                    <div class="flex justify-center mb-4">
                        <img src="{{ asset('storage/' . $vendor->logo) }}" 
                             alt="Shop Logo" 
                             class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">
                    </div>
                @endif

                <div class="space-y-2">
                    @if($vendor->status === 'approved')
                        <form action="{{ route('admin.vendors.suspend', $vendor) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition"
                                    onclick="return confirm('Suspend this vendor?')">
                                Suspend Vendor
                            </button>
                        </form>
                    @elseif($vendor->status === 'suspended')
                        <form action="{{ route('admin.vendors.approve', $vendor) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition"
                                    onclick="return confirm('Re-activate this vendor?')">
                                Activate Vendor
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Statistics</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-gray-600">Total Products</span>
                        <span class="font-bold text-lg">{{ $stats['total_products'] }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-gray-600">Active Products</span>
                        <span class="font-bold text-lg text-green-600">{{ $stats['active_products'] }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-gray-600">Total Orders</span>
                        <span class="font-bold text-lg">{{ $stats['total_orders'] }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-gray-600">Pending Orders</span>
                        <span class="font-bold text-lg text-yellow-600">{{ $stats['pending_orders'] }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-gray-600">Total Sales</span>
                        <span class="font-bold text-lg text-blue-600">{{ number_format($stats['total_sales'], 2) }} TND</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-gray-600">Platform Commission</span>
                        <span class="font-bold text-lg text-purple-600">{{ number_format($stats['total_commission'], 2) }} TND</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Vendor Balance</span>
                        <span class="font-bold text-lg text-green-600">{{ number_format($stats['current_balance'], 2) }} TND</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>
                
                <div class="space-y-2">
                    <a href="#" class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-2 rounded-lg transition flex items-center justify-between">
                        <span>View Products</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="#" class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-2 rounded-lg transition flex items-center justify-between">
                        <span>View Orders</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="#" class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-2 rounded-lg transition flex items-center justify-between">
                        <span>View Transactions</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
