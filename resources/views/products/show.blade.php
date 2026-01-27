@extends('layouts.app')

@section('title', $product->name_ar)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <div class="mb-6 text-sm text-gray-600">
        <a href="{{ route('home') }}" class="hover:text-blue-600">الرئيسية</a>
        <span class="mx-2">←</span>
        <a href="{{ route('products.index') }}" class="hover:text-blue-600">المنتجات</a>
        <span class="mx-2">←</span>
        <a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="hover:text-blue-600">
            {{ $product->category->name_ar }}
        </a>
        <span class="mx-2">←</span>
        <span class="text-gray-900">{{ $product->name_ar }}</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white rounded-lg shadow-lg p-8">
        <!-- Product Images -->
        <div>
            <div class="mb-4">
                @if($product->images->count() > 0)
                <img id="mainImage"
                     src="{{ $product->images->first()->getImageUrl() }}"
                     alt="{{ $product->name_ar }}"
                     class="w-full h-96 object-contain rounded-lg border">
                @else
                <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                    <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                @endif
            </div>

            <!-- Thumbnails -->
            @if($product->images->count() > 1)
            <div class="flex gap-2 overflow-x-auto">
                @foreach($product->images as $image)
                <img src="{{ $image->getThumbnailUrl() }}"
                     alt="{{ $product->name_ar }}"
                     class="w-20 h-20 object-cover rounded border cursor-pointer hover:border-blue-500 thumbnail-image"
                     onclick="document.getElementById('mainImage').src='{{ $image->getImageUrl() }}'">
                @endforeach
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div>
            <h1 class="text-3xl font-bold mb-4">{{ $product->name_ar }}</h1>

            <!-- Rating -->
            @if($product->average_rating > 0)
            <div class="flex items-center gap-2 mb-4">
                <div class="flex">
                    @for($i = 1; $i <= 5; $i++)
                    <svg class="w-5 h-5 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    @endfor
                </div>
                <span class="text-gray-600">{{ number_format($product->average_rating, 1) }} ({{ $product->reviews_count }} تقييم)</span>
            </div>
            @endif

            <!-- Price -->
            <div class="mb-6">
                <div class="flex items-baseline gap-3">
                    <span class="text-4xl font-bold text-blue-600">{{ number_format($product->price, 2) }}</span>
                    <span class="text-xl text-gray-600">دت</span>
                    @if($product->compare_price && $product->compare_price > $product->price)
                    <span class="text-xl text-gray-400 line-through">{{ number_format($product->compare_price, 2) }} دت</span>
                    <span class="bg-red-500 text-white px-2 py-1 rounded text-sm font-bold">
                            خصم {{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}%
                        </span>
                    @endif
                </div>
            </div>

            <!-- Vendor Info -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <div>
                        <div class="text-sm text-gray-600">البائع</div>
                        <div class="font-semibold">{{ $product->vendor->shop_name_ar ?? $product->vendor->shop_name }}</div>
                    </div>
                </div>
            </div>

            <!-- Stock Status -->
            <div class="mb-6">
                @if($product->quantity > 0)
                <div class="flex items-center gap-2 text-green-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-semibold">متوفر في المخزون</span>
                    @if($product->quantity <= 5)
                    <span class="text-orange-500">(كمية محدودة)</span>
                    @endif
                </div>
                @else
                <div class="flex items-center gap-2 text-red-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-semibold">غير متوفر حالياً</span>
                </div>
                @endif
            </div>

            <!-- Add to Cart -->
            @if($product->quantity > 0)
            <div class="mb-6">
                <form id="addToCartForm" class="flex gap-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="flex items-center border rounded-lg">
                        <button type="button" onclick="decrementQty()" class="px-4 py-2 hover:bg-gray-100">-</button>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->quantity }}"
                               class="w-20 text-center border-x py-2 focus:outline-none">
                        <button type="button" onclick="incrementQty()" class="px-4 py-2 hover:bg-gray-100">+</button>
                    </div>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-bold flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        إضافة إلى السلة
                    </button>
                </form>
            </div>
            @endif

            <!-- Product Details -->
            <div class="border-t pt-6">
                <h3 class="font-bold text-lg mb-3">تفاصيل المنتج</h3>
                <div class="space-y-2 text-gray-700">
                    <div><strong>رمز المنتج:</strong> {{ $product->sku }}</div>
                    <div><strong>الفئة:</strong> {{ $product->category->name_ar ?? $product->category->name }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Description -->
    <div class="mt-8 bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-4">الوصف</h2>
        <div class="prose max-w-none text-gray-700">
            {!! nl2br(e($product->description_ar ?? $product->description)) !!}
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-12 bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">تقييمات العملاء</h2>

        <!-- Rating Summary -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <div class="flex items-center gap-8">
                <div class="text-center">
                    <div class="text-5xl font-bold text-gray-800">{{ number_format($product->average_rating, 1) }}</div>
                    <div class="flex items-center justify-center gap-1 my-2">
                        @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                    </div>
                    <div class="text-sm text-gray-600">{{ $product->reviews_count }} تقييم</div>
                </div>

                <div class="flex-1">
                    @php
                    $ratingDistribution = [
                    5 => $product->reviews()->where('rating', 5)->count(),
                    4 => $product->reviews()->where('rating', 4)->count(),
                    3 => $product->reviews()->where('rating', 3)->count(),
                    2 => $product->reviews()->where('rating', 2)->count(),
                    1 => $product->reviews()->where('rating', 1)->count(),
                    ];
                    @endphp

                    @foreach([5,4,3,2,1] as $star)
                    <div class="flex items-center gap-2 mb-2">
                        <div class="text-sm text-gray-600 w-12">{{ $star }} نجوم</div>
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-400 h-2 rounded-full"
                                 style="width: {{ $product->reviews_count > 0 ? ($ratingDistribution[$star] / $product->reviews_count * 100) : 0 }}%">
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 w-12">{{ $ratingDistribution[$star] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Write Review Button -->
        @auth
        @php
        $userReview = $product->reviews()->where('user_id', auth()->id())->first();
        $hasPurchased = \App\Models\Order::where('user_id', auth()->id())
        ->whereHas('orderItems', function($q) use ($product) {
        $q->where('product_id', $product->id);
        })
        ->where('status', 'delivered')
        ->exists();
        @endphp

        @if(!$userReview)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <button onclick="document.getElementById('reviewForm').classList.toggle('hidden')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
                <i class="fas fa-star ml-2"></i>اكتب تقييمك
            </button>
            @if($hasPurchased)
            <span class="mr-4 text-sm text-green-600">
                            <i class="fas fa-check-circle"></i> مشتري موثق
                        </span>
            @endif
        </div>

        <!-- Review Form -->
        <div id="reviewForm" class="bg-white rounded-lg border p-6 mb-6 hidden">
            <h3 class="text-lg font-bold text-gray-800 mb-4">اكتب تقييمك</h3>
            <form action="{{ route('reviews.store', $product) }}" method="POST">
                @csrf

                <!-- Rating -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">تقييمك *</label>
                    <div class="flex gap-2" id="starRating">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button" onclick="setRating({{ $i }})"
                                class="star-btn text-3xl text-gray-300 hover:text-yellow-400 transition">
                            <i class="fas fa-star"></i>
                        </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" required>
                    @error('rating')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Review Text -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">رأيك * (10 حروف على الأقل)</label>
                    <textarea name="review" rows="4"
                              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                              required minlength="10" maxlength="1000">{{ old('review') }}</textarea>
                    @error('review')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
                        إرسال التقييم
                    </button>
                    <button type="button" onclick="document.getElementById('reviewForm').classList.add('hidden')"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
        @else
        <!-- User's Review -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <div class="font-medium text-gray-800 mb-2">تقييمك</div>
                    <div class="flex gap-1 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $userReview->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                    </div>
                    <p class="text-gray-700">{{ $userReview->review }}</p>
                    <p class="text-sm text-gray-500 mt-2">{{ $userReview->created_at->format('d M Y') }}</p>
                </div>
                <form action="{{ route('reviews.destroy', $userReview) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('حذف تقييمك؟')"
                            class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @endif
        @else
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6 text-center">
            <p class="text-gray-600">
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">تسجيل الدخول</a>
                لكتابة تقييم
            </p>
        </div>
        @endauth

        <!-- Reviews List -->
        <div class="space-y-4">
            @forelse($product->reviews()->with('user')->latest()->get() as $review)
            <div class="bg-white rounded-lg border p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        {{ substr($review->user->name, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <div class="font-medium text-gray-800">{{ $review->user->name }}</div>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="flex gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star text-sm {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                    @if($review->verified_purchase)
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">
                                                <i class="fas fa-check-circle"></i> مشتري موثق
                                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">{{ $review->created_at->format('d M Y') }}</div>
                        </div>
                        <p class="text-gray-700 mb-3">{{ $review->review }}</p>
                        <button onclick="markHelpful({{ $review->id }})"
                                class="text-sm text-gray-600 hover:text-blue-600">
                            <i class="fas fa-thumbs-up"></i>
                            مفيد (<span id="helpful-{{ $review->id }}">{{ $review->helpful_count }}</span>)
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-lg border p-12 text-center">
                <i class="fas fa-comments text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">لا توجد تقييمات بعد. كن أول من يقيّم هذا المنتج!</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    function incrementQty() {
        const input = document.getElementById('quantity');
        const max = parseInt(input.max);
        const current = parseInt(input.value);
        if (current < max) {
            input.value = current + 1;
        }
    }

    function decrementQty() {
        const input = document.getElementById('quantity');
        const current = parseInt(input.value);
        if (current > 1) {
            input.value = current - 1;
        }
    }

    // Star rating selection
    function setRating(rating) {
        document.getElementById('ratingInput').value = rating;
        const stars = document.querySelectorAll('#starRating .star-btn i');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }

    // Mark review as helpful
    function markHelpful(reviewId) {
        fetch(`/reviews/${reviewId}/helpful`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`helpful-${reviewId}`).textContent = data.helpful_count;
                }
            })
            .catch(error => console.error('Error:', error));
    }

    document.getElementById('addToCartForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const button = this.querySelector('button[type="submit"]');
        const originalText = button.innerHTML;

        button.disabled = true;
        button.innerHTML = '<span>جاري الإضافة...</span>';

        try {
            const response = await fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    product_id: formData.get('product_id'),
                    quantity: formData.get('quantity')
                })
            });

            const data = await response.json();

            if (data.success) {
                const cartCount = document.getElementById('cart-count');
                if (cartCount) cartCount.textContent = data.cart_count;

                button.innerHTML = '<span class="text-green-400">✓ تمت الإضافة</span>';
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 2000);
            } else {
                alert(data.message);
                button.innerHTML = originalText;
                button.disabled = false;
            }
        } catch (error) {
            alert('حدث خطأ أثناء الإضافة إلى السلة');
            button.innerHTML = originalText;
            button.disabled = false;
        }
    });
</script>
@endsection
