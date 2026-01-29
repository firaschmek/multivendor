<nav x-data="{ open: false }" class="bg-white border-b border-gray-100" dir="rtl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <span class="text-2xl font-bold text-blue-600">السوق</span>
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600">المنتجات</a>

                @auth
                <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-blue-600">طلباتي</a>

                @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600">لوحة الإدارة</a>
                @elseif(Auth::user()->role === 'vendor')
                <a href="{{ route('vendor.dashboard') }}" class="text-gray-700 hover:text-blue-600">لوحة البائع</a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-blue-600">
                        {{ Auth::user()->name }} - خروج
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">دخول</a>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">تسجيل</a>
                @endauth

                <a href="{{ route('cart.index') }}" class="relative text-gray-700 hover:text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</nav>
