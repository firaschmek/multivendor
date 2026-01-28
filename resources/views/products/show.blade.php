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
                    @if($i <= floor($product->average_rating))
                    <i class="fas fa-star text-yellow-400"></i>
                    @elseif($i - 0.5 <= $product->average_rating)
                    <i class="fas fa-star-half-alt text-yellow-400"></i>
                    @else
                    <i class="far fa-star text-gray-300"></i>
                    @endif
                    @endfor
                </div>
                <span class="text-gray-600">{{ number_format($product->average_rating, 1) }} ({{ $product->reviews_count }} تقييم)</span>
                <a href="#reviews-section" class="text-blue-600 hover:underline text-sm">عرض جميع التقييمات</a>
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
                    <span class="text-orange-500">(كمية محدودة: {{ $product->quantity }} قطع)</span>
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
                    @if($product->weight)
                    <div><strong>الوزن:</strong> {{ $product->weight }} كغ</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Description -->
    <div class="mt-8 bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-4">الوصف</h2>
        <div class="prose max-w-none text-gray-700 leading-relaxed">
            {!! nl2br(e($product->description_ar ?? $product->description)) !!}
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-8 bg-white rounded-lg shadow-lg p-8" id="reviews-section">
        <h2 class="text-2xl font-bold mb-6">تقييمات العملاء</h2>

        {{-- ملخص التقييمات --}}
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <div class="grid md:grid-cols-2 gap-8">
                {{-- التقييم العام --}}
                <div class="text-center">
                    <div class="text-5xl font-bold text-gray-900 mb-2">
                        {{ $product->reviews_count > 0 ? number_format($product->average_rating, 1) : '0.0' }}
                    </div>
                    <div class="flex justify-center items-center gap-1 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($product->average_rating))
                        <i class="fas fa-star text-yellow-400 text-xl"></i>
                        @elseif($i - 0.5 <= $product->average_rating)
                        <i class="fas fa-star-half-alt text-yellow-400 text-xl"></i>
                        @else
                        <i class="far fa-star text-gray-300 text-xl"></i>
                        @endif
                        @endfor
                    </div>
                    <p class="text-gray-600">بناءً على {{ $product->reviews_count }} تقييم</p>
                </div>

                {{-- توزيع التقييمات --}}
                <div class="space-y-2">
                    @php
                    $ratingDistribution = [];
                    for($i = 5; $i >= 1; $i--) {
                    $count = $product->reviews()->where('rating', $i)->count();
                    $percentage = $product->reviews_count > 0 ? ($count / $product->reviews_count) * 100 : 0;
                    $ratingDistribution[$i] = [
                    'count' => $count,
                    'percentage' => round($percentage, 1)
                    ];
                    }
                    @endphp

                    @foreach($ratingDistribution as $stars => $data)
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-600 w-16">{{ $stars }} نجوم</span>
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $data['percentage'] }}%"></div>
                        </div>
                        <span class="text-sm text-gray-600 w-12 text-left">{{ $data['count'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- زر كتابة تقييم --}}
        @auth
        @php
        $userReview = $product->reviews()->where('user_id', Auth::id())->first();
        @endphp

        @if(!$userReview)
        <div class="mb-8">
            <button onclick="document.getElementById('review-form').classList.toggle('hidden')"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-pen-alt ml-2"></i>
                اكتب تقييمك
            </button>
        </div>

        {{-- نموذج إضافة تقييم --}}
        <div id="review-form" class="hidden bg-white border rounded-lg p-6 mb-8">
            <h3 class="text-xl font-semibold mb-4">شارك رأيك</h3>
            <form action="{{ route('reviews.store', $product) }}" method="POST">
                @csrf

                {{-- اختيار التقييم --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">التقييم *</label>
                    <div class="flex gap-2" id="star-rating">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button"
                                class="star-btn text-3xl text-gray-300 hover:text-yellow-400 transition"
                                data-rating="{{ $i }}">
                            <i class="far fa-star"></i>
                        </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" required>
                    @error('rating')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- نص التقييم --}}
                <div class="mb-4">
                    <label for="review" class="block text-sm font-medium text-gray-700 mb-2">
                        تقييمك * (10 أحرف على الأقل)
                    </label>
                    <textarea name="review" id="review" rows="4"
                              class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="أخبرنا عن تجربتك مع المنتج..."
                              required minlength="10" maxlength="1000">{{ old('review') }}</textarea>
                    @error('review')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-check ml-2"></i>
                        نشر التقييم
                    </button>
                    <button type="button"
                            onclick="document.getElementById('review-form').classList.add('hidden')"
                            class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
        @endif
        @else
        <div class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-blue-800">
                <i class="fas fa-info-circle ml-2"></i>
                يرجى <a href="{{ route('login') }}" class="font-semibold underline hover:text-blue-900">تسجيل الدخول</a>
                لترك تقييم على هذا المنتج
            </p>
        </div>
        @endauth

        {{-- عرض تقييم المستخدم الحالي --}}
        @auth
        @if($userReview)
        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <h3 class="font-semibold text-lg">تقييمك</h3>
                    <div class="flex items-center gap-2 mt-1">
                        @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $userReview->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                        @if($userReview->verified_purchase)
                        <span class="bg-green-600 text-white text-xs px-2 py-1 rounded">
                                        <i class="fas fa-check-circle"></i> مشتري موثق
                                    </span>
                        @endif
                    </div>
                </div>
                <div class="flex gap-2">
                    <button onclick="document.getElementById('edit-review-{{ $userReview->id }}').classList.toggle('hidden')"
                            class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-edit"></i> تعديل
                    </button>
                    <form action="{{ route('reviews.destroy', $userReview) }}" method="POST" class="inline"
                          onsubmit="return confirm('هل أنت متأكد من حذف تقييمك؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i> حذف
                        </button>
                    </form>
                </div>
            </div>
            <p class="text-gray-700">{{ $userReview->review }}</p>
            <p class="text-sm text-gray-500 mt-2">
                {{ $userReview->created_at->diffForHumans() }}
            </p>

            {{-- نموذج تعديل التقييم --}}
            <div id="edit-review-{{ $userReview->id }}" class="hidden mt-4 pt-4 border-t">
                <form action="{{ route('reviews.update', $userReview) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">التقييم *</label>
                        <div class="flex gap-2" id="edit-star-rating-{{ $userReview->id }}">
                            @for($i = 1; $i <= 5; $i++)
                            <button type="button"
                                    class="edit-star-btn text-3xl {{ $i <= $userReview->rating ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition"
                                    data-rating="{{ $i }}"
                                    data-review-id="{{ $userReview->id }}">
                                <i class="fas fa-star"></i>
                            </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="edit-rating-input-{{ $userReview->id }}" value="{{ $userReview->rating }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="edit-review" class="block text-sm font-medium text-gray-700 mb-2">تقييمك *</label>
                        <textarea name="review" rows="4"
                                  class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  required minlength="10" maxlength="1000">{{ $userReview->review }}</textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-save ml-2"></i>
                            حفظ التعديلات
                        </button>
                        <button type="button"
                                onclick="document.getElementById('edit-review-{{ $userReview->id }}').classList.add('hidden')"
                                class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
        @endauth

        {{-- قائمة جميع التقييمات --}}
        <div class="space-y-6">
            <h3 class="text-xl font-semibold mb-4">جميع التقييمات ({{ $product->reviews_count }})</h3>

            @forelse($product->reviews()->with('user')->latest()->paginate(10) as $review)
            <div class="border-b pb-6">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="font-semibold">{{ $review->user->name }}</span>
                            @if($review->verified_purchase)
                            <span class="bg-green-600 text-white text-xs px-2 py-1 rounded">
                                        <i class="fas fa-check-circle"></i> مشتري موثق
                                    </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                            <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    {{-- زر مفيد --}}
                    @auth
                    @if($review->user_id !== Auth::id())
                    <button onclick="markHelpful({{ $review->id }})"
                            class="text-gray-600 hover:text-blue-600 transition"
                            id="helpful-btn-{{ $review->id }}">
                        <i class="far fa-thumbs-up ml-1"></i>
                        مفيد (<span id="helpful-count-{{ $review->id }}">{{ $review->helpful_count }}</span>)
                    </button>
                    @endif
                    @endauth
                </div>
                <p class="text-gray-700">{{ $review->review }}</p>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-comments text-4xl mb-3"></i>
                <p>لا توجد تقييمات بعد. كن أول من يقيم هذا المنتج!</p>
            </div>
            @endforelse

            {{-- Pagination --}}
            @if($product->reviews_count > 10)
            <div class="mt-6">
                {{ $product->reviews()->paginate(10)->links() }}
            </div>
            @endif
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

    // نموذج إضافة تقييم جديد
    document.querySelectorAll('#star-rating .star-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const rating = this.dataset.rating;
            document.getElementById('rating-input').value = rating;

            // تحديث مظهر النجوم
            document.querySelectorAll('#star-rating .star-btn').forEach((star, index) => {
                const icon = star.querySelector('i');
                if (index < rating) {
                    icon.className = 'fas fa-star text-yellow-400';
                } else {
                    icon.className = 'far fa-star text-gray-300';
                }
            });
        });
    });

    // نماذج تعديل التقييم
    document.querySelectorAll('.edit-star-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const rating = this.dataset.rating;
            const reviewId = this.dataset.reviewId;
            document.getElementById(`edit-rating-input-${reviewId}`).value = rating;

            // تحديث مظهر النجوم
            document.querySelectorAll(`#edit-star-rating-${reviewId} .edit-star-btn`).forEach((star, index) => {
                const icon = star.querySelector('i');
                if (index < rating) {
                    icon.className = 'fas fa-star text-yellow-400';
                } else {
                    icon.className = 'far fa-star text-gray-300';
                }
            });
        });
    });

    // وظيفة وضع علامة مفيد
    function markHelpful(reviewId) {
        fetch(`/reviews/${reviewId}/helpful`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`helpful-count-${reviewId}`).textContent = data.helpful_count;
                    const btn = document.getElementById(`helpful-btn-${reviewId}`);
                    btn.classList.add('text-blue-600');
                    btn.classList.remove('text-gray-600');
                }
            })
            .catch(error => console.error('خطأ:', error));
    }

    // Add to cart
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
