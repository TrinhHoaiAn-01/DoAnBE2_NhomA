@extends('layouts.app', ['title' => ($currentCategory ? $currentCategory->name . ' – ' : '') . 'Sản phẩm – NeoMart'])

@push('styles')
<style>
/* ===== BREADCRUMB ===== */
.custom-breadcrumb { margin-bottom: 1.5rem; }
.custom-breadcrumb .breadcrumb { margin: 0; }
.custom-breadcrumb .breadcrumb-item a { color: var(--primary); text-decoration: none; font-size: 0.85rem; }
.custom-breadcrumb .breadcrumb-item.active { font-size: 0.85rem; color: var(--text-muted); }
.custom-breadcrumb .breadcrumb-item + .breadcrumb-item::before { color: var(--text-muted); }

/* ===== SIDEBAR ===== */
.filter-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid var(--border);
    overflow: hidden;
    margin-bottom: 1rem;
}
.filter-card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border);
    font-weight: 700;
    font-size: 0.9rem;
    color: var(--text-primary);
    display: flex; align-items: center; gap: 0.5rem;
}
.filter-card-body { padding: 0.75rem; }

.cat-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.55rem 0.75rem;
    border-radius: var(--radius-sm);
    text-decoration: none;
    color: var(--text-secondary);
    font-size: 0.875rem;
    font-weight: 500;
    transition: var(--transition);
    gap: 0.5rem;
}
.cat-item:hover { background: var(--primary-light); color: var(--primary); }
.cat-item.active {
    background: var(--primary-light);
    color: var(--primary);
    font-weight: 700;
    border-left: 3px solid var(--primary);
}
.cat-count {
    background: var(--surface-3);
    color: var(--text-muted);
    font-size: 0.72rem;
    padding: 0.1rem 0.5rem;
    border-radius: 50px;
    font-weight: 600;
}
.cat-item.active .cat-count { background: rgba(99,102,241,0.15); color: var(--primary); }

/* Sort radio */
.sort-radio-item {
    display: flex; align-items: center;
    padding: 0.5rem 0.75rem;
    border-radius: var(--radius-sm);
    cursor: pointer;
    gap: 0.6rem;
    font-size: 0.875rem;
    color: var(--text-secondary);
    transition: var(--transition);
    text-decoration: none;
}
.sort-radio-item:hover { background: var(--primary-light); color: var(--primary); }
.sort-radio-item.active { color: var(--primary); font-weight: 600; }
.sort-radio-dot {
    width: 16px; height: 16px;
    border-radius: 50%;
    border: 2px solid var(--border);
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    transition: var(--transition);
}
.sort-radio-item.active .sort-radio-dot {
    border-color: var(--primary);
    background: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-light);
}
.sort-radio-item.active .sort-radio-dot::after {
    content: '';
    width: 6px; height: 6px;
    background: #fff;
    border-radius: 50%;
}

/* ===== TOOLBAR ===== */
.products-toolbar {
    background: #fff;
    border-radius: 16px;
    padding: 0.85rem 1.25rem;
    border: 1px solid var(--border);
    margin-bottom: 1.25rem;
    display: flex; flex-wrap: wrap;
    align-items: center; justify-content: space-between;
    gap: 0.75rem;
}
.toolbar-title { font-weight: 700; font-size: 1.1rem; color: var(--text-primary); }
.toolbar-count { font-size: 0.8rem; color: var(--text-muted); font-weight: 500; }
.view-toggle { display: flex; gap: 0.25rem; }
.view-btn {
    width: 34px; height: 34px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: transparent;
    color: var(--text-muted);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: var(--transition);
    font-size: 0.9rem;
}
.view-btn.active, .view-btn:hover { background: var(--primary); color: #fff; border-color: var(--primary); }

/* ===== PRODUCT GRID ===== */
.product-grid-card {
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    border: 1px solid var(--border);
    transition: var(--transition);
    height: 100%;
    display: flex; flex-direction: column;
}
.product-grid-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary);
}
.grid-img-wrap {
    position: relative;
    background: var(--surface-2);
    height: 195px;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    padding: 1rem;
}
.grid-img-wrap img {
    max-height: 100%; max-width: 100%;
    object-fit: contain;
    transition: transform 0.4s ease;
}
.product-grid-card:hover .grid-img-wrap img { transform: scale(1.07); }
.grid-badge {
    position: absolute; top: 0.65rem; left: 0.65rem;
    font-size: 0.68rem; font-weight: 800;
    padding: 0.2rem 0.55rem; border-radius: 7px;
}
.grid-wishlist {
    position: absolute; top: 0.65rem; right: 0.65rem;
    width: 30px; height: 30px; border-radius: 50%;
    background: #fff; border: none;
    color: #cbd5e1; font-size: 0.85rem;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; box-shadow: var(--shadow-sm);
    transition: var(--transition);
}
.grid-wishlist:hover { color: #ef4444; transform: scale(1.12); }
.grid-wishlist.active { color: #ef4444; }
.grid-body { padding: 1rem 1.1rem 1.2rem; flex: 1; display: flex; flex-direction: column; }
.grid-cat { font-size: 0.7rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 0.3rem; }
.grid-name {
    font-weight: 700; font-size: 0.88rem;
    color: var(--text-primary);
    line-height: 1.4;
    min-height: 2.4rem;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    text-decoration: none; margin-bottom: 0.4rem;
}
.grid-name:hover { color: var(--primary); }
.grid-stars { color: #fbbf24; font-size: 0.72rem; margin-bottom: 0.6rem; }
.grid-stars span { color: var(--text-muted); margin-left: 0.2rem; }
.grid-footer { display: flex; align-items: flex-end; justify-content: space-between; margin-top: auto; gap: 0.5rem; }
.grid-price { font-size: 1.1rem; font-weight: 900; color: var(--primary); }
.grid-original { font-size: 0.75rem; color: var(--text-muted); text-decoration: line-through; }
.grid-stock { font-size: 0.7rem; padding: 0.2rem 0.55rem; border-radius: 20px; font-weight: 600; }
.grid-actions { display: flex; gap: 0.4rem; margin-top: 0.75rem; }
.btn-detail {
    flex: 1; border-radius: 50px; font-size: 0.8rem; font-weight: 600;
    padding: 0.45rem 0;
    border: 1.5px solid var(--primary); color: var(--primary);
    background: transparent; text-decoration: none;
    text-align: center; transition: var(--transition);
    display: flex; align-items: center; justify-content: center; gap: 0.3rem;
}
.btn-detail:hover { background: var(--primary); color: #fff; }
.btn-cart-grid {
    width: 34px; flex-shrink: 0; border-radius: 50px;
    border: none; background: var(--primary);
    color: #fff; font-size: 0.85rem;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: var(--transition);
}
.btn-cart-grid:hover { background: var(--primary-dark); }
.btn-cart-grid:disabled { opacity: 0.4; cursor: not-allowed; }

/* ===== LIST VIEW ===== */
.product-list-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid var(--border);
    transition: var(--transition);
    display: flex; overflow: hidden;
    margin-bottom: 0.75rem;
}
.product-list-card:hover { box-shadow: var(--shadow-md); border-color: var(--primary); }
.list-img-wrap {
    width: 160px; flex-shrink: 0;
    background: var(--surface-2);
    display: flex; align-items: center; justify-content: center;
    padding: 1rem;
}
.list-img-wrap img { max-width: 100%; max-height: 120px; object-fit: contain; }
.list-body { flex: 1; padding: 1.1rem 1.25rem; display: flex; flex-direction: column; }
.list-name { font-weight: 700; font-size: 1rem; color: var(--text-primary); text-decoration: none; margin-bottom: 0.3rem; }
.list-name:hover { color: var(--primary); }
.list-cat { font-size: 0.78rem; color: var(--text-muted); margin-bottom: 0.5rem; }
.list-actions { display: flex; align-items: center; gap: 0.75rem; margin-top: auto; flex-wrap: wrap; }
.list-price { font-size: 1.2rem; font-weight: 900; color: var(--primary); }
.list-original { font-size: 0.8rem; color: var(--text-muted); text-decoration: line-through; }

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center; padding: 4rem 2rem;
    background: #fff; border-radius: 20px;
    border: 1px solid var(--border);
}
.empty-icon { font-size: 3.5rem; color: var(--text-muted); margin-bottom: 1rem; }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<nav class="custom-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i>Trang chủ</a></li>
        <li class="breadcrumb-item @if(!$currentCategory) active @endif">
            @if($currentCategory)
                <a href="{{ route('products.index') }}">Sản phẩm</a>
            @else
                Sản phẩm
            @endif
        </li>
        @if($currentCategory)
            <li class="breadcrumb-item active">{{ $currentCategory->name }}</li>
        @endif
    </ol>
</nav>

<div class="row g-4">
    {{-- ===== SIDEBAR ===== --}}
    <div class="col-lg-3">

        {{-- Danh mục --}}
        <div class="filter-card">
            <div class="filter-card-header">
                <i class="bi bi-grid text-primary"></i> Danh mục
            </div>
            <div class="filter-card-body">
                <a href="{{ route('products.index') }}"
                   class="cat-item {{ !$categorySlug ? 'active' : '' }}">
                    <span><i class="bi bi-box-seam me-1 opacity-60"></i> Tất cả sản phẩm</span>
                    <span class="cat-count">{{ $products->count() }}</span>
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                       class="cat-item {{ $categorySlug === $cat->slug ? 'active' : '' }}">
                        <span>
                            <i class="fa-solid {{ $cat->icon ?? 'fa-box' }} me-1 opacity-70 small"></i>
                            {{ $cat->name }}
                        </span>
                        <span class="cat-count">{{ $cat->products_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Tìm kiếm --}}
        <div class="filter-card">
            <div class="filter-card-header">
                <i class="bi bi-search text-primary"></i> Tìm kiếm
            </div>
            <div class="filter-card-body">
                <form method="get" action="{{ route('products.index') }}">
                    @if($categorySlug)
                        <input type="hidden" name="category" value="{{ $categorySlug }}">
                    @endif
                    <div class="input-group input-group-sm" style="border-radius:50px;overflow:hidden;border:1.5px solid var(--border);">
                        <input type="text" name="search" class="form-control border-0 bg-white"
                               placeholder="Tên sản phẩm..." value="{{ $search }}"
                               style="font-size:0.85rem;">
                        <button class="btn btn-primary border-0 px-3" type="submit">
                            <i class="bi bi-search" style="font-size:0.8rem;"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sắp xếp --}}
        <div class="filter-card">
            <div class="filter-card-header">
                <i class="bi bi-sort-down text-primary"></i> Sắp xếp
            </div>
            <div class="filter-card-body">
                @php
                    $sortOptions = [
                        '' => ['label' => 'Mới nhất', 'icon' => 'bi-clock'],
                        'price_asc' => ['label' => 'Giá: Thấp → Cao', 'icon' => 'bi-sort-numeric-up'],
                        'price_desc' => ['label' => 'Giá: Cao → Thấp', 'icon' => 'bi-sort-numeric-down'],
                        'name' => ['label' => 'Tên A → Z', 'icon' => 'bi-sort-alpha-up'],
                    ];
                @endphp
                @foreach($sortOptions as $key => $opt)
                    <a href="{{ route('products.index', array_filter(['category' => $categorySlug, 'search' => $search, 'sort' => $key ?: null])) }}"
                       class="sort-radio-item {{ $sort === $key ? 'active' : '' }}">
                        <span class="sort-radio-dot"></span>
                        <i class="bi {{ $opt['icon'] }} small opacity-70"></i>
                        {{ $opt['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ===== PRODUCT AREA ===== --}}
    <div class="col-lg-9">

        {{-- Toolbar --}}
        <div class="products-toolbar">
            <div>
                <div class="toolbar-title">
                    @if($currentCategory) {{ $currentCategory->name }}
                    @elseif($search) Kết quả: "{{ $search }}"
                    @else Tất cả sản phẩm
                    @endif
                </div>
                <div class="toolbar-count">{{ $products->count() }} sản phẩm</div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="small text-muted d-none d-sm-inline">Hiển thị:</span>
                <div class="view-toggle">
                    <button class="view-btn active" id="btn-grid" onclick="setView('grid')" title="Lưới">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </button>
                    <button class="view-btn" id="btn-list" onclick="setView('list')" title="Danh sách">
                        <i class="bi bi-list-ul"></i>
                    </button>
                </div>
                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 d-none d-sm-flex align-items-center gap-1">
                    <i class="bi bi-bag"></i> Giỏ hàng
                </a>
            </div>
        </div>

        {{-- Products --}}
        @forelse ($products as $product)

            {{-- GRID VIEW --}}
            <div class="product-item-grid" id="view-grid">
            </div>

            {{-- LIST VIEW --}}
            <div class="product-item-list d-none" id="view-list">
            </div>

        @empty
        @endforelse

        {{-- Grid + List rendered together --}}
        @if($products->isEmpty())
        <div class="empty-state">
            <div class="empty-icon"><i class="bi bi-inbox"></i></div>
            <h5 class="fw-bold mb-2">Không tìm thấy sản phẩm</h5>
            <p class="text-muted mb-4">
                @if($search) Không có sản phẩm nào phù hợp với "{{ $search }}".
                @elseif($currentCategory) Danh mục "{{ $currentCategory->name }}" chưa có sản phẩm nào.
                @else Cửa hàng chưa có sản phẩm nào đang bán.
                @endif
            </p>
            <a class="btn btn-primary rounded-pill px-4" href="{{ route('products.index') }}">Xem tất cả sản phẩm</a>
        </div>
        @else
        {{-- Grid View --}}
        <div id="grid-view">
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3">
                @foreach ($products as $product)
                <div class="col">
                    <div class="product-grid-card">
                        <div class="grid-img-wrap">
                            @if($product->original_price && $product->original_price > $product->price)
                                <span class="grid-badge bg-danger text-white">
                                    -{{ round((1 - $product->price / $product->original_price) * 100) }}%
                                </span>
                            @elseif($product->created_at->gt(now()->subDays(7)))
                                <span class="grid-badge bg-success text-white">Mới</span>
                            @endif
                            <button class="grid-wishlist" onclick="toggleWishlist(this)" title="Yêu thích">
                                <i class="bi bi-heart"></i>
                            </button>
                            <a href="{{ route('products.show', $product) }}">
                                <img src="{{ $product->image_url ?: 'https://placehold.co/400x300?text='.urlencode($product->name) }}"
                                     alt="{{ $product->name }}" loading="lazy">
                            </a>
                        </div>
                        <div class="grid-body">
                            <div class="grid-cat">{{ $product->category?->name }}</div>
                            <a href="{{ route('products.show', $product) }}" class="grid-name">{{ $product->name }}</a>
                            <div class="grid-stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                                <span>({{ rand(5, 80) }})</span>
                            </div>
                            <div class="grid-footer">
                                <div>
                                    <div class="grid-price">{{ number_format((float) $product->price, 0, ',', '.') }}đ</div>
                                    @if ($product->original_price)
                                        <div class="grid-original">{{ number_format((float) $product->original_price, 0, ',', '.') }}đ</div>
                                    @endif
                                </div>
                                <span class="grid-stock {{ $product->stock > 0 ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary' }}">
                                    {{ $product->stock > 0 ? 'Còn hàng' : 'Hết hàng' }}
                                </span>
                            </div>
                            <div class="grid-actions">
                                <a class="btn-detail" href="{{ route('products.show', $product) }}">
                                    <i class="bi bi-eye"></i> Chi tiết
                                </a>
                                <form method="post" action="{{ route('cart.add', $product) }}">
                                    @csrf
                                    <button class="btn-cart-grid" type="submit" @disabled($product->stock <= 0) title="Thêm vào giỏ">
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

        {{-- List View --}}
        <div id="list-view" class="d-none">
            @foreach ($products as $product)
            <div class="product-list-card">
                <div class="list-img-wrap">
                    <a href="{{ route('products.show', $product) }}">
                        <img src="{{ $product->image_url ?: 'https://placehold.co/300x200?text='.urlencode($product->name) }}"
                             alt="{{ $product->name }}" loading="lazy">
                    </a>
                </div>
                <div class="list-body">
                    <div class="list-cat">{{ $product->category?->name }}</div>
                    <a href="{{ route('products.show', $product) }}" class="list-name">{{ $product->name }}</a>
                    <div class="grid-stars mb-2">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                        <span style="color:var(--text-muted);font-size:0.72rem;">({{ rand(5,80) }})</span>
                    </div>
                    <div class="list-actions">
                        <div>
                            <div class="list-price">{{ number_format((float) $product->price, 0, ',', '.') }}đ</div>
                            @if($product->original_price)
                                <div class="list-original">{{ number_format((float) $product->original_price, 0, ',', '.') }}đ</div>
                            @endif
                        </div>
                        <span class="grid-stock {{ $product->stock > 0 ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary' }}">
                            {{ $product->stock > 0 ? 'Còn hàng' : 'Hết hàng' }}
                        </span>
                        <a class="btn btn-outline-primary btn-sm rounded-pill px-3" href="{{ route('products.show', $product) }}">
                            <i class="bi bi-eye me-1"></i>Chi tiết
                        </a>
                        <form method="post" action="{{ route('cart.add', $product) }}">
                            @csrf
                            <button class="btn btn-primary btn-sm rounded-pill px-3" type="submit" @disabled($product->stock <= 0)>
                                <i class="bi bi-cart-plus me-1"></i>Thêm giỏ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function setView(mode) {
        const gridView = document.getElementById('grid-view');
        const listView = document.getElementById('list-view');
        const btnGrid  = document.getElementById('btn-grid');
        const btnList  = document.getElementById('btn-list');
        if (mode === 'grid') {
            gridView?.classList.remove('d-none');
            listView?.classList.add('d-none');
            btnGrid?.classList.add('active');
            btnList?.classList.remove('active');
            localStorage.setItem('productView', 'grid');
        } else {
            listView?.classList.remove('d-none');
            gridView?.classList.add('d-none');
            btnList?.classList.add('active');
            btnGrid?.classList.remove('active');
            localStorage.setItem('productView', 'list');
        }
    }

    // Restore view preference
    const savedView = localStorage.getItem('productView') || 'grid';
    if (savedView === 'list') setView('list');

    function toggleWishlist(btn) {
        btn.classList.toggle('active');
        const icon = btn.querySelector('i');
        icon.classList.toggle('bi-heart');
        icon.classList.toggle('bi-heart-fill');
    }
</script>
@endpush
