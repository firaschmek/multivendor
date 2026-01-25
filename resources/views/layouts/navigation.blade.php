<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <span class="text-2xl font-bold text-blue-600">Multivendor</span>
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600">Marketplace</a>

                @auth
                <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-blue-600">My Orders</a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-blue-600">
                        {{ Auth::user()->name }} - Logout
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">Login</a>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
