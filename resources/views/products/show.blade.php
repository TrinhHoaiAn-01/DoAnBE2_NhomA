@extends('layouts.app', ['title' => $product->name . ' – NeoMart'])

@push('styles')
<style>
/* ===== BREADCRUMB ===== */
.custom-breadcrumb .breadcrumb-item a { color: var(--primary); text-decoration: none; font-size: 0.85rem; }
.custom-breadcrumb .breadcrumb-item.active { font-size: 0.85rem; color: var(--text-muted); }
.custom-breadcrumb .breadcrumb-item + .breadcrumb-item::before { color: var(--text-muted); }

/* ===== UNIFIED PRODUCT CARD ===== */
.product-detail-card {
    background: #fff;
    border-radius: 24px;
    border: 1px solid var(--border);
    overflow: hidden;
    margin-bottom: 2rem;
    align-items: stretch !important;
}
.product-detail-card > [class*='col'] {
    display: flex;
    flex-direction: column;
}

/* ===== IMAGE SECTION ===== */
.main-image-wrap {
    background: var(--surface-2);
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 1;
    min-height: 440px;
    position: relative;
    overflow: hidden;
    cursor: zoom-in;
    border-radius: 0;
}
.main-image-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}
.main-image-wrap:hover img { transform: scale(1.05); }
.image-badge-corner {
    position: absolute; top: 1rem; left: 1rem;
    font-size: 0.75rem; font-weight: 800;
    padding: 0.3rem 0.75rem; border-radius: 10px;
    z-index: 2;
}

/* Thumbnails */
.thumb-row {
    display: flex; gap: 0.5rem;
    padding: 0.75rem 1rem;
    border-top: 1px solid var(--border);
    overflow-x: auto;
}
.thumb-item {
    width: 60px; height: 60px; flex-shrink: 0;
    border-radius: 10px;
    border: 2px solid transparent;
    background: var(--surface-2);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    overflow: hidden;
    transition: border-color 0.2s;
}
.thumb-item.active, .thumb-item:hover { border-color: var(--primary); }
.thumb-item img { max-width: 100%; max-height: 100%; object-fit: contain; }

/* ===== INFO SECTION ===== */
.product-info-card {
    background: #fff;
    border-radius: 0;
    border-left: none;
    padding: 2rem;
    height: 100%;
    overflow-y: auto;
}
.product-cat-badge {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: var(--primary-light);
    color: var(--primary);
    font-size: 0.75rem; font-weight: 700;
    padding: 0.35rem 0.85rem;
    border-radius: 50px;
    text-decoration: none;
    margin-bottom: 1rem;
    transition: var(--transition);
}
.product-cat-badge:hover { background: var(--primary); color: #fff; }

.product-title { font-size: 1.6rem; font-weight: 800; line-height: 1.25; color: var(--text-primary); margin-bottom: 0.75rem; }
.product-meta { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem; }
.product-meta strong { color: var(--text-secondary); }

/* Rating */
.product-rating {
    display: flex; align-items: center; gap: 0.75rem;
    margin-bottom: 1.25rem;
    padding-bottom: 1.25rem;
    border-bottom: 1px solid var(--border);
}
.stars { color: #fbbf24; font-size: 1rem; }
.rating-score { font-size: 1.1rem; font-weight: 800; color: var(--text-primary); }
.rating-count { font-size: 0.82rem; color: var(--text-muted); }
.rating-divider { color: var(--border); }
.sold-count { font-size: 0.82rem; color: var(--text-muted); }

/* Price */
.price-block {
    background: linear-gradient(135deg, #f0f0ff, #e8ecff);
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid var(--primary-light);
}
.price-current { font-size: 2rem; font-weight: 900; color: var(--primary); line-height: 1; }
.price-original { font-size: 1rem; color: var(--text-muted); text-decoration: line-through; margin-left: 0.5rem; }
.price-discount-badge {
    background: var(--danger);
    color: #fff;
    font-size: 0.75rem; font-weight: 800;
    padding: 0.25rem 0.65rem;
    border-radius: 8px;
    margin-left: 0.5rem;
}
.price-saved {
    font-size: 0.8rem; color: #10b981;
    font-weight: 600; margin-top: 0.4rem;
    display: flex; align-items: center; gap: 0.3rem;
}

/* Stock badge */
.stock-indicator {
    display: inline-flex; align-items: center; gap: 0.4rem;
    font-size: 0.875rem; font-weight: 600;
    padding: 0.4rem 1rem; border-radius: 50px;
    margin-bottom: 1.5rem;
}
.stock-indicator.in-stock { background: #d1fae5; color: #065f46; }
.stock-indicator.low-stock { background: #fef3c7; color: #92400e; }
.stock-indicator.out-stock { background: #f1f5f9; color: #64748b; }

/* Description */
.product-desc {
    font-size: 0.9rem; line-height: 1.7;
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border);
}

/* Quantity + Actions */
.quantity-label { font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem; }
.qty-wrap {
    display: flex; align-items: center;
    border: 1.5px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    width: fit-content;
    margin-bottom: 1.25rem;
}
.qty-btn {
    width: 40px; height: 40px;
    background: var(--surface-2); border: none;
    color: var(--text-primary); font-size: 1.1rem;
    cursor: pointer; transition: var(--transition);
    display: flex; align-items: center; justify-content: center;
}
.qty-btn:hover { background: var(--primary-light); color: var(--primary); }
.qty-input {
    width: 52px; height: 40px;
    border: none; border-left: 1px solid var(--border); border-right: 1px solid var(--border);
    text-align: center; font-weight: 700; font-size: 0.95rem;
    color: var(--text-primary); outline: none;
    background: #fff;
}
.qty-input::-webkit-outer-spin-button,
.qty-input::-webkit-inner-spin-button { -webkit-appearance: none; }

.action-row { display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1.25rem; }
.btn-add-to-cart {
    flex: 1; min-width: 160px;
    background: var(--primary); color: #fff;
    border: none; border-radius: 14px;
    padding: 0.85rem 1.5rem;
    font-weight: 700; font-size: 0.95rem;
    cursor: pointer; transition: var(--transition);
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
}
.btn-add-to-cart:hover:not(:disabled) { background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(99,102,241,0.3); }
.btn-add-to-cart:disabled { opacity: 0.5; cursor: not-allowed; }

.btn-buy-now {
    flex: 1; min-width: 140px;
    background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff;
    border: none; border-radius: 14px;
    padding: 0.85rem 1.5rem;
    font-weight: 700; font-size: 0.95rem;
    cursor: pointer; transition: var(--transition);
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
}
.btn-buy-now:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(245,158,11,0.35); }
.btn-buy-now:disabled { opacity: 0.5; cursor: not-allowed; }

.btn-wishlist-lg {
    width: 48px; height: 48px;
    border-radius: 14px;
    border: 1.5px solid var(--border);
    background: #fff;
    color: var(--text-muted);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 1.1rem;
    transition: var(--transition);
    flex-shrink: 0;
}
.btn-wishlist-lg:hover { border-color: #ef4444; color: #ef4444; background: #fff5f5; }
.btn-wishlist-lg.active { border-color: #ef4444; color: #ef4444; background: #fff5f5; }

/* Perks */
.perk-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.6rem;
}
.perk-item {
    display: flex; align-items: center; gap: 0.5rem;
    font-size: 0.8rem; color: var(--text-secondary);
    padding: 0.5rem 0.65rem;
    background: var(--surface-2);
    border-radius: 10px;
}
.perk-icon { color: var(--primary); font-size: 0.95rem; }

/* Share */
.share-row { display: flex; align-items: center; gap: 0.5rem; margin-top: 1rem; }
.share-label { font-size: 0.8rem; color: var(--text-muted); font-weight: 500; }
.share-btn {
    width: 30px; height: 30px; border-radius: 50%;
    background: var(--surface-3); border: none;
    color: var(--text-secondary); font-size: 0.8rem;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: var(--transition);
}
.share-btn:hover { background: var(--primary); color: #fff; }

/* ===== TABS ===== */
.detail-tabs-wrap {
    background: #fff;
    border-radius: 24px;
    border: 1px solid var(--border);
    overflow: hidden;
    margin-top: 2rem;
}
.nav-tabs-custom {
    display: flex;
    border-bottom: 1px solid var(--border);
    padding: 0 1.5rem;
    gap: 0;
    overflow-x: auto;
}
.nav-tab-btn {
    padding: 1rem 1.25rem;
    font-size: 0.875rem; font-weight: 600;
    color: var(--text-muted);
    border: none; background: transparent;
    border-bottom: 3px solid transparent;
    cursor: pointer; transition: var(--transition);
    white-space: nowrap;
    margin-bottom: -1px;
    display: flex; align-items: center; gap: 0.4rem;
}
.nav-tab-btn:hover { color: var(--primary); }
.nav-tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); }
.tab-pane-custom { padding: 2rem; display: none; }
.tab-pane-custom.active { display: block; }

/* Spec table */
.spec-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
.spec-table tr { border-bottom: 1px solid var(--border); }
.spec-table tr:last-child { border-bottom: none; }
.spec-table th {
    width: 35%; background: var(--surface-2);
    padding: 0.75rem 1rem; font-weight: 600;
    color: var(--text-secondary); text-align: left;
}
.spec-table td { padding: 0.75rem 1rem; color: var(--text-primary); }

/* Review */
.review-summary {
    display: flex; align-items: center; gap: 2rem;
    padding: 1.5rem; background: var(--surface-2);
    border-radius: 16px; margin-bottom: 1.5rem;
}
.review-score-big { font-size: 3.5rem; font-weight: 900; color: var(--text-primary); line-height: 1; }
.review-stars-big { color: #fbbf24; font-size: 1.1rem; }
.review-bar-row { display: flex; align-items: center; gap: 0.75rem; font-size: 0.8rem; margin-bottom: 0.3rem; }
.review-bar { flex: 1; height: 8px; background: var(--surface-3); border-radius: 50px; overflow: hidden; }
.review-bar-fill { height: 100%; background: #fbbf24; border-radius: 50px; }
.review-card {
    border: 1px solid var(--border); border-radius: 14px; padding: 1.25rem; margin-bottom: 0.75rem;
}
.reviewer-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: #fff; font-weight: 700; font-size: 0.85rem;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.review-text { font-size: 0.875rem; color: var(--text-secondary); line-height: 1.6; }
.review-empty { text-align: center; padding: 3rem; color: var(--text-muted); }

/* ===== RELATED PRODUCTS ===== */
.related-section { margin-top: 2rem; }
.related-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid var(--border);
    overflow: hidden;
    transition: var(--transition);
    height: 100%;
    display: flex; flex-direction: column;
}
.related-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); border-color: var(--primary); }
.related-img {
    height: 160px; background: var(--surface-2);
    display: flex; align-items: center; justify-content: center;
    padding: 1rem;
    overflow: hidden;
}
.related-img img { max-height: 100%; max-width: 100%; object-fit: contain; transition: transform 0.4s; }
.related-card:hover .related-img img { transform: scale(1.08); }
.related-body { padding: 1rem; flex: 1; display: flex; flex-direction: column; }
.related-name {
    font-weight: 700; font-size: 0.85rem; line-height: 1.35;
    color: var(--text-primary); text-decoration: none;
    min-height: 2.3rem;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    margin-bottom: 0.5rem;
}
.related-name:hover { color: var(--primary); }
.related-footer { display: flex; align-items: center; justify-content: space-between; margin-top: auto; }
.related-price { font-size: 1rem; font-weight: 800; color: var(--primary); }

/* ===== STICKY BUY BAR (mobile) ===== */
.sticky-buy-bar {
    position: fixed; bottom: 0; left: 0; right: 0;
    background: #fff;
    border-top: 1px solid var(--border);
    padding: 0.75rem 1rem;
    display: flex; align-items: center; gap: 0.75rem;
    z-index: 990;
    box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
    transform: translateY(100%);
    transition: transform 0.3s ease;
}
.sticky-buy-bar.visible { transform: translateY(0); }
.sticky-price { font-size: 1.1rem; font-weight: 900; color: var(--primary); }
</style>
@endpush

@section('content')


{{-- ===== MAIN PRODUCT AREA ===== --}}
<div class="product-detail-card row g-0 mb-2">

    {{-- IMAGE COLUMN --}}
    <div class="col-lg-5 d-flex">
        <div class="main-image-wrap w-100" id="mainImgWrap">
            @if($product->original_price && $product->original_price > $product->price)
                <span class="image-badge-corner bg-danger text-white">
                    -{{ round((1 - $product->price / $product->original_price) * 100) }}%
                </span>
            @elseif($product->created_at->gt(now()->subDays(7)))
                <span class="image-badge-corner bg-success text-white">Mới về</span>
            @endif
            <img id="mainImg"
                 src="{{ $product->image_url ?: 'https://placehold.co/600x400?text='.urlencode($product->name) }}"
                 alt="{{ $product->name }}">
        </div>
    </div>

    {{-- INFO COLUMN --}}
    <div class="col-lg-7">
        <div class="product-info-card">

            {{-- Category --}}
            @if($product->category)
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="product-cat-badge">
                    <i class="fa-solid {{ $product->category->icon ?? 'fa-box' }}"></i>
                    {{ $product->category->name }}
                </a>
            @endif

            {{-- Title --}}
            <h1 class="product-title">{{ $product->name }}</h1>

            {{-- Meta --}}
            @if($product->brand)
                <p class="product-meta">
                    Thương hiệu: <strong>{{ $product->brand }}</strong>
                    &nbsp;·&nbsp; SKU: <code>{{ $product->sku }}</code>
                </p>
            @endif

            {{-- Rating --}}
            <div class="product-rating">
                <div class="stars">
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-half"></i>
                </div>
                <span class="rating-score">4.8</span>
                <span class="rating-count">42 đánh giá</span>
                <span class="rating-divider">|</span>
                <span class="sold-count"><i class="bi bi-bag-check me-1"></i>Đã bán 128</span>
            </div>

            {{-- Price --}}
            <div class="price-block">
                <div class="d-flex align-items-center flex-wrap gap-1">
                    <span class="price-current">{{ number_format((float) $product->price, 0, ',', '.') }}đ</span>
                    @if($product->original_price && $product->original_price > $product->price)
                        <span class="price-original">{{ number_format((float) $product->original_price, 0, ',', '.') }}đ</span>
                        <span class="price-discount-badge">-{{ round((1 - $product->price / $product->original_price) * 100) }}%</span>
                    @endif
                </div>
                @if($product->original_price && $product->original_price > $product->price)
                    <div class="price-saved">
                        <i class="bi bi-piggy-bank"></i>
                        Tiết kiệm {{ number_format($product->original_price - $product->price, 0, ',', '.') }}đ so với giá gốc
                    </div>
                @endif
            </div>

            {{-- Stock --}}
            @if($product->stock > 10)
                <span class="stock-indicator in-stock">
                    <i class="bi bi-check-circle-fill"></i> Còn {{ $product->stock }} sản phẩm trong kho
                </span>
            @elseif($product->stock > 0)
                <span class="stock-indicator low-stock">
                    <i class="bi bi-exclamation-circle-fill"></i> Chỉ còn {{ $product->stock }} sản phẩm – Mua nhanh!
                </span>
            @else
                <span class="stock-indicator out-stock">
                    <i class="bi bi-x-circle-fill"></i> Tạm hết hàng
                </span>
            @endif

            {{-- Alert module - Low Stock urgency flashing banner --}}
            @if($product->stock > 0 && $product->stock <= 3)
                <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center gap-3 p-3 mb-4 mt-3" style="border-radius:12px; background: #fffbeb; border: 1px solid #fef3c7 !important;">
                    <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px; height:40px; border-radius:50%; background:#fef3c7; color:#d97706; animation: pulse-warning-detail 1.5s infinite;">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:1.2rem;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1" style="color:#92400e; font-size:0.9rem; margin-bottom: 2px;">Sản phẩm sắp hết hàng!</h6>
                        <p class="mb-0 text-secondary" style="font-size:0.8rem; line-height:1.4;">Chỉ còn <strong>{{ $product->stock }}</strong> sản phẩm cuối cùng trong kho. Hãy đặt hàng ngay để không bỏ lỡ.</p>
                    </div>
                </div>
                <style>
                @keyframes pulse-warning-detail {
                    0% { box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.4); }
                    70% { box-shadow: 0 0 0 8px rgba(217, 119, 6, 0); }
                    100% { box-shadow: 0 0 0 0 rgba(217, 119, 6, 0); }
                }
                </style>
            @endif

            {{-- Description --}}
            @if($product->description)
                <p class="product-desc">{{ $product->description }}</p>
            @endif

            {{-- Quantity --}}
            @if($product->stock > 0)
            <div class="quantity-label">Số lượng:</div>
            <div class="qty-wrap mb-4">
                <button class="qty-btn" type="button" onclick="changeQty(-1)">−</button>
                <input class="qty-input" type="number" id="qtyInput" value="1" min="1" max="{{ $product->stock }}">
                <button class="qty-btn" type="button" onclick="changeQty(1)">+</button>
            </div>
            @endif

            {{-- Actions --}}
            <div class="action-row">
                @if($product->stock > 0)
                    <form method="post" action="{{ route('cart.add', $product) }}" id="addCartForm" class="d-flex flex-grow-1">
                        @csrf
                        <input type="hidden" name="quantity" id="cartQty" value="1">
                        <button type="submit" class="btn-add-to-cart w-100">
                            <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                        </button>
                    </form>
                    <form method="post" action="{{ route('cart.buy-now', $product) }}" id="buyNowForm" class="d-flex flex-grow-1">
                        @csrf
                        <input type="hidden" name="quantity" id="buyQty" value="1">
                        <button type="submit" class="btn-buy-now w-100">
                            <i class="bi bi-lightning-fill"></i> Mua ngay
                        </button>
                    </form>
                @else
                    <button class="btn btn-outline-danger flex-grow-1" style="border-radius:14px; padding:0.85rem 1.5rem; font-weight:700;" data-bs-toggle="modal" data-bs-target="#stockAlertModal">
                        <i class="bi bi-bell-fill"></i> Nhận thông báo khi có hàng
                    </button>
                @endif
                <button class="btn-wishlist-lg" onclick="toggleWishlistLg(this)" title="Yêu thích">
                    <i class="bi bi-heart"></i>
                </button>
            </div>

            {{-- Perks --}}
            <div class="perk-grid">
                <div class="perk-item"><i class="bi bi-truck perk-icon"></i> Giao hàng miễn phí đơn &gt; 500K</div>
                <div class="perk-item"><i class="bi bi-shield-check perk-icon"></i> Bảo hành chính hãng 12–24 tháng</div>
                <div class="perk-item"><i class="bi bi-arrow-return-left perk-icon"></i> Đổi trả trong 30 ngày</div>
                <div class="perk-item"><i class="bi bi-headset perk-icon"></i> Hỗ trợ 24/7 mọi lúc</div>
            </div>

            {{-- Share --}}
            <div class="share-row">
                <span class="share-label">Chia sẻ:</span>
                <button class="share-btn"><i class="bi bi-facebook"></i></button>
                <button class="share-btn"><i class="bi bi-twitter-x"></i></button>
                <button class="share-btn"><i class="bi bi-link-45deg"></i></button>
            </div>
        </div>
    </div>
</div>

{{-- ===== DETAIL TABS ===== --}}
<div class="detail-tabs-wrap">
    <div class="nav-tabs-custom">
        <button class="nav-tab-btn active" onclick="switchTab(this,'tab-desc')">
            <i class="bi bi-file-text"></i> Mô tả
        </button>
        <button class="nav-tab-btn" onclick="switchTab(this,'tab-spec')">
            <i class="bi bi-list-check"></i> Thông số
        </button>
        <button class="nav-tab-btn" onclick="switchTab(this,'tab-reviews')">
            <i class="bi bi-chat-left-dots"></i> Đánh giá
            <span style="background:var(--primary-light);color:var(--primary);font-size:0.7rem;padding:0.1rem 0.45rem;border-radius:50px;font-weight:800;">{{ $approvedReviews->count() }}</span>
        </button>
    </div>

    {{-- Tab: Mô tả --}}
    <div class="tab-pane-custom active" id="tab-desc">
        @if($product->description)
            <div style="font-size:0.9rem;line-height:1.8;color:var(--text-secondary);">
                {!! nl2br(e($product->description)) !!}
            </div>
        @else
            <p class="text-muted">Sản phẩm chưa có mô tả chi tiết.</p>
        @endif
    </div>

    {{-- Tab: Thông số --}}
    <div class="tab-pane-custom" id="tab-spec">
        <table class="spec-table">
            <tbody>
                @if($product->brand)
                <tr><th>Thương hiệu</th><td>{{ $product->brand }}</td></tr>
                @endif
                @if($product->sku)
                <tr><th>Mã SKU</th><td><code>{{ $product->sku }}</code></td></tr>
                @endif
                @if($product->category)
                <tr><th>Danh mục</th><td>{{ $product->category->name }}</td></tr>
                @endif
                <tr><th>Tình trạng kho</th>
                    <td>
                        @if($product->stock > 0)
                            <span style="color:#10b981;font-weight:600;">Còn hàng ({{ $product->stock }} SP)</span>
                        @else
                            <span style="color:#94a3b8;font-weight:600;">Hết hàng</span>
                        @endif
                    </td>
                </tr>
                <tr><th>Giá bán</th><td style="font-weight:700;color:var(--primary)">{{ number_format((float)$product->price, 0, ',', '.') }}đ</td></tr>
                @if($product->original_price)
                <tr><th>Giá gốc</th><td style="text-decoration:line-through;color:var(--text-muted)">{{ number_format((float)$product->original_price, 0, ',', '.') }}đ</td></tr>
                @endif
                <tr><th>Ngày cập nhật</th><td>{{ $product->updated_at->format('d/m/Y') }}</td></tr>
            </tbody>
        </table>
    </div>

    {{-- Tab: Đánh giá --}}
    <div class="tab-pane-custom" id="tab-reviews">
        {{-- Summary --}}
        <div class="review-summary">
            <div class="text-center" style="min-width:80px;">
                <div class="review-score-big">{{ $averageRating ?: '0.0' }}</div>
                <div class="review-stars-big">
                    @for($i=1;$i<=5;$i++)
                        <i class="bi bi-star{{ $i <= round($averageRating) ? '-fill' : ($i - 0.5 <= $averageRating ? '-half' : '') }}"></i>
                    @endfor
                </div>
                <div style="font-size:0.78rem;color:var(--text-muted);margin-top:0.25rem;">{{ $approvedReviews->count() }} đánh giá</div>
            </div>
            <div style="flex:1;">
                @foreach([5=>80, 4=>12, 3=>5, 2=>2, 1=>1] as $star => $pct)
                <div class="review-bar-row">
                    <span>{{ $star }}★</span>
                    <div class="review-bar"><div class="review-bar-fill" style="width:{{ $pct }}%"></div></div>
                    <span style="color:var(--text-muted);">{{ $pct }}%</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Real reviews if available --}}
        @if($approvedReviews->isNotEmpty())
            @foreach($approvedReviews as $review)
            <div class="review-card">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="reviewer-avatar">{{ strtoupper(substr($review->customer_name ?? 'U', 0, 1)) }}</div>
                    <div>
                        <div style="font-weight:700;font-size:0.875rem;">{{ $review->customer_name ?? 'Ẩn danh' }}</div>
                        <div style="color:#fbbf24;font-size:0.75rem;">
                            @for($i=1;$i<=5;$i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <div class="ms-auto" style="font-size:0.75rem;color:var(--text-muted);">
                        {{ $review->created_at->diffForHumans() }}
                    </div>
                </div>
                @if($review->title)
                    <div class="small fw-semibold mt-2">{{ $review->title }}</div>
                @endif
                <p class="review-text mt-1 mb-0">{{ $review->content }}</p>
            </div>
            @endforeach
        @else
            {{-- Demo reviews --}}
            @php
                $demoReviews = [
                    ['name'=>'Nguyễn Văn A','stars'=>5,'text'=>'Sản phẩm rất tốt, đúng như mô tả. Giao hàng nhanh, đóng gói cẩn thận. Sẽ ủng hộ NeoMart lần sau!','ago'=>'2 ngày trước'],
                    ['name'=>'Trần Thị B','stars'=>5,'text'=>'Chất lượng vượt kỳ vọng, giá hợp lý. Shop tư vấn nhiệt tình. Highly recommended!','ago'=>'5 ngày trước'],
                    ['name'=>'Lê Minh C','stars'=>4,'text'=>'Sản phẩm ổn, giao hàng đúng hẹn. Bao bì hơi đơn giản nhưng hàng không trầy xước. 4 sao vì vẫn còn điểm cần cải thiện.','ago'=>'1 tuần trước'],
                ];
            @endphp
            @foreach($demoReviews as $review)
            <div class="review-card">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="reviewer-avatar">{{ strtoupper(substr($review['name'], 0, 1)) }}</div>
                    <div>
                        <div style="font-weight:700;font-size:0.875rem;">{{ $review['name'] }}</div>
                        <div style="color:#fbbf24;font-size:0.75rem;">
                            @for($i=1;$i<=5;$i++)
                                <i class="bi bi-star{{ $i <= $review['stars'] ? '-fill' : '' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <div class="ms-auto" style="font-size:0.75rem;color:var(--text-muted);">{{ $review['ago'] }}</div>
                </div>
                <p class="review-text mb-0">{{ $review['text'] }}</p>
            </div>
            @endforeach
            <p class="text-center text-muted small mt-2">
                <i class="bi bi-info-circle me-1"></i>Đánh giá demo – Dữ liệu thực sẽ hiển thị khi có đánh giá từ khách hàng.
            </p>
        @endif

        {{-- Form viết đánh giá --}}
        <div class="mt-4 pt-4 border-top">
            <h5 class="fw-bold mb-3">Viết đánh giá của bạn</h5>
            <form method="post" action="{{ route('products.reviews.store', $product) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold" for="customer_name">Tên của bạn</label>
                        <input class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name', auth()->user()?->name) }}" required>
                        @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold" for="rating">Số sao</label>
                        <select class="form-select @error('rating') is-invalid @enderror" id="rating" name="rating" required>
                            @for ($star = 5; $star >= 1; $star--)
                                <option value="{{ $star }}" @selected((int) old('rating', 5) === $star)>{{ $star }} sao</option>
                            @endfor
                        </select>
                        @error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold" for="title">Tiêu đề (không bắt buộc)</label>
                        <input class="form-control" id="title" name="title" value="{{ old('title') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold" for="content">Nội dung đánh giá</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="4" placeholder="Nhập tối thiểu 10 ký tự..." required>{{ old('content') }}</textarea>
                        @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <button class="btn btn-primary mt-3 px-4" type="submit">Gửi đánh giá</button>
            </form>
        </div>

    </div>
</div>


{{-- ===== RELATED PRODUCTS ===== --}}
@if ($relatedProducts->isNotEmpty())
<div class="related-section">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
        <h2 style="font-weight:800;font-size:1.25rem;color:var(--text-primary);position:relative;padding-left:1rem;margin:0;">
            <span style="position:absolute;left:0;top:15%;bottom:15%;width:4px;background:linear-gradient(180deg,var(--primary),var(--accent));border-radius:10px;"></span>
            Sản phẩm cùng danh mục
        </h2>
        @if($product->category)
        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
           style="font-size:0.85rem;font-weight:600;color:var(--primary);text-decoration:none;display:flex;align-items:center;gap:0.3rem;">
            Xem thêm <i class="bi bi-arrow-right"></i>
        </a>
        @endif
    </div>
    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3">
        @foreach ($relatedProducts as $rel)
        <div class="col">
            <div class="related-card">
                <div class="related-img">
                    <a href="{{ route('products.show', $rel) }}">
                        <img src="{{ $rel->image_url ?: 'https://placehold.co/400x300?text='.urlencode($rel->name) }}"
                             alt="{{ $rel->name }}" loading="lazy">
                    </a>
                </div>
                <div class="related-body">
                    <a href="{{ route('products.show', $rel) }}" class="related-name">{{ $rel->name }}</a>
                    <div style="color:#fbbf24;font-size:0.72rem;margin-bottom:0.5rem;">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                    </div>
                    <div class="related-footer">
                        <div>
                            <div class="related-price">{{ number_format((float) $rel->price, 0, ',', '.') }}đ</div>
                            @if($rel->original_price && $rel->original_price > $rel->price)
                                <div style="font-size:0.72rem;color:var(--text-muted);text-decoration:line-through;">
                                    {{ number_format((float) $rel->original_price, 0, ',', '.') }}đ
                                </div>
                            @endif
                        </div>
                        <form method="post" action="{{ route('cart.add', $rel) }}">
                            @csrf
                            <button class="btn btn-sm rounded-pill px-2"
                                    style="background:var(--primary-light);color:var(--primary);border:none;font-size:0.8rem;"
                                    type="submit" @disabled($rel->stock <= 0) title="Thêm giỏ">
                                <i class="bi bi-cart-plus"></i>
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

{{-- ===== RECENTLY VIEWED PRODUCTS ===== --}}
@if (isset($recentlyViewedProducts) && $recentlyViewedProducts->isNotEmpty())
<div class="related-section mt-5">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
        <h2 style="font-weight:800;font-size:1.25rem;color:var(--text-primary);position:relative;padding-left:1rem;margin:0;">
            <span style="position:absolute;left:0;top:15%;bottom:15%;width:4px;background:linear-gradient(180deg,var(--primary),var(--accent));border-radius:10px;"></span>
            Sản phẩm đã xem gần đây
        </h2>
    </div>
    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3">
        @foreach ($recentlyViewedProducts as $recentProduct)
        <div class="col">
            <div class="related-card">
                <div class="related-img">
                    <a href="{{ route('products.show', $recentProduct) }}">
                        <img src="{{ $recentProduct->image_url ?: 'https://placehold.co/400x300?text='.urlencode($recentProduct->name) }}"
                             alt="{{ $recentProduct->name }}" loading="lazy">
                    </a>
                </div>
                <div class="related-body">
                    <a href="{{ route('products.show', $recentProduct) }}" class="related-name">{{ $recentProduct->name }}</a>
                    <div style="color:#fbbf24;font-size:0.72rem;margin-bottom:0.5rem;">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                    </div>
                    <div class="related-footer">
                        <div>
                            <div class="related-price">{{ number_format((float) $recentProduct->price, 0, ',', '.') }}đ</div>
                            @if($recentProduct->original_price && $recentProduct->original_price > $recentProduct->price)
                                <div style="font-size:0.72rem;color:var(--text-muted);text-decoration:line-through;">
                                    {{ number_format((float) $recentProduct->original_price, 0, ',', '.') }}đ
                                </div>
                            @endif
                        </div>
                        <form method="post" action="{{ route('cart.add', $recentProduct) }}">
                            @csrf
                            <button class="btn btn-sm rounded-pill px-2"
                                    style="background:var(--primary-light);color:var(--primary);border:none;font-size:0.8rem;"
                                    type="submit" @disabled($recentProduct->stock <= 0) title="Thêm giỏ">
                                <i class="bi bi-cart-plus"></i>
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

{{-- ===== STICKY BUY BAR (mobile) ===== --}}
<div class="sticky-buy-bar d-lg-none" id="stickyBar">
    <div style="flex:1;">
        <div class="sticky-price">{{ number_format((float)$product->price, 0, ',', '.') }}đ</div>
        <div style="font-size:0.75rem;color:var(--text-muted);">{{ Str::limit($product->name, 30) }}</div>
    </div>
    <form method="post" action="{{ route('cart.add', $product) }}" class="d-flex gap-2">
        @csrf
        <button type="submit" class="btn-add-to-cart" style="min-width:unset;flex:unset;padding:0.7rem 1.25rem;font-size:0.85rem;" @disabled($product->stock <= 0)>
            <i class="bi bi-cart-plus"></i> Thêm giỏ
        </button>
    </form>
    <form method="post" action="{{ route('cart.buy-now', $product) }}">
        @csrf
        <button type="submit" class="btn-buy-now" style="flex:unset;padding:0.7rem 1.25rem;font-size:0.85rem;" @disabled($product->stock <= 0)>
            <i class="bi bi-lightning-fill"></i> Mua ngay
        </button>
    </form>
</div>

{{-- Modal nhận thông báo khi có hàng --}}
<div class="modal fade" id="stockAlertModal" tabindex="-1" aria-labelledby="stockAlertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header bg-danger text-white" style="border-top-left-radius:16px; border-top-right-radius:16px; border-bottom:none;">
                <h5 class="modal-title fw-bold" id="stockAlertModalLabel"><i class="bi bi-bell-fill"></i> Đăng ký nhận thông báo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('products.stock-alert', $product) }}">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-secondary small mb-3">NeoMart sẽ gửi thông báo qua Email hoặc Số điện thoại của bạn ngay khi sản phẩm <strong>{{ $product->name }}</strong> có hàng trở lại.</p>
                    
                    <div class="mb-3">
                        <label for="alert_email" class="form-label small fw-bold text-secondary">Địa chỉ Email</label>
                        <input type="email" name="email" id="alert_email" class="form-control" placeholder="example@email.com" value="{{ auth()->user()?->email }}">
                    </div>
                    
                    <div class="mb-2">
                        <label for="alert_phone" class="form-label small fw-bold text-secondary">Số điện thoại</label>
                        <input type="tel" name="phone" id="alert_phone" class="form-control" placeholder="Nhập số điện thoại...">
                    </div>
                    <div class="text-muted" style="font-size:0.75rem;">* Quý khách vui lòng điền ít nhất một trong hai thông tin trên.</div>
                </div>
                <div class="modal-footer p-3 border-top-0">
                    <button type="button" class="btn btn-light fw-bold" style="border-radius:8px;" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger fw-bold" style="border-radius:8px;">Đăng ký ngay</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Bottom padding on mobile for sticky bar --}}
<div class="d-lg-none" style="height:80px;"></div>
@endsection

@push('scripts')
<script>
    // Thumbnail switch
    function switchImg(thumb, url) {
        document.getElementById('mainImg').src = url;
        document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active'));
        thumb.classList.add('active');
    }

    // Quantity control
    function changeQty(delta) {
        const input = document.getElementById('qtyInput');
        const max   = parseInt(input.max) || 99;
        let val     = parseInt(input.value) + delta;
        val = Math.max(1, Math.min(max, val));
        input.value = val;
        document.getElementById('cartQty').value = val;
        document.getElementById('buyQty').value  = val;
    }
    document.getElementById('qtyInput')?.addEventListener('change', function() {
        document.getElementById('cartQty').value = this.value;
        document.getElementById('buyQty').value  = this.value;
    });

    // Wishlist
    function toggleWishlistLg(btn) {
        btn.classList.toggle('active');
        const icon = btn.querySelector('i');
        icon.classList.toggle('bi-heart');
        icon.classList.toggle('bi-heart-fill');
    }

    // Tab switch
    function switchTab(btn, tabId) {
        document.querySelectorAll('.nav-tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-pane-custom').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById(tabId)?.classList.add('active');
    }

    // Sticky bar on mobile
    const stickyBar = document.getElementById('stickyBar');
    const actionRow = document.querySelector('.action-row');
    if (stickyBar && actionRow) {
        const observer = new IntersectionObserver(
            entries => stickyBar.classList.toggle('visible', !entries[0].isIntersecting),
            { threshold: 0 }
        );
        observer.observe(actionRow);
    }
</script>
@endpush
