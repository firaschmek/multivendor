<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vendor Dashboard') - {{ config('app.name', 'RahouThi3a') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-link.active { background-color: #3B82F6; color: white; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100">
<div class="flex h-screen overflow-hidden">
    <aside class="w-64 bg-white shadow-lg flex-shrink-0 hidden md:block">
        <div class="h-full flex flex-col">
            <div class="p-6 border-b">
                <a href="{{ route('vendor.dashboard') }}" class="flex items-center gap-3">
                    @if(auth()->user()->vendor->logo)
                    <img src="{{ asset('storage/' . auth()->user()->vendor->logo) }}" alt="Logo" class="w-10 h-10 rounded-full object-cover">
                    @else
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                        {{ substr(auth()->user()->vendor->shop_name, 0, 1) }}
                    </div>
                    @endif
                    <div>
                        <div class="font-bold text-gray-800 truncate max-w-[150px]">{{ auth()->user()->vendor->shop_name }}</div>
                        <div class="text-xs text-gray-500">Vendor Panel</div>
                    </div>
                </a>
            </div>
            <nav class="flex-1 overflow-y-auto py-4">
                <a href="{{ route('vendor.dashboard') }}" class="sidebar-link flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-blue-50 transition {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home w-5"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('vendor.products.index') }}" class="sidebar-link flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-blue-50 transition {{ request()->routeIs('vendor.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box w-5"></i><span>Products</span>
                </a>
                <a href="{{ route('vendor.orders.index') }}" class="sidebar-link flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-blue-50 transition {{ request()->routeIs('vendor.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart w-5"></i><span>Orders</span>
                </a>
                <a href="{{ route('vendor.transactions.index') }}" class="sidebar-link flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-blue-50 transition {{ request()->routeIs('vendor.transactions.*') ? 'active' : '' }}">
                    <i class="fas fa-wallet w-5"></i><span>Earnings</span>
                </a>
                <a href="{{ route('vendor.shop.edit') }}" class="sidebar-link flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-blue-50 transition {{ request()->routeIs('vendor.shop.*') ? 'active' : '' }}">
                    <i class="fas fa-store w-5"></i><span>Shop Settings</span>
                </a>
                <div class="border-t my-4"></div>
                <a href="{{ route('home') }}" class="sidebar-link flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-blue-50 transition">
                    <i class="fas fa-external-link-alt w-5"></i><span>Visit Store</span>
                </a>
            </nav>
            <div class="border-t p-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-gray-800 truncate">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-red-50 text-red-600 px-4 py-2 rounded-lg hover:bg-red-100 transition text-sm font-medium">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm border-b">
            <div class="px-6 py-4 flex items-center justify-between">
                <button onclick="toggleSidebar()" class="md:hidden text-gray-600"><i class="fas fa-bars text-xl"></i></button>
                <h1 class="text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-2 bg-green-50 px-4 py-2 rounded-lg">
                        <i class="fas fa-wallet text-green-600"></i>
                        <div>
                            <div class="text-xs text-gray-600">Balance</div>
                            <div class="font-bold text-green-600">{{ number_format(auth()->user()->vendor->balance, 2) }} TND</div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <main class="flex-1 overflow-y-auto p-6">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900"><i class="fas fa-times"></i></button>
            </div>
            @endif
            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900"><i class="fas fa-times"></i></button>
            </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('mobileSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar?.classList.toggle('-translate-x-full');
        overlay?.classList.toggle('hidden');
    }
    setTimeout(() => {
        document.querySelectorAll('.bg-green-100, .bg-red-100').forEach(el => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 5000);
</script>
@stack('scripts')
</body>
</html>
