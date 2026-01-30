<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm" dir="rtl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-2 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">السوق</span>
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">المنتجات</a>

                @auth
                <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">طلباتي</a>

                @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">لوحة الإدارة</a>
                @elseif(Auth::user()->role === 'vendor')
                <a href="{{ route('vendor.dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">لوحة البائع</a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-blue-600 font-medium transition">
                        {{ Auth::user()->name }} - خروج
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">دخول</a>
                <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition shadow-md">تسجيل</a>
                @endauth

                <a href="{{ route('cart.index') }}" class="relative text-gray-700 hover:text-blue-600 transition p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    @php
                        $cartCount = 0;
                        if (auth()->check()) {
                            $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity');
                        } else {
                            $sessionId = session()->get('cart_session_id');
                            if ($sessionId) {
                                $cartCount = \App\Models\CartItem::where('session_id', $sessionId)->sum('quantity');
                            }
                        }
                    @endphp
                    <span id="cart-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center {{ $cartCount > 0 ? '' : 'hidden' }}">
                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                    </span>
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center sm:hidden">
                <a href="{{ route('cart.index') }}" class="relative text-gray-700 hover:text-blue-600 transition p-2 mr-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="cart-count-mobile absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center {{ $cartCount > 0 ? '' : 'hidden' }}">
                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                    </span>
                </a>
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('products.index') }}" class="block py-2 text-gray-700 hover:text-blue-600">المنتجات</a>
            @auth
            <a href="{{ route('orders.index') }}" class="block py-2 text-gray-700 hover:text-blue-600">طلباتي</a>
            @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="block py-2 text-gray-700 hover:text-blue-600">لوحة الإدارة</a>
            @elseif(Auth::user()->role === 'vendor')
            <a href="{{ route('vendor.dashboard') }}" class="block py-2 text-gray-700 hover:text-blue-600">لوحة البائع</a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-right py-2 text-gray-700 hover:text-blue-600">خروج</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="block py-2 text-gray-700 hover:text-blue-600">دخول</a>
            <a href="{{ route('register') }}" class="block py-2 text-blue-600 font-bold">تسجيل</a>
            @endauth
        </div>
    </div>
</nav>
