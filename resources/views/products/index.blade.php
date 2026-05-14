@extends('layouts.app', ['title' => ($currentCategory ? $currentCategory->name . ' - ' : '') . 'Sản phẩm NeoMart'])

@section('content')
<style>
    .product-grid-card { border: none; border-radius: 16px; background: white; transition: all 0.3s ease; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
    .product-grid-card:hover { transform: translateY(-5px); box-shadow: 0 12px 24px rgba(0,0,0,0.08); }
    .product-grid-card .card-img-top { height: 200px; object-fit: contain; padding: 1rem; background: #f8fafc; }
    .filter-sidebar .list-group-item { border: none; padding: 0.6rem 1rem; font-size: 0.9rem; transition: all 0.2s; border-radius: 8px !important; margin-bottom: 2px; }
    .filter-sidebar .list-group-item:hover, .filter-sidebar .list-group-item.active { background: #e8f0fe; color: #0d6efd; font-weight: 600; }
    .filter-sidebar .list-group-item.active { border-left: 3px solid #0d6efd; }
    .discount-badge { position: absolute; top: 0.75rem; left: 0.75rem; z-index: 5; }
</style>

<div class="row g-4">
    <!-- Sidebar: Danh mục + Bộ lọc -->
    <div class="col-lg-3">
        <div class="bg-white rounded-4 shadow-sm p-3 mb-4 filter-sidebar">
            <h6 class="fw-bold mb-3"><i class="bi bi-grid me-2"></i>Danh mục</h6>
            <div class="list-group list-group-flush">
                <a href="{{ route('products.index') }}"
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ !$categorySlug ? 'active' : '' }}">
                    Tất cả sản phẩm
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">{{ $products->count() }}</span>
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $categorySlug === $cat->slug ? 'active' : '' }}">
                        <span><i class="fa-solid {{ $cat->icon ?? 'fa-box' }} me-2 small text-muted"></i>{{ $cat->name }}</span>
                        <span class="badge bg-light text-muted rounded-pill">{{ $cat->products_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Bộ lọc tìm kiếm -->
        <div class="bg-white rounded-4 shadow-sm p-3">
            <h6 class="fw-bold mb-3"><i class="bi bi-search me-2"></i>Tìm kiếm</h6>
            <form method="get" action="{{ route('products.index') }}">
                @if($categorySlug)
                    <input type="hidden" name="category" value="{{ $categorySlug }}">
                @endif
                <div class="input-group input-group-sm mb-3">
                    <input type="text" name="search" class="form-control" placeholder="Tên sản phẩm..." value="{{ $search }}">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>

            <h6 class="fw-bold mb-2 mt-3"><i class="bi bi-sort-down me-2"></i>Sắp xếp</h6>
            <div class="d-flex flex-column gap-1">
                <a href="{{ route('products.index', array_filter(['category' => $categorySlug, 'search' => $search])) }}"
                   class="btn btn-sm {{ !$sort ? 'btn-primary' : 'btn-outline-secondary' }}">Mới nhất</a>
                <a href="{{ route('products.index', array_filter(['category' => $categorySlug, 'search' => $search, 'sort' => 'price_asc'])) }}"
                   class="btn btn-sm {{ $sort === 'price_asc' ? 'btn-primary' : 'btn-outline-secondary' }}">Giá tăng dần</a>
                <a href="{{ route('products.index', array_filter(['category' => $categorySlug, 'search' => $search, 'sort' => 'price_desc'])) }}"
                   class="btn btn-sm {{ $sort === 'price_desc' ? 'btn-primary' : 'btn-outline-secondary' }}">Giá giảm dần</a>
                <a href="{{ route('products.index', array_filter(['category' => $categorySlug, 'search' => $search, 'sort' => 'name'])) }}"
                   class="btn btn-sm {{ $sort === 'name' ? 'btn-primary' : 'btn-outline-secondary' }}">Tên A-Z</a>
            </div>
        </div>
    </div>

    <!-- Lưới sản phẩm -->
    <div class="col-lg-9">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h1 class="h3 fw-bold mb-1">
                    @if($currentCategory)
                        {{ $currentCategory->name }}
                    @elseif($search)
                        Tìm kiếm: "{{ $search }}"
                    @else
                        Tất cả sản phẩm
                    @endif
                </h1>
                <p class="text-muted small mb-0">{{ $products->count() }} sản phẩm được tìm thấy</p>
            </div>
            <a class="btn btn-outline-primary btn-sm rounded-pill px-3" href="{{ route('cart.index') }}">
                <i class="bi bi-cart3 me-1"></i> Xem giỏ hàng
            </a>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
            @forelse ($products as $product)
                <div class="col">
                    <div class="product-grid-card h-100 d-flex flex-column position-relative">
                        @if($product->original_price && $product->original_price > $product->price)
                            <span class="badge bg-danger discount-badge">
                                -{{ round((1 - $product->price / $product->original_price) * 100) }}%
                            </span>
                        @endif
                        <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                            <img class="card-img-top" src="{{ $product->image_url ?: 'https://placehold.co/400x300?text='.urlencode($product->name) }}" alt="{{ $product->name }}">
                        </a>
                        <div class="p-3 flex-grow-1 d-flex flex-column">
                            <div class="small text-muted mb-1">{{ $product->category?->name }}</div>
                            <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">
                                <h6 class="fw-bold mb-2" style="min-height: 2.5rem; line-height: 1.3;">{{ $product->name }}</h6>
                            </a>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <div class="fw-bold text-primary fs-5">{{ number_format((float) $product->price, 0, ',', '.') }}đ</div>
                                    @if ($product->original_price)
                                        <div class="small text-muted text-decoration-line-through">{{ number_format((float) $product->original_price, 0, ',', '.') }}đ</div>
                                    @endif
                                </div>
                                <span class="badge {{ $product->stock > 0 ? 'text-bg-success' : 'text-bg-secondary' }} rounded-pill">
                                    {{ $product->stock > 0 ? 'Còn hàng' : 'Hết hàng' }}
                                </span>
                            </div>
                            <div class="d-flex gap-2 mt-auto">
                                <a class="btn btn-outline-primary btn-sm flex-grow-1 rounded-pill" href="{{ route('products.show', $product) }}">
                                    <i class="bi bi-eye me-1"></i>Chi tiết
                                </a>
                                <form method="post" action="{{ route('cart.add', $product) }}">
                                    @csrf
                                    <button class="btn btn-primary btn-sm rounded-pill px-3" type="submit" @disabled($product->stock <= 0)>
                                        <i class="bi bi-cart-plus me-1"></i>Thêm
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="bg-white rounded-4 shadow-sm p-5 text-center">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <h5 class="fw-bold mt-3">Chưa có sản phẩm</h5>
                        <p class="text-muted">
                            @if($search)
                                Không tìm thấy sản phẩm phù hợp với "{{ $search }}".
                            @elseif($currentCategory)
                                Danh mục "{{ $currentCategory->name }}" hiện chưa có sản phẩm nào.
                            @else
                                Cửa hàng hiện chưa có sản phẩm nào đang bán.
                            @endif
                        </p>
                        <a class="btn btn-primary rounded-pill px-4" href="{{ route('products.index') }}">Xem tất cả sản phẩm</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
