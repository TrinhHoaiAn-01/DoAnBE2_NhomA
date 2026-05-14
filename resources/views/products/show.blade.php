@extends('layouts.app', ['title' => $product->name . ' - NeoMart'])

@section('content')
<style>
    .product-main-img { width: 100%; aspect-ratio: 4/3; object-fit: contain; background: #f8fafc; border-radius: 16px; padding: 2rem; }
    .spec-table th { background: #f8fafc; font-weight: 600; width: 40%; }
    .qty-input { max-width: 100px; }
    .related-card { transition: all 0.3s ease; border-radius: 16px; overflow: hidden; }
    .related-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
    .related-card img { height: 160px; object-fit: contain; padding: 1rem; background: #f8fafc; }
</style>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Sản phẩm</a></li>
        @if($product->category)
            <li class="breadcrumb-item">
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="text-decoration-none">{{ $product->category->name }}</a>
            </li>
        @endif
        <li class="breadcrumb-item active">{{ $product->name }}</li>
    </ol>
</nav>

<div class="row g-4 mb-5">
    <!-- Hình ảnh sản phẩm -->
    <div class="col-lg-5">
        <div class="bg-white rounded-4 shadow-sm overflow-hidden p-3">
            <img class="product-main-img" src="{{ $product->image_url ?: 'https://placehold.co/600x400?text='.urlencode($product->name) }}" alt="{{ $product->name }}">
        </div>
    </div>

    <!-- Thông tin sản phẩm -->
    <div class="col-lg-7">
        <div class="bg-white rounded-4 shadow-sm p-4 h-100">
            @if($product->category)
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="badge bg-primary bg-opacity-10 text-primary text-decoration-none mb-2">
                    <i class="fa-solid {{ $product->category->icon ?? 'fa-box' }} me-1"></i>{{ $product->category->name }}
                </a>
            @endif

            <h1 class="h2 fw-bold mb-2">{{ $product->name }}</h1>

            @if($product->brand)
                <p class="text-muted mb-3">Thương hiệu: <strong>{{ $product->brand }}</strong> · SKU: <code>{{ $product->sku }}</code></p>
            @endif

            <div class="d-flex align-items-end gap-3 mb-4">
                <div class="display-6 fw-bold text-primary">{{ number_format((float) $product->price, 0, ',', '.') }}đ</div>
                @if ($product->original_price && $product->original_price > $product->price)
                    <div class="h5 text-muted text-decoration-line-through mb-2">{{ number_format((float) $product->original_price, 0, ',', '.') }}đ</div>
                    <span class="badge bg-danger fs-6 mb-2">-{{ round((1 - $product->price / $product->original_price) * 100) }}%</span>
                @endif
            </div>

            <div class="mb-4">
                @if($product->stock > 10)
                    <span class="badge text-bg-success fs-6"><i class="bi bi-check-circle me-1"></i>Còn {{ $product->stock }} sản phẩm</span>
                @elseif($product->stock > 0)
                    <span class="badge text-bg-warning fs-6"><i class="bi bi-exclamation-circle me-1"></i>Chỉ còn {{ $product->stock }} sản phẩm</span>
                @else
                    <span class="badge text-bg-secondary fs-6"><i class="bi bi-x-circle me-1"></i>Hết hàng</span>
                @endif
            </div>

            @if($product->description)
                <p class="text-secondary mb-4">{{ $product->description }}</p>
            @endif

            <!-- Nút hành động -->
            <div class="d-flex flex-wrap gap-3">
                <form class="d-flex gap-2" method="post" action="{{ route('cart.add', $product) }}">
                    @csrf
                    <input class="form-control qty-input" type="number" name="quantity" value="1" min="1" max="{{ max($product->stock, 1) }}">
                    <button class="btn btn-primary px-4 rounded-pill" type="submit" @disabled($product->stock <= 0)>
                        <i class="bi bi-cart-plus me-2"></i>Thêm vào giỏ
                    </button>
                </form>
                <form method="post" action="{{ route('cart.buy-now', $product) }}">
                    @csrf
                    <button class="btn btn-outline-primary px-4 rounded-pill" type="submit" @disabled($product->stock <= 0)>
                        <i class="bi bi-lightning me-1"></i>Mua ngay
                    </button>
                </form>
            </div>

            <!-- Tiện ích -->
            <div class="row g-3 mt-4">
                <div class="col-6">
                    <div class="d-flex align-items-center gap-2 text-muted small">
                        <i class="bi bi-truck text-primary"></i> Giao hàng miễn phí đơn > 500k
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex align-items-center gap-2 text-muted small">
                        <i class="bi bi-shield-check text-success"></i> Bảo hành chính hãng
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex align-items-center gap-2 text-muted small">
                        <i class="bi bi-arrow-return-left text-warning"></i> Đổi trả trong 30 ngày
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex align-items-center gap-2 text-muted small">
                        <i class="bi bi-headset text-info"></i> Hỗ trợ 24/7
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sản phẩm liên quan -->
@if ($relatedProducts->isNotEmpty())
    <section>
        <h2 class="h4 fw-bold mb-4" style="padding-left: 1.25rem; border-left: 4px solid #0d6efd; border-radius: 2px;">
            Sản phẩm cùng danh mục
        </h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">
            @foreach ($relatedProducts as $relatedProduct)
                <div class="col">
                    <div class="bg-white shadow-sm related-card h-100">
                        <a href="{{ route('products.show', $relatedProduct) }}" class="text-decoration-none d-block">
                            <img class="w-100" src="{{ $relatedProduct->image_url ?: 'https://placehold.co/400x300?text='.urlencode($relatedProduct->name) }}" alt="{{ $relatedProduct->name }}">
                        </a>
                        <div class="p-3">
                            <a href="{{ route('products.show', $relatedProduct) }}" class="text-decoration-none text-dark">
                                <div class="fw-semibold mb-1" style="min-height: 2.5rem; line-height: 1.3;">{{ $relatedProduct->name }}</div>
                            </a>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-primary fw-bold">{{ number_format((float) $relatedProduct->price, 0, ',', '.') }}đ</div>
                                <form method="post" action="{{ route('cart.add', $relatedProduct) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-primary rounded-pill" type="submit" @disabled($relatedProduct->stock <= 0)>
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif
@endsection
