@extends('layouts.app', ['title' => ($currentCategory ? $currentCategory->name . ' – ' : '') . 'Sản phẩm – NeoMart'])

@push('styles')
<style>
/* ===== BREADCRUMB ===== */
.custom-breadcrumb { margin-bottom: 1.5rem; }
.custom-breadcrumb .breadcrumb { margin: 0; }
.custom-breadcrumb .breadcrumb-item a { color: var(--primary); text-decoration: none; font-size: 0.85rem; }
.custom-breadcrumb .breadcrumb-item.active { font-size: 0.85rem; color: var(--text-muted); }
.custom-breadcrumb .breadcrumb-item + .breadcrumb-item::before { color: var(--text-muted); }

/* ===== TOP SEARCH-SORT BAR ===== */
.top-filter-bar {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 0.85rem 1.25rem;
    margin-bottom: 1.25rem;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.75rem;
}
.top-filter-bar .search-wrap {
    flex: 1;
    min-width: 200px;
    display: flex;
    align-items: center;
    background: var(--surface-2);
    border: 1.5px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    transition: var(--transition);
}
.top-filter-bar .search-wrap:focus-within {
    border-color: var(--primary);
    background: #fff;
    box-shadow: 0 0 0 3px var(--primary-light);
}
.top-filter-bar .search-input {
    flex: 1; border: none; background: transparent;
    padding: 0.5rem 0.9rem;
    font-size: 0.875rem; color: var(--text-primary); outline: none;
}
.top-filter-bar .search-input::placeholder { color: var(--text-muted); }
.top-filter-bar .search-btn {
    background: var(--primary); border: none; color: #fff;
    padding: 0 1rem; height: 100%; font-size: 0.85rem; cursor: pointer;
    transition: background 0.2s;
}
.top-filter-bar .search-btn:hover { background: var(--primary-dark); }
.sort-dropdown-wrap { display: flex; align-items: center; gap: 0.5rem; }
.sort-dropdown-wrap label { font-size: 0.82rem; font-weight: 600; color: var(--text-secondary); white-space: nowrap; }
.sort-select {
    border: 1.5px solid var(--border);
    border-radius: 8px;
    padding: 0.45rem 2rem 0.45rem 0.85rem;
    font-size: 0.85rem;
    color: var(--text-primary);
    background: var(--surface-2) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%236366f1' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E") no-repeat right 0.65rem center;
    -webkit-appearance: none; appearance: none;
    cursor: pointer;
    outline: none;
    transition: var(--transition);
    font-weight: 500;
}
.sort-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); }

/* ===== SIDEBAR – CATEGORY ===== */
.filter-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 1rem;
}
.filter-card-header {
    padding: 0.9rem 1.25rem;
    border-bottom: 1px solid var(--border);
    font-weight: 700;
    font-size: 0.875rem;
    color: var(--text-primary);
    display: flex; align-items: center; gap: 0.5rem;
    background: var(--surface-2);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.filter-card-body { padding: 0; }

.cat-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.7rem 1.25rem;
    border-bottom: 1px solid var(--surface-3);
    text-decoration: none;
    color: var(--text-secondary);
    font-size: 0.875rem;
    font-weight: 500;
    transition: var(--transition);
    gap: 0.5rem;
    border-left: 3px solid transparent;
}
.cat-item:last-child { border-bottom: none; }
.cat-item:hover {
    background: var(--primary-light);
    color: var(--primary);
    border-left-color: var(--primary);
    padding-left: 1.5rem;
}
.cat-item.active {
    background: var(--primary-light);
    color: var(--primary);
    font-weight: 700;
    border-left-color: var(--primary);
    padding-left: 1.5rem;
}
.cat-count {
    background: var(--surface-3);
    color: var(--text-muted);
    font-size: 0.7rem;
    padding: 0.15rem 0.55rem;
    border-radius: 20px;
    font-weight: 700;
    flex-shrink: 0;
}
.cat-item.active .cat-count { background: rgba(99,102,241,0.18); color: var(--primary); }

/* ===== TOOLBAR ===== */
.products-toolbar {
    background: #fff;
    border-radius: 8px;
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
    border-radius: 8px;
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
    position: absolute; top: 0; left: 0;
    font-size: 0.68rem; font-weight: 800;
    padding: 0.2rem 0.55rem; border-radius: 8px 0 8px 0;
}
.grid-wishlist {
    position: absolute; top: 0; right: 0;
    width: 30px; height: 30px; border-radius: 0 8px 0 8px;
    background: rgba(0,0,0,0.05); border: none;
    color: #999; font-size: 0.85rem;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
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
.grid-footer { display: flex; flex-direction: column; margin-top: auto; gap: 0.5rem; }
.grid-price { font-size: 1.15rem; font-weight: 900; color: var(--danger); }
.grid-original { font-size: 0.75rem; color: #999; text-decoration: line-through; }
.grid-stock { font-size: 0.7rem; padding: 0.2rem 0.55rem; border-radius: 6px; font-weight: 600; }
.grid-actions { display: flex; margin-top: 0.75rem; width: 100%; }
.btn-detail { display: none; }
.btn-cart-grid {
    flex: 1; border-radius: 0 0 8px 8px;
    border: none; background: var(--accent);
    color: #000; font-size: 0.85rem; font-weight: bold;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: var(--transition);
    padding: 0.5rem 0;
    text-transform: uppercase;
}
.btn-cart-grid:hover { background: #ffc107; color: #000; }
.btn-cart-grid:disabled { opacity: 0.4; cursor: not-allowed; }

/* ===== LIST VIEW ===== */
.product-list-card {
    background: #fff;
    border-radius: 8px;
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
.list-price { font-size: 1.2rem; font-weight: 900; color: var(--danger); }
.list-original { font-size: 0.8rem; color: #999; text-decoration: line-through; }

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

{{-- ===== TOP SEARCH + SORT BAR ===== --}}
<div class="top-filter-bar">
    {{-- Search --}}
    <form method="get" action="{{ route('products.index') }}" class="d-flex flex-grow-1" style="min-width:200px;max-width:480px;">
        @if($categorySlug)
            <input type="hidden" name="category" value="{{ $categorySlug }}">
        @endif
        @if($sort)
            <input type="hidden" name="sort" value="{{ $sort }}">
        @endif
        <div class="search-wrap w-100">
            <input type="text" name="search" class="search-input" placeholder="Tìm kiếm sản phẩm..." value="{{ $search }}" autocomplete="off">
            <button type="submit" class="search-btn"><i class="bi bi-search"></i></button>
        </div>
    </form>

    {{-- Sort dropdown --}}
    <div class="sort-dropdown-wrap ms-auto">
        <label for="sortSelect"><i class="bi bi-sort-down me-1"></i>Sắp xếp:</label>
        <select id="sortSelect" class="sort-select" onchange="applySort(this.value)">
            <option value="" {{ !$sort ? 'selected' : '' }}>Mới nhất</option>
            <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Giá: Thấp → Cao</option>
            <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Giá: Cao → Thấp</option>
            <option value="name" {{ $sort === 'name' ? 'selected' : '' }}>Tên A → Z</option>
        </select>
    </div>
</div>

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
            </div>
        </div>

        {{-- Products --}}

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
            <a class="btn btn-primary rounded-1 px-4" href="{{ route('products.index') }}">Xem tất cả sản phẩm</a>
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
                                <form method="post" action="{{ route('cart.add', $product) }}" class="w-100">
                                    @csrf
                                    <button class="btn-cart-grid" type="submit" @disabled($product->stock <= 0) title="Thêm vào giỏ">
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
                        <a class="btn btn-outline-primary btn-sm rounded-1 px-3" href="{{ route('products.show', $product) }}">
                            <i class="bi bi-eye me-1"></i>Chi tiết
                        </a>
                        <form method="post" action="{{ route('cart.add', $product) }}">
                            @csrf
                            <button class="btn btn-primary btn-sm rounded-1 px-3" style="background:var(--accent);color:#000;border:none;font-weight:bold;" type="submit" @disabled($product->stock <= 0)>
                                CHỌN MUA
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

    // Sort dropdown redirect
    function applySort(val) {
        const url = new URL(window.location.href);
        if (val) url.searchParams.set('sort', val);
        else url.searchParams.delete('sort');
        window.location.href = url.toString();
    }
</script>
@endpush
