@extends('layouts.app', ['title' => 'Trang chủ – NeoMart'])

@push('styles')
<style>
/* ===== HERO ===== */
.hero-section {
    background: var(--primary-dark);
    border-radius: 12px;
    padding: 4.5rem 2rem;
    color: white;
    position: relative;
    overflow: hidden;
    margin-bottom: 2.5rem;
}
.hero-section::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 320px; height: 320px;
    background: rgba(255,255,255,0.06);
}
.hero-section::after {
    content: '';
    position: absolute;
    bottom: -100px; left: 30%;
    width: 260px; height: 260px;
    background: rgba(255,255,255,0.04);
}
.hero-badge {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: rgba(0,0,0,0.2);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 20px;
    padding: 0.4rem 1rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #fff;
    margin-bottom: 1.5rem;
}
.hero-badge .dot {
    width: 6px; height: 6px;
    background: #34d399;
    border-radius: 50%;
    animation: blink 1.5s infinite;
}
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }
.hero-title {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 900;
    line-height: 1.15;
    margin-bottom: 1.25rem;
    letter-spacing: -1px;
}
.hero-title span {
    background: linear-gradient(135deg, #fde68a, #fbbf24);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.hero-subtitle {
    color: rgba(255,255,255,0.75);
    font-size: 1rem;
    margin-bottom: 2rem;
    line-height: 1.6;
}
.hero-cta-group { display: flex; flex-wrap: wrap; gap: 0.75rem; }
.hero-btn-primary {
    background: var(--accent);
    color: #000;
    border: none;
    border-radius: 8px;
    padding: 0.75rem 1.75rem;
    font-weight: 700;
    font-size: 0.95rem;
    text-decoration: none;
    display: inline-flex; align-items: center; gap: 0.5rem;
    transition: all 0.25s;
}
.hero-btn-primary:hover { background: #ffc107; color: #000; }
.hero-btn-outline {
    background: transparent;
    color: #fff;
    border: 2px solid rgba(255,255,255,0.45);
    border-radius: 8px;
    padding: 0.75rem 1.75rem;
    font-weight: 600;
    font-size: 0.95rem;
    text-decoration: none;
    display: inline-flex; align-items: center; gap: 0.5rem;
    transition: all 0.25s;
}
.hero-btn-outline:hover { background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.7); color: #fff; }

/* Hero stats */
.hero-stats { display: flex; gap: 2rem; margin-top: 2.5rem; }
.hero-stat-item { text-align: center; }
.hero-stat-num { font-size: 1.5rem; font-weight: 900; color: #fff; line-height: 1; }
.hero-stat-label { font-size: 0.72rem; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }

/* Hero image */
.hero-img {
    border-radius: 12px;
    max-width: 100%;
    object-fit: cover;
    height: 320px;
    width: 100%;
}

/* ===== TRUST STRIP ===== */
.trust-strip {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    margin-bottom: 2.5rem;
}
.trust-item {
    background: #fff;
    border-radius: 8px;
    padding: 1rem 1.25rem;
    display: flex; align-items: center; gap: 0.75rem;
    border: 1px solid var(--border);
    transition: var(--transition);
}
.trust-item:hover { background: var(--surface-2); }
.trust-icon {
    width: 42px; height: 42px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.trust-text { font-size: 0.82rem; font-weight: 600; color: var(--text-primary); line-height: 1.3; }
.trust-sub  { font-size: 0.72rem; color: var(--text-muted); margin-top: 1px; }

/* ===== SECTION TITLE ===== */
.section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
.section-title {
    font-weight: 900;
    color: var(--primary) !important;
    font-size: 1.8rem;
    position: relative;
    padding-left: 1.25rem;
    margin: 0;
    letter-spacing: -0.5px;
}
.section-title::before {
    content: '';
    position: absolute; left: 0; top: 10%; bottom: 10%;
    width: 5px;
    background: var(--accent);
    border-radius: 4px;
}
.section-link {
    font-size: 0.85rem; font-weight: 600;
    color: var(--primary); text-decoration: none;
    display: flex; align-items: center; gap: 0.3rem;
    transition: gap 0.2s;
}
.section-link:hover { gap: 0.5rem; color: var(--primary-dark); }

/* ===== BANNER CAROUSEL ===== */
.banner-carousel-wrap { border-radius: 12px; overflow: hidden; margin-bottom: 2.5rem; border: 1px solid var(--border); }
.carousel-item img { height: 380px; object-fit: cover; }

/* ===== FLASH SALE ===== */
.flash-sale-section {
    background: var(--danger);
    border-radius: 12px;
    padding: 2rem;
    color: white;
    margin-bottom: 2.5rem;
    position: relative;
    overflow: hidden;
}
.flash-header { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
.flash-label {
    display: flex; align-items: center; gap: 0.5rem;
    font-size: 1.3rem; font-weight: 900;
    text-transform: uppercase;
}
.flash-label .icon { color: #fbbf24; font-size: 1.4rem; animation: shake 0.5s infinite alternate; }
@keyframes shake { from { transform: rotate(-5deg); } to { transform: rotate(5deg); } }
.timer-wrap { display: flex; align-items: center; gap: 0.4rem; }
.timer-label { font-size: 0.8rem; color: #fff; }
.timer-box {
    background: #000;
    color: #fff;
    padding: 0.4rem 0.65rem;
    border-radius: 6px;
    font-weight: 900;
    min-width: 42px;
    text-align: center;
    font-size: 1rem;
    font-variant-numeric: tabular-nums;
}
.timer-sep { font-weight: 900; color: #fff; }

.flash-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 1.25rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: var(--text-primary);
    display: block;
    height: 100%;
}
.flash-card:hover {
    border-color: var(--accent);
    color: var(--text-primary);
}
.flash-card img {
    height: 110px; object-fit: contain;
    margin-bottom: 0.75rem;
    transition: transform 0.4s ease;
}
.flash-card:hover img { transform: scale(1.05); }
.flash-card-name { font-size: 0.82rem; font-weight: 700; margin-bottom: 0.5rem; line-height: 1.3; min-height: 2.2rem; }
.flash-price { font-size: 1.1rem; font-weight: 900; color: var(--danger); }
.flash-original { font-size: 0.75rem; color: #999; text-decoration: line-through; margin-top: 2px; }
.flash-discount-badge {
    display: inline-block;
    background: var(--accent);
    color: #000;
    font-size: 0.65rem;
    font-weight: 800;
    padding: 0.15rem 0.45rem;
    border-radius: 4px;
    margin-bottom: 0.4rem;
}

/* ===== CATEGORY CARDS ===== */
.category-card {
    background: #fff;
    border-radius: 8px;
    border: 1px solid var(--border);
    overflow: hidden;
    transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
    cursor: pointer;
    text-decoration: none;
    color: var(--text-primary);
    display: flex;
    flex-direction: column;
    height: 100%;
}
.category-card:hover {
    border-color: var(--primary);
    background: #fff;
    color: var(--primary);
    transform: translateY(-6px);
    box-shadow: 0 12px 24px rgba(0, 136, 72, 0.1);
}
.category-img-wrap {
    width: 100%;
    height: 180px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}
.category-card:hover .category-img-wrap img {
    transform: scale(1.08);
}
.category-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.category-body {
    padding: 1.25rem 1rem;
    text-align: center;
}
.category-name {
    font-weight: 800;
    font-size: 1.15rem;
    color: var(--text-primary);
    margin: 0;
    transition: color 0.2s;
}
.category-card:hover .category-name {
    color: var(--primary);
}

/* ===== PRODUCT CARDS ===== */
.product-card {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    transition: var(--transition);
    border: 1px solid var(--border);
    display: flex; flex-direction: column;
    height: 100%;
}
.product-card:hover {
    border-color: var(--primary);
}
.product-img-wrap {
    position: relative;
    padding: 1.5rem;
    background: var(--surface-2);
    display: flex; align-items: center; justify-content: center;
    height: 200px;
    overflow: hidden;
}
.product-img-wrap img {
    max-height: 100%; max-width: 100%;
    object-fit: contain;
    transition: transform 0.5s ease;
}
.product-card:hover .product-img-wrap img { transform: scale(1.1); }
.product-overlay-btn {
    position: absolute;
    inset: 0;
    background: rgba(79,70,229,0.08);
    display: flex; align-items: center; justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}
.product-card:hover .product-overlay-btn { opacity: 1; }
.product-quick-btn {
    background: var(--accent);
    color: #000;
    border: none;
    border-radius: 8px;
    padding: 0.5rem 1.25rem;
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    display: flex; align-items: center; gap: 0.4rem;
}
.product-quick-btn:hover { background: #ffc107; color: #000; }

.badge-corner {
    position: absolute;
    top: 0; left: 0;
    font-size: 0.7rem; font-weight: 800;
    padding: 0.25rem 0.6rem;
    border-radius: 8px 0 8px 0;
    z-index: 10;
}
.wishlist-btn {
    position: absolute;
    top: 0; right: 0;
    width: 32px; height: 32px;
    border-radius: 0 8px 0 8px;
    background: rgba(0,0,0,0.05);
    border: none;
    color: #999;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    font-size: 0.9rem;
    transition: var(--transition);
    z-index: 10;
}
.wishlist-btn:hover { color: #ef4444; transform: scale(1.15); }
.wishlist-btn.active { color: #ef4444; }

.product-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
.product-cat { font-size: 0.72rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem; }
.product-name {
    font-weight: 700; font-size: 0.92rem;
    color: var(--text-primary);
    margin-bottom: 0.6rem;
    line-height: 1.4;
    min-height: 2.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-decoration: none;
}
.product-name:hover { color: var(--primary); }
.star-rating { color: #fbbf24; font-size: 0.75rem; margin-bottom: 0.6rem; }
.star-count { color: var(--text-muted); font-size: 0.72rem; margin-left: 0.25rem; }
.product-price-row { display: flex; flex-direction: column; margin-top: auto; gap: 0.5rem; }
.product-price { font-size: 1.15rem; font-weight: 900; color: var(--danger); }
.product-original { font-size: 0.78rem; color: #999; text-decoration: line-through; }
.btn-add-cart {
    width: 100%; height: 36px;
    border-radius: 0 0 8px 8px;
    background: var(--accent);
    color: #000;
    border: none;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: bold;
    text-transform: uppercase;
    transition: var(--transition);
}
.btn-add-cart:hover { background: #ffc107; color: #000; }
.btn-add-cart:disabled { opacity: 0.4; cursor: not-allowed; }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- ===== 1. HERO BANNER ===== --}}
    <div class="hero-section mb-4">
        <div class="row align-items-center">
            <div class="col-lg-6 position-relative" style="z-index:1">
                <div class="hero-badge">
                    <span class="dot"></span>
                    Đi chợ Online - Giao hàng siêu tốc
                </div>
                <h1 class="hero-title">
                    Thịt rau tươi sống<br>
                    <span>Giá rẻ mỗi ngày</span>
                </h1>
                <p class="hero-subtitle">
                    Hàng ngàn mặt hàng tươi sống, nhu yếu phẩm chất lượng. Giao hàng siêu tốc trong 2 giờ. Mua sắm dễ dàng, an tâm tuyệt đối cùng NeoMart.
                </p>
                <div class="hero-cta-group">
                    <a href="{{ route('products.index') }}" class="hero-btn-primary">
                        <i class="bi bi-bag-heart"></i> Mua sắm ngay
                    </a>
                    <a href="{{ route('products.index') }}" class="hero-btn-outline">
                        <i class="bi bi-lightning"></i> Khuyến mãi hot
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat-item">
                        <div class="hero-stat-num">10K+</div>
                        <div class="hero-stat-label">Sản phẩm</div>
                    </div>
                    <div class="hero-stat-item">
                        <div class="hero-stat-num">50K+</div>
                        <div class="hero-stat-label">Khách hàng</div>
                    </div>
                    <div class="hero-stat-item">
                        <div class="hero-stat-num">4.9★</div>
                        <div class="hero-stat-label">Đánh giá</div>
                    </div>
                    <div class="hero-stat-item">
                        <div class="hero-stat-num">2h</div>
                        <div class="hero-stat-label">Giao hàng</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block position-relative" style="z-index:1">
                <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=900&auto=format&fit=crop"
                     alt="NeoMart Groceries" class="hero-img">
            </div>
        </div>
    </div>

    {{-- ===== 2. BANNER CAROUSEL ===== --}}
    @if(count($banners) > 0)
    <div class="banner-carousel-wrap mb-4">
        <div id="homeBanner" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach($banners as $index => $banner)
                    <button type="button" data-bs-target="#homeBanner" data-bs-slide-to="{{ $index }}"
                            class="{{ $index == 0 ? 'active' : '' }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach($banners as $index => $banner)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <img src="{{ $banner['image'] }}" class="d-block w-100" alt="{{ $banner['title'] }}">
                        <div class="carousel-caption text-start" style="left:6%;right:auto;bottom:15%;background:rgba(0,0,0,0.6);padding:1.5rem 2rem;border-radius:0;">
                            <h2 class="fw-black text-white mb-3" style="font-size:clamp(1.2rem,3vw,1.8rem);">{{ $banner['title'] }}</h2>
                            <a href="{{ $banner['link'] }}" class="hero-btn-primary" style="font-size:0.85rem;padding:0.55rem 1.25rem;">
                                Mua ngay <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#homeBanner" data-bs-slide="prev"></button>
            <button class="carousel-control-next" type="button" data-bs-target="#homeBanner" data-bs-slide="next"></button>
        </div>
    </div>
    @endif

    {{-- ===== 4. FLASH SALE ===== --}}
    @if($flash_sales->isNotEmpty())
    <div class="flash-sale-section mb-4">
        <div class="flash-header">
            <div class="flash-label">
                <span class="icon"><i class="bi bi-lightning-fill"></i></span>
                <span>FLASH SALE</span>
            </div>
            <div class="timer-wrap">
                <span class="timer-label d-none d-sm-inline">Kết thúc sau:</span>
                <div class="timer-box" id="timer-h">00</div>
                <span class="timer-sep">:</span>
                <div class="timer-box" id="timer-m">00</div>
                <span class="timer-sep">:</span>
                <div class="timer-box" id="timer-s">00</div>
            </div>
            <a href="{{ route('products.index') }}" class="text-white text-decoration-none small fw-bold d-none d-sm-flex align-items-center gap-1">
                Xem tất cả <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row g-3">
            @foreach($flash_sales as $product)
            <div class="col-6 col-md-3">
                <a href="{{ route('products.show', $product) }}" class="flash-card">
                    @if($product->original_price && $product->original_price > $product->price)
                        <div class="flash-discount-badge">
                            -{{ round((1 - $product->price / $product->original_price) * 100) }}%
                        </div>
                    @endif
                    <img src="{{ $product->image_url ?: 'https://placehold.co/300x200?text='.urlencode($product->name) }}"
                         alt="{{ $product->name }}">
                    <div class="flash-card-name">{{ $product->name }}</div>
                    <div class="flash-price">{{ number_format((float)$product->price, 0, ',', '.') }}đ</div>
                    @if($product->original_price)
                        <div class="flash-original">{{ number_format((float)$product->original_price, 0, ',', '.') }}đ</div>
                    @endif
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ===== 5. DANH MỤC SẢN PHẨM ===== --}}
    @if($categories->isNotEmpty())
    <div class="mb-4">
        <div class="section-header">
            <h2 class="section-title">Danh mục nổi bật</h2>
            <a href="{{ route('products.index') }}" class="section-link">
                Xem tất cả <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row g-3">
            @php
                $getCategoryImage = function($category) {
                    $id = (int)$category->id;
                    $slug = trim(strtolower($category->slug));
                    $name = mb_strtolower(trim($category->name), 'UTF-8');
                    
                    // 1. Food / Groceries / Thực phẩm (ID 1)
                    if ($id === 1 || str_contains($slug, 'thuc-pham') || str_contains($slug, 'thucpham') || str_contains($name, 'thực phẩm') || str_contains($name, 'thuc pham') || str_contains($slug, 'food') || str_contains($name, 'ăn')) {
                        return 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=500&auto=format&fit=crop&q=80'; // Ultra-vibrant fresh market stand
                    }
                    
                    // 2. Drinks / Đồ uống (ID 2)
                    if ($id === 2 || str_contains($slug, 'do-uong') || str_contains($slug, 'douong') || str_contains($name, 'đồ uống') || str_contains($name, 'do uong') || str_contains($slug, 'drink') || str_contains($slug, 'beverage') || str_contains($name, 'nước') || str_contains($name, 'sữa')) {
                        return 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=500&auto=format&fit=crop&q=80'; // Vibrant tropical cocktails & beverages
                    }
                    
                    // 3. Cosmetics / Mỹ phẩm (ID 3)
                    if ($id === 3 || str_contains($slug, 'my-pham') || str_contains($slug, 'mypham') || str_contains($name, 'mỹ phẩm') || str_contains($name, 'my pham') || str_contains($slug, 'cosmetic') || str_contains($slug, 'beauty') || str_contains($name, 'tóc') || str_contains($name, 'da')) {
                        return 'https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?w=500&auto=format&fit=crop&q=80'; // Bright modern cosmetic bottles
                    }
                    
                    // 4. Houseware / Detergents / Gia dụng (ID 4 - handles 'da dung' typo too!)
                    if ($id === 4 || str_contains($slug, 'gia-dung') || str_contains($slug, 'giadung') || str_contains($name, 'gia dụng') || str_contains($name, 'gia dung') || str_contains($name, 'da dung') || str_contains($slug, 'house') || str_contains($slug, 'home') || str_contains($slug, 'clean') || str_contains($name, 'chén') || str_contains($name, 'giấy')) {
                        return 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=500&auto=format&fit=crop&q=80'; // Extremely bright, working cleaning supplies image
                    }
                    
                    // 5. Promotions / Khuyến mãi (ID 5)
                    if ($id === 5 || str_contains($slug, 'khuyen-mai') || str_contains($slug, 'khuyenmai') || str_contains($name, 'khuyến mãi') || str_contains($name, 'khuyen mai') || str_contains($slug, 'promo') || str_contains($slug, 'sale') || str_contains($name, 'tặng')) {
                        return 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=500&auto=format&fit=crop&q=80'; // Bright glowing shopping bags & promotions
                    }
                    
                    // Fallback general nice grocery image
                    return 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=500&auto=format&fit=crop&q=80';
                };
            @endphp
            @foreach($categories as $i => $category)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="category-card">
                    <div class="category-img-wrap">
                        <img src="{{ $getCategoryImage($category) }}" alt="{{ $category->name }}" loading="lazy">
                    </div>
                    <div class="category-body">
                        <div class="category-name">{{ $category->name }}</div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ===== 6. SẢN PHẨM GỢI Ý ===== --}}
    @if($featured_products->isNotEmpty())
    <div class="mb-4">
        <div class="section-header">
            <h2 class="section-title">Sản phẩm gợi ý cho bạn</h2>
            <a href="{{ route('products.index') }}" class="section-link">
                Xem tất cả <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row g-3">
            @foreach($featured_products as $product)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="product-card">
                    <div class="product-img-wrap">
                        {{-- Badge --}}
                        @if($product->original_price && $product->original_price > $product->price)
                            <span class="badge-corner bg-danger text-white">
                                -{{ round((1 - $product->price / $product->original_price) * 100) }}%
                            </span>
                        @elseif($product->created_at->gt(now()->subDays(7)))
                            <span class="badge-corner bg-success text-white">Mới</span>
                        @endif

                        {{-- Wishlist --}}
                        <button class="wishlist-btn" onclick="toggleWishlist(this)" title="Yêu thích">
                            <i class="bi bi-heart"></i>
                        </button>

                        <a href="{{ route('products.show', $product) }}">
                            <img src="{{ $product->image_url ?: 'https://placehold.co/400x300?text='.urlencode($product->name) }}"
                                 alt="{{ $product->name }}" loading="lazy">
                        </a>

                        {{-- Quick view overlay --}}
                        <div class="product-overlay-btn">
                            <a href="{{ route('products.show', $product) }}" class="product-quick-btn">
                                <i class="bi bi-eye"></i> Xem nhanh
                            </a>
                        </div>
                    </div>
                    <div class="product-body">
                        <div class="product-cat">{{ $product->category?->name }}</div>
                        <a href="{{ route('products.show', $product) }}" class="product-name">{{ $product->name }}</a>
                        <div class="star-rating">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                            <span class="star-count">({{ rand(10, 99) }})</span>
                        </div>
                        <div class="product-price-row">
                            <div>
                                <div class="product-price">{{ number_format((float)$product->price, 0, ',', '.') }}đ</div>
                                @if($product->original_price)
                                    <div class="product-original">{{ number_format((float)$product->original_price, 0, ',', '.') }}đ</div>
                                @endif
                            </div>
                            <form method="post" action="{{ route('cart.add', $product) }}" class="w-100">
                                @csrf
                                <button type="submit" class="btn-add-cart" title="Thêm vào giỏ" @disabled($product->stock <= 0)>
                                    CHỌN MUA
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ===== 7. TRUST STRIP ===== --}}
    <div class="trust-strip mb-4 mt-2">
        <div class="trust-item">
            <div class="trust-icon" style="background:#e0e7ff">
                <i class="bi bi-truck" style="color:var(--primary)"></i>
            </div>
            <div>
                <div class="trust-text">Giao hàng 2 giờ</div>
                <div class="trust-sub">Nội thành TP.HCM & Hà Nội</div>
            </div>
        </div>
        <div class="trust-item">
            <div class="trust-icon" style="background:#d1fae5">
                <i class="bi bi-shield-check" style="color:#10b981"></i>
            </div>
            <div>
                <div class="trust-text">Bảo hành chính hãng</div>
                <div class="trust-sub">Đổi trả trong 30 ngày</div>
            </div>
        </div>
        <div class="trust-item">
            <div class="trust-icon" style="background:#fef3c7">
                <i class="bi bi-percent" style="color:#f59e0b"></i>
            </div>
            <div>
                <div class="trust-text">Giá tốt nhất</div>
                <div class="trust-sub">Cam kết hoàn tiền chênh lệch</div>
            </div>
        </div>
        <div class="trust-item">
            <div class="trust-icon" style="background:#fce7f3">
                <i class="bi bi-headset" style="color:#ec4899"></i>
            </div>
            <div>
                <div class="trust-text">Hỗ trợ 24/7</div>
                <div class="trust-sub">Tư vấn miễn phí mọi lúc</div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Flash sale timer
    function updateTimer() {
        const endTime = new Date("{{ $flash_sale_end }}").getTime();
        const now = new Date().getTime();
        const diff = endTime - now;
        if (diff > 0) {
            document.getElementById('timer-h').innerText = Math.floor(diff / (1000*60*60)).toString().padStart(2,'0');
            document.getElementById('timer-m').innerText = Math.floor((diff % (1000*60*60)) / (1000*60)).toString().padStart(2,'0');
            document.getElementById('timer-s').innerText = Math.floor((diff % (1000*60)) / 1000).toString().padStart(2,'0');
        }
    }
    setInterval(updateTimer, 1000);
    updateTimer();

    // Wishlist toggle (UI only)
    function toggleWishlist(btn) {
        btn.classList.toggle('active');
        const icon = btn.querySelector('i');
        icon.classList.toggle('bi-heart');
        icon.classList.toggle('bi-heart-fill');
    }
</script>
@endpush
