@extends('layouts.app', ['title' => 'Trang chủ – NeoMart'])

@push('styles')
<style>
/* ===== HERO ===== */
.hero-section {
    background: linear-gradient(90deg, var(--primary-dark) 0%, var(--primary) 70%, #34d399 100%);
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
    top: -150px; right: -50px;
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 60%);
    border-radius: 50%;
}
.hero-section::after {
    content: '';
    position: absolute;
    bottom: -200px; left: 10%;
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 60%);
    border-radius: 50%;
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

/* Mobile adjustments for Hero and Buttons */
@media (max-width: 991.98px) {
    .hero-section {
        padding: 2.5rem 1.5rem !important;
        background: #047857 !important; /* solid deep green bg from screenshot */
    }
    .hero-section::before, .hero-section::after {
        display: none !important;
    }
    .hero-cta-group {
        flex-direction: column;
        align-items: stretch;
        gap: 0.75rem;
    }
    .hero-btn-primary, .hero-btn-outline {
        justify-content: center;
        width: 100%;
        font-size: 0.9rem !important;
        padding: 0.85rem 1.5rem !important;
    }
    .hero-btn-primary {
        background-color: #fbbf24 !important; /* Yellow/orange button */
        color: #000 !important;
    }
    .hero-stats {
        display: grid !important;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem 1rem !important;
        text-align: left;
        margin-top: 2rem !important;
    }
    .hero-stat-item {
        text-align: left !important;
    }
    .hero-stat-num {
        font-size: 1.6rem !important;
    }
    .hero-stat-label {
        font-size: 0.75rem !important;
        color: rgba(255, 255, 255, 0.7) !important;
    }
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

/* Mobile adjustments for carousel */
@media (max-width: 767.98px) {
    .banner-carousel-wrap {
        border-radius: 16px;
        border: none;
        margin-bottom: 1.5rem;
    }
    /* Force-hide Bootstrap carousel-caption on mobile (it shows at 576px+) */
    .carousel-caption {
        display: none !important;
    }
    /* Hide the big background image on mobile for both slides */
    .carousel-item > img.d-block {
        display: none !important;
    }
    .carousel-item .position-absolute {
        display: none !important;
    }
    /* Hide desktop layout blocks */
    .carousel-item > .d-none.d-md-block {
        display: none !important;
    }
    /* Hide carousel prev/next controls on mobile (user swipes) */
    .carousel-control-prev,
    .carousel-control-next {
        display: none !important;
    }
    /* Hide carousel indicators on mobile */
    .carousel-indicators {
        display: none !important;
    }
    /* Remove the dark green bg from carousel-inner on mobile */
    .carousel-inner {
        background: transparent !important;
    }

    /* --- Yellow pill button used in ALL mobile slides --- */
    .mobile-slide0-btn {
        display: inline-flex !important;
        align-items: center;
        gap: 0.35rem;
        background: #fbbf24 !important;
        color: #1a1a1a !important;
        border: none !important;
        border-radius: 50px !important;
        padding: 0.5rem 1.1rem !important;
        font-size: 0.8rem !important;
        font-weight: 700 !important;
        text-decoration: none !important;
        transition: background 0.2s;
        pointer-events: auto;
        cursor: pointer;
    }
    .mobile-slide0-btn:hover,
    .mobile-slide0-btn:active {
        background: #f59e0b !important;
        color: #1a1a1a !important;
    }

    /* --- Mobile Slide 0: Green card with text --- */
    .mobile-slide0-card {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #00a859 0%, #047857 100%);
        min-height: 140px;
        border-radius: 16px;
        margin: 0;
        padding: 1.25rem 1.5rem;
        position: relative;
        z-index: 5;
    }
    .mobile-slide0-card h3 {
        color: #fff;
        font-size: 1.05rem;
        font-weight: 800;
        line-height: 1.4;
        margin: 0 0 0.75rem 0;
        text-shadow: 0 1px 4px rgba(0,0,0,0.15);
    }

    /* --- Mobile Slide 1: Newest product card --- */
    .mobile-slide-flex {
        display: flex !important;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(135deg, #00a859 0%, #047857 100%);
        min-height: 140px;
        border-radius: 16px;
        margin: 0;
        padding: 1.25rem 1.5rem;
        position: relative;
        z-index: 5;
        pointer-events: auto;
    }
    .mobile-slide-left {
        flex: 1;
        padding-right: 0.75rem;
    }
    .mobile-slide-left h4 {
        color: #fff;
        font-size: 0.95rem;
        font-weight: 800 !important;
        line-height: 1.35;
        margin-bottom: 0.75rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-shadow: 0 1px 4px rgba(0,0,0,0.15);
    }
    .mobile-slide-right {
        flex-shrink: 0;
    }
    .mobile-slide-right img {
        width: 80px;
        height: 80px;
        object-fit: contain;
        background: #fff;
        border-radius: 14px;
        padding: 6px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
}

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
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100%;
}
.flash-card:hover {
    border-color: var(--accent);
    color: var(--text-primary);
}
    .flash-card img {
    width: 100%;
    height: 260px;
    object-fit: cover;
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
    height: 260px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
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
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid var(--border);
    display: flex; flex-direction: column;
    height: 100%;
}
.product-card:hover {
    border-color: var(--primary);
    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(0, 136, 72, 0.12);
}
.product-img-wrap {
    position: relative;
    padding: 1rem;
    background: #f8fafc;
    border: none;
    border-radius: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 220px; /* uniform height */
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.product-img-wrap img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.4s ease;
}
.product-card:hover .product-img-wrap img {
    transform: scale(1.08);
}
.product-overlay-btn {
    position: absolute;
    inset: 0;
    background: rgba(0, 136, 72, 0.04);
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
    display: inline-flex; align-items: center; gap: 0.4rem;
}
.product-quick-btn:hover { background: #ffc107; color: #000; }

.badge-corner {
    position: absolute;
    top: 10px; left: 10px;
    font-size: 0.68rem; font-weight: 800;
    padding: 0.2rem 0.55rem;
    border-radius: 6px;
    z-index: 10;
}
.wishlist-btn {
    position: absolute;
    top: 10px; right: 10px;
    width: 34px; height: 34px;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: none;
    color: #94a3b8;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    font-size: 0.95rem;
    transition: var(--transition);
    z-index: 10;
}
.wishlist-btn:hover { color: #ef4444; background: #fff; transform: scale(1.1); box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15); }
.wishlist-btn.active { color: #ef4444; }

.product-body {
    background: #ffffff;
    padding: 1.25rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}
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
.product-price-row { display: flex; align-items: baseline; justify-content: space-between; margin-top: auto; gap: 0.5rem; flex-wrap: wrap; }
.product-price { font-size: 1.15rem; font-weight: 900; color: var(--danger); }
.product-original { font-size: 0.78rem; color: #999; text-decoration: line-through; }
.btn-add-cart {
    width: 100%; height: 40px;
    border-radius: 8px;
    background: var(--accent);
    color: #0f172a;
    border: none;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: var(--transition);
}
.btn-add-cart:hover { background: #e09b0f; color: #0f172a; }
.btn-add-cart:disabled { opacity: 0.4; cursor: not-allowed; }

/* ===== CATEGORY CARDS ===== */
.group-card {
    transition: all 0.3s ease;
    border: 1px solid var(--border) !important;
    border-radius: 12px !important;
    background: #fff;
}
.group-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 136, 72, 0.08) !important;
    border-color: var(--primary) !important;
}
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
                        <i class="bi bi-cart-fill"></i> Mua sắm ngay
                    </a>
                    <a href="{{ route('products.index') }}" class="hero-btn-outline">
                        <i class="bi bi-lightning-fill" style="color:#fbbf24;"></i> Khuyến mãi hot
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat-item">
                        <div class="hero-stat-num">10K+</div>
                        <div class="hero-stat-label">SẢN PHẨM</div>
                    </div>
                    <div class="hero-stat-item">
                        <div class="hero-stat-num">50K+</div>
                        <div class="hero-stat-label">KHÁCH HÀNG</div>
                    </div>
                    <div class="hero-stat-item">
                        <div class="hero-stat-num">4.9★</div>
                        <div class="hero-stat-label">ĐÁNH GIÁ</div>
                    </div>
                    <div class="hero-stat-item">
                        <div class="hero-stat-num">2h</div>
                        <div class="hero-stat-label">GIAO HÀNG</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 position-relative mt-4 mt-lg-0" style="z-index:1">
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
            <div class="carousel-inner" style="background: #047857;">

                {{-- SLIDE 0: Mega Sale – chỉ dẫn đến trang sản phẩm --}}
                <div class="carousel-item active">
                    {{-- Desktop: full image + gradient overlay --}}
                    <img src="{{ $banners[0]['image'] }}"
                         class="d-block w-100" style="object-fit:cover;opacity:0.85;"
                         alt="{{ $banners[0]['title'] }}">
                    <div class="carousel-caption text-start d-none d-md-flex"
                         style="left:0;right:auto;bottom:0;top:0;background:linear-gradient(90deg,rgba(4,120,87,.95) 0%,rgba(4,120,87,.5) 55%,transparent 100%);padding:2rem 2.5rem;border-radius:0;flex-direction:column;justify-content:center;max-width:55%;z-index:5;pointer-events:none;">
                        <h2 class="fw-black text-white mb-3"
                            style="font-size:clamp(1.2rem,3vw,1.8rem);text-shadow:0 2px 8px rgba(0,0,0,.3);">
                            {{ $banners[0]['title'] }}
                        </h2>
                        <a href="{{ $banners[0]['link'] }}"
                           class="hero-btn-primary"
                           style="font-size:.85rem;padding:.55rem 1.25rem;align-self:flex-start;pointer-events:auto;">
                            Mua ngay <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>

                    {{-- Mobile: Green card --}}
                    <div class="d-md-none mobile-slide0-card">
                        <div>
                            <h3>{{ $banners[0]['title'] }}</h3>
                            <a href="{{ $banners[0]['link'] }}" class="mobile-slide0-btn">
                                Mua ngay <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- SLIDE 1: Sản phẩm mới nhất – form POST buy-now --}}
                @if(isset($banners[1]))
                <div class="carousel-item">
                    @if($newestProduct)
                        <!-- Desktop Layout -->
                        <div class="d-none d-md-block w-100 position-relative"
                             style="height:380px;background:linear-gradient(90deg,#047857 0%,#059669 35%,#ecfdf5 70%,#f3f4f6 100%);">
                            <img src="{{ $newestProduct->image_url }}"
                                 class="position-absolute"
                                 style="right:5%;top:50%;transform:translateY(-50%);max-height:80%;max-width:40%;object-fit:contain;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,.12);background:white;padding:1rem;"
                                 alt="{{ $newestProduct->name }}">
                        </div>
                        <div class="d-none d-md-flex carousel-caption text-start"
                             style="left:0;right:auto;bottom:0;top:0;background:linear-gradient(90deg,rgba(4,120,87,.95) 0%,rgba(4,120,87,.5) 55%,transparent 100%);padding:2rem 2.5rem;border-radius:0;flex-direction:column;justify-content:center;max-width:55%;z-index:5;pointer-events:none;">
                            <span class="badge mb-2" style="background:#fbbf24;color:#1a1a1a;font-size:.7rem;letter-spacing:.05em;width:fit-content;">Mới nhất</span>
                            <h2 class="fw-black text-white mb-1"
                                style="font-size:clamp(1rem,2.5vw,1.5rem);text-shadow:0 2px 8px rgba(0,0,0,.3);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                {{ $newestProduct->name }}
                            </h2>
                            <p class="text-white mb-3" style="font-size:1.1rem;font-weight:700;opacity:.9;">
                                {{ number_format($newestProduct->price, 0, ',', '.') }}đ
                                @if($newestProduct->original_price && $newestProduct->original_price > $newestProduct->price)
                                    <del class="ms-2" style="font-size:.85rem;opacity:.65;">{{ number_format($newestProduct->original_price, 0, ',', '.') }}đ</del>
                                @endif
                            </p>

                            {{-- Nút MUA NGAY: POST đến cart.buy-now để thêm vào giỏ và chuyển thẳng checkout --}}
                            <form method="POST" action="{{ route('cart.buy-now', $newestProduct) }}"
                                  style="align-self:flex-start;pointer-events:auto;">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="hero-btn-primary"
                                        style="font-size:.85rem;padding:.55rem 1.25rem;border:none;cursor:pointer;">
                                    Mua ngay <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Mobile Layout (green card with product thumbnail) -->
                        <div class="d-md-none mobile-slide-flex">
                            <div class="mobile-slide-left">
                                <h4>Mới nhất: {{ $newestProduct->name }}</h4>
                                <form method="POST" action="{{ route('cart.buy-now', $newestProduct) }}" class="m-0">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="mobile-slide0-btn">
                                        Mua ngay <i class="bi bi-arrow-right"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="mobile-slide-right">
                                <img src="{{ $newestProduct->image_url }}" alt="{{ $newestProduct->name }}">
                            </div>
                        </div>
                    @else
                        {{-- Fallback khi không có sản phẩm --}}
                        <div class="d-block w-100" style="height:380px;background:linear-gradient(90deg,#047857,#059669);"></div>
                        <div class="d-flex carousel-caption text-start"
                             style="left:0;right:auto;bottom:0;top:0;background:rgba(4,120,87,.85);padding:2rem 2.5rem;display:flex;flex-direction:column;justify-content:center;max-width:55%;z-index:5;pointer-events:none;">
                            <h2 class="fw-black text-white mb-3">{{ $banners[1]['title'] }}</h2>
                            <a href="{{ $banners[1]['link'] }}" class="hero-btn-primary"
                               style="font-size:.85rem;padding:.55rem 1.25rem;align-self:flex-start;pointer-events:auto;">
                                Khám phá ngay <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @endif
                </div>
                @endif

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



    <!-- 5. Sản phẩm gợi ý -->
    @if($suggested_products->isNotEmpty())
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="section-title mb-0">Sản phẩm gợi ý cho bạn</h3>
                <a href="{{ route('products.index') }}" class="btn btn-link text-primary fw-bold text-decoration-none">
                    Xem tất cả <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="row g-4">
                @foreach($suggested_products as $product)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="product-card">
                            <div class="product-img-wrap">
                                @if($product->original_price && $product->original_price > $product->price)
                                    <span class="badge-corner bg-danger text-white">
                                        -{{ round((1 - $product->price / $product->original_price) * 100) }}%
                                    </span>
                                @elseif($product->created_at->gt(now()->subDays(7)))
                                    <span class="badge-corner bg-success text-white">Mới</span>
                                @endif
                                <button class="wishlist-btn" onclick="toggleWishlist(this)" title="Yêu thích">
                                    <i class="bi bi-heart"></i>
                                </button>
                                <a href="{{ route('products.show', $product) }}">
                                    <img src="{{ $product->image_url ?: 'https://placehold.co/400x300?text='.urlencode($product->name) }}" alt="{{ $product->name }}" loading="lazy">
                                </a>
                            </div>
                            <div class="product-body">
                                <div class="product-cat">{{ $product->category?->name }}</div>
                                <a href="{{ route('products.show', $product) }}" class="product-name" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </a>
                                <div class="star-rating">
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                                    <span class="star-count">({{ rand(5, 80) }})</span>
                                </div>
                                <div class="product-price-row d-flex justify-content-between align-items-center mt-auto mb-3">
                                    <div>
                                        <div class="product-price">{{ number_format((float)$product->price, 0, ',', '.') }}đ</div>
                                        @if($product->original_price)
                                            <div class="product-original">{{ number_format((float)$product->original_price, 0, ',', '.') }}đ</div>
                                        @endif
                                    </div>
                                </div>
                                <form method="post" action="{{ route('cart.add', $product) }}" class="w-100 mb-0">
                                    @csrf
                                    <button type="submit" class="btn-add-cart" @disabled($product->stock <= 0)>
                                        Thêm vào giỏ
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    

    {{-- ===== 6. DANH MỤC SẢN PHẨM ===== --}}
@if($categories->isNotEmpty())
    <div class="mb-5">
        <div class="section-header">
            <h2 class="section-title">Danh mục sản phẩm</h2>
            <a href="{{ route('products.index') }}" class="section-link">
                Xem tất cả <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row g-4">
            @foreach($categories as $category)
                @php
                    $catMap = [
                        'Thuc pham'  => ['icon' => 'fa-apple-whole',        'bg' => '#fef3c7', 'color' => '#d97706'],
                        'Thực phẩm'  => ['icon' => 'fa-apple-whole',        'bg' => '#fef3c7', 'color' => '#d97706'],
                        'Do uong'    => ['icon' => 'fa-mug-hot',             'bg' => '#e0e7ff', 'color' => '#4f46e5'],
                        'Đồ uống'    => ['icon' => 'fa-mug-hot',             'bg' => '#e0e7ff', 'color' => '#4f46e5'],
                        'My pham'    => ['icon' => 'fa-wand-magic-sparkles', 'bg' => '#fce8e6', 'color' => '#d0021b'],
                        'Mỹ phẩm'   => ['icon' => 'fa-wand-magic-sparkles', 'bg' => '#fce8e6', 'color' => '#d0021b'],
                        'Gia dung'   => ['icon' => 'fa-house',               'bg' => '#d1fae5', 'color' => '#059669'],
                        'Gia dụng'   => ['icon' => 'fa-house',               'bg' => '#d1fae5', 'color' => '#059669'],
                    ];
                    $cat = $catMap[$category->name] ?? ['icon' => 'fa-box', 'bg' => '#f1f5f9', 'color' => '#64748b'];
                @endphp
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="group-card card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width:46px;height:46px;border-radius:10px;background:{{ $cat['bg'] }};color:{{ $cat['color'] }};">
                                    <i class="fa-solid {{ $cat['icon'] }}" style="font-size:1.2rem;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="font-size:0.97rem;">{{ $category->name }}</h6>
                                    <small class="text-muted">{{ $category->products_count }} sản phẩm</small>
                                </div>
                            </div>
                            @if($category->description)
                                <p class="small text-secondary mb-3" style="line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:2.5rem;">
                                    {{ $category->description }}
                                </p>
                            @endif
                            <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                               class="text-decoration-none fw-semibold small" style="color:var(--primary);">
                                Xem tất cả →
                            </a>
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
        <div class="trust-icon" style="background:#fff3e0;">
            <i class="fa-solid fa-truck-fast" style="color:#f57c00;"></i>
        </div>
        <div>
            <div class="trust-text">Giao hàng 2 giờ</div>
            <div class="trust-sub">Nội thành TP.HCM & Hà Nội</div>
        </div>
    </div>
    <div class="trust-item">
        <div class="trust-icon" style="background:#e8f0fe;">
            <i class="fa-solid fa-shield-halved" style="color:#1a73e8;"></i>
        </div>
        <div>
            <div class="trust-text">Bảo hành chính hãng</div>
            <div class="trust-sub">Đổi trả trong 30 ngày</div>
        </div>
    </div>
    <div class="trust-item">
        <div class="trust-icon" style="background:#fef3c7;">
            <i class="fa-solid fa-tag" style="color:#d97706;"></i>
        </div>
        <div>
            <div class="trust-text">Giá tốt nhất</div>
            <div class="trust-sub">Cam kết hoàn tiền chênh lệch</div>
        </div>
    </div>
    <div class="trust-item">
        <div class="trust-icon" style="background:#f3f4f6;">
            <i class="fa-solid fa-headset" style="color:#374151;"></i>
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
    // ===== FLASH SALE TIMER (2h45m, tự reset sau mỗi chu kỳ) =====
    (function () {
        const DURATION = 2 * 60 * 60 + 45 * 60; // 9900 giây
        const KEY = 'flashSaleEnd';

        function getOrInitEnd() {
            let stored = localStorage.getItem(KEY);
            const now = Date.now();
            // Nếu chưa có hoặc đã hết hạn ⇒ khởi tạo chu kỳ mới
            if (!stored || parseInt(stored, 10) <= now) {
                stored = (now + DURATION * 1000).toString();
                localStorage.setItem(KEY, stored);
            }
            return parseInt(stored, 10);
        }

        function tick() {
            const endTs = getOrInitEnd();
            const diff = Math.max(0, Math.floor((endTs - Date.now()) / 1000));

            const hEl = document.getElementById('timer-h');
            const mEl = document.getElementById('timer-m');
            const sEl = document.getElementById('timer-s');
            if (!hEl) return;

            const h = Math.floor(diff / 3600);
            const m = Math.floor((diff % 3600) / 60);
            const s = diff % 60;

            hEl.textContent = h.toString().padStart(2, '0');
            mEl.textContent = m.toString().padStart(2, '0');
            sEl.textContent = s.toString().padStart(2, '0');

            // Khi về 0 ⇒ xóa key để lần tick tiếp theo tự reset
            if (diff === 0) localStorage.removeItem(KEY);
        }

        tick();
        setInterval(tick, 1000);
    })();

    // Wishlist toggle (UI only)
    function toggleWishlist(btn) {
        btn.classList.toggle('active');
        const icon = btn.querySelector('i');
        icon.classList.toggle('bi-heart');
        icon.classList.toggle('bi-heart-fill');
    }
</script>
@endpush
