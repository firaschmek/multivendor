@extends('layouts.app')

@section('title', 'راحة ثقة - الصفحة الرئيسية')

@section('content')
<style>
    * {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Modern Hero Section avec gradient élégant */
    .hero {
        background: linear-gradient(135deg, #0EA5E9 0%, #06B6D4 50%, #8B5CF6 100%);
        color: white;
        padding: 120px 20px;
        text-align: center;
        border-radius: 0 0 40px 40px;
        margin-bottom: 80px;
        position: relative;
        overflow: hidden;
    }

    .hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 600px;
        height: 600px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 500px;
        height: 500px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .hero h1 {
        font-size: 56px;
        margin-bottom: 20px;
        text-shadow: 0 4px 6px rgba(0,0,0,0.1);
        font-weight: 800;
    }

    .hero p {
        font-size: 22px;
        margin-bottom: 40px;
        opacity: 0.95;
    }

    .btn {
        padding: 16px 40px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 18px;
        display: inline-block;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .btn-primary {
        background: #FFFFFF;
        color: #0EA5E9;
    }

    .btn-primary:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    }

    .btn-secondary {
        background: transparent;
        color: #FFFFFF;
        border: 3px solid #FFFFFF;
        margin-right: 20px;
    }

    .btn-secondary:hover {
        background: #FFFFFF;
        color: #0EA5E9;
    }

    /* Section moderne */
    .section-title {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 40px;
        text-align: center;
        background: linear-gradient(135deg, #0EA5E9, #06B6D4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Category cards avec ombre élégante */
    .category-card {
        background: white;
        border-radius: 20px;
        padding: 40px 20px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        cursor: pointer;
    }

    .category-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .category-icon {
        font-size: 60px;
        margin-bottom: 20px;
    }

    .category-name {
        font-size: 22px;
        font-weight: 600;
        color: #1E293B;
        margin-bottom: 8px;
    }

    .category-count {
        color: #64748B;
        font-size: 16px;
    }

    /* Product cards modernes */
    .product-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .product-image {
        position: relative;
        height: 280px;
        background: linear-gradient(135deg, #F1F5F9, #E2E8F0);
        overflow: hidden;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }

    .product-card:hover .product-image img {
        transform: scale(1.1);
    }

    .product-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .badge-discount {
        background: linear-gradient(135deg, #EF4444, #DC2626);
        color: white;
    }

    .badge-new {
        background: linear-gradient(135deg, #10B981, #059669);
        color: white;
    }

    .product-info {
        padding: 24px;
    }

    .product-vendor {
        color: #64748B;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .product-name {
        font-size: 20px;
        font-weight: 700;
        color: #1E293B;
        margin-bottom: 12px;
        min-height: 48px;
    }

    .product-rating {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 16px;
    }

    .product-price {
        font-size: 32px;
        font-weight: 800;
        background: linear-gradient(135deg, #0EA5E9, #06B6D4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 8px;
    }

    .product-old-price {
        color: #94A3B8;
        text-decoration: line-through;
        font-size: 18px;
    }

    .product-btn {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #0EA5E9, #06B6D4);
        color: white;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        margin-top: 16px;
    }

    .product-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(14, 165, 233, 0.4);
    }

    /* CTA Section */
    .cta {
        background: linear-gradient(135deg, #8B5CF6, #7C3AED);
        border-radius: 30px;
        padding: 80px 40px;
        text-align: center;
        color: white;
        margin: 80px 0;
        box-shadow: 0 20px 50px rgba(139, 92, 246, 0.3);
    }

    .cta h2 {
        font-size: 42px;
        font-weight: 800;
        margin-bottom: 16px;
    }

    .cta p {
        font-size: 20px;
        margin-bottom: 30px;
        opacity: 0.95;
    }

    /* Features Section */
    .features {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 40px;
        margin: 80px 0;
    }

    .feature-card {
        text-align: center;
        padding: 40px;
        background: white;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    .feature-icon {
        font-size: 60px;
        margin-bottom: 20px;
    }

    .feature-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 12px;
        color: #1E293B;
    }

    .feature-desc {
        color: #64748B;
        font-size: 16px;
    }

    @media (max-width: 768px) {
        .hero h1 {
            font-size: 36px;
        }
        .features {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>مرحباً بكم في راحة ثقة</h1>
        <p>السوق الإلكتروني التونسي الحديث | Your Modern Tunisian Marketplace</p>
        <div>
            <a href="{{ route('products.index') }}" class="btn btn-primary">تسوق الآن</a>
            <a href="{{ route('vendor.register') }}" class="btn btn-secondary">سجل كبائع</a>
        </div>
    </div>
</section>

<!-- Categories -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <section class="mb-20">
        <h2 class="section-title">تسوق حسب الفئة</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @forelse($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->id]) }}" class="category-card">
                <div class="category-icon">
                    @switch($category->name)
                    @case('Electronics')
                    💻
                    @break
                    @case('Fashion')
                    👕
                    @break
                    @case('Home')
                    🏠
                    @break
                    @default
                    📦
                    @endswitch
                </div>
                <div class="category-name">{{ $category->name }}</div>
                <div class="category-count">{{ $category->products()->where('is_active', true)->count() }} منتج</div>
            </a>
            @empty
            <div class="col-span-full text-center text-gray-500">لا توجد فئات متاحة</div>
            @endforelse
        </div>
    </section>

    <!-- Featured Products -->
    <section class="mb-20">
        <h2 class="section-title">المنتجات المميزة</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @forelse($featuredProducts as $product)
            <div class="product-card">
                <a href="{{ route('products.show', $product->slug) }}">
                    <div class="product-image">
                        @if($product->images->first())
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}">
                        @else
                        <div class="flex items-center justify-center h-full">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @endif

                        @if($product->compare_price && $product->compare_price > $product->price)
                        <div class="product-badge badge-discount">
                            -{{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}%
                        </div>
                        @endif
                    </div>

                    <div class="product-info">
                        <div class="product-vendor">{{ $product->vendor->shop_name ?? 'متجر' }}</div>
                        <div class="product-name">{{ $product->name }}</div>

                        @if($product->rating > 0)
                        <div class="product-rating">
                            @for($i = 1; $i <= 5; $i++)
                            <span style="color: {{ $i <= $product->rating ? '#FBBF24' : '#E5E7EB' }}">⭐</span>
                            @endfor
                            <span class="text-sm text-gray-600">({{ $product->reviews()->count() }})</span>
                        </div>
                        @endif

                        <div class="product-price">{{ number_format($product->price, 2) }} د.ت</div>
                        @if($product->compare_price && $product->compare_price > $product->price)
                        <div class="product-old-price">{{ number_format($product->compare_price, 2) }} د.ت</div>
                        @endif

                        <button class="product-btn">أضف للسلة</button>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-span-full text-center text-gray-500 py-12">لا توجد منتجات مميزة حالياً</div>
            @endforelse
        </div>
    </section>

    <!-- New Products -->
    <section class="mb-20">
        <h2 class="section-title">أحدث المنتجات</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @forelse($newProducts as $product)
            <div class="product-card">
                <a href="{{ route('products.show', $product->slug) }}">
                    <div class="product-image">
                        @if($product->images->first())
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}">
                        @else
                        <div class="flex items-center justify-center h-full">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @endif
                        <div class="product-badge badge-new">جديد</div>
                    </div>

                    <div class="product-info">
                        <div class="product-vendor">{{ $product->vendor->shop_name ?? 'متجر' }}</div>
                        <div class="product-name">{{ $product->name }}</div>
                        <div class="product-price">{{ number_format($product->price, 2) }} د.ت</div>
                        <button class="product-btn">أضف للسلة</button>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-span-full text-center text-gray-500 py-12">لا توجد منتجات جديدة حالياً</div>
            @endforelse
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <h2>هل أنت بائع؟ انضم إلينا اليوم!</h2>
        <p>ابدأ في بيع منتجاتك وخدماتك لآلاف العملاء في تونس</p>
        <a href="{{ route('vendor.register') }}" class="btn btn-primary">سجل الآن مجاناً</a>
    </section>

    <!-- Features -->
    <section class="features">
        <div class="feature-card">
            <div class="feature-icon">🚚</div>
            <div class="feature-title">شحن سريع</div>
            <div class="feature-desc">توصيل إلى جميع ولايات تونس</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔒</div>
            <div class="feature-title">دفع آمن</div>
            <div class="feature-desc">دفع عند الاستلام أو بالبطاقة</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon">💬</div>
            <div class="feature-title">دعم العملاء</div>
            <div class="feature-desc">خدمة عملاء متاحة دائماً</div>
        </div>
    </section>
</div>
@endsection
