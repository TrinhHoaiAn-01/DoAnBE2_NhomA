@extends('layouts.app', ['title' => 'Trang chủ - NeoMart'])

@section('content')
<style>
    .carousel-item img { height: 400px; object-fit: cover; }
    .carousel-caption { background: rgba(0,0,0,0.3); border-radius: 20px; padding: 2rem; }

    .flash-sale-card { background: #1a202c; border-radius: 24px; padding: 2rem; color: white; margin-bottom: 3rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    .timer-box { background: #ef4444; padding: 0.5rem 0.8rem; border-radius: 10px; font-weight: 800; min-width: 50px; display: inline-block; text-align: center; font-size: 1.1rem; }
    .flash-item { background: rgba(255,255,255,0.05); border-radius: 20px; padding: 1.5rem; transition: all 0.3s ease; border: 1px solid rgba(255,255,255,0.1); text-align: center; }
    .flash-item:hover { background: rgba(255,255,255,0.1); transform: translateY(-5px); }

    .hero-banner { background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%); border-radius: 24px; padding: 4rem 2rem; color: white; margin-bottom: 3rem; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(13,110,253,0.2); }
    .hero-content { position: relative; z-index: 1; }
    .hero-img-wrapper { perspective: 1000px; }
    .floating-img { animation: floating 6s ease-in-out infinite; border: 8px solid rgba(255,255,255,0.1); backdrop-filter: blur(10px); }
    @keyframes floating {
        0% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(2deg); }
        100% { transform: translateY(0px) rotate(0deg); }
    }

    .group-card { transition: all 0.3s ease; border: 1px solid transparent !important; overflow: hidden; }
    .group-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; border-color: #0d6efd !important; }

    .product-card { border: none; border-radius: 20px; background: white; transition: all 0.3s ease; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
    .product-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
    .product-img-wrapper { position: relative; padding: 1.5rem; background: #f8fafc; display: flex; align-items: center; justify-content: center; height: 220px; }
    .product-img-wrapper img { max-height: 100%; max-width: 100%; object-fit: contain; transition: transform 0.5s ease; }
    .product-card:hover .product-img-wrapper img { transform: scale(1.1); }
    .badge-custom { position: absolute; top: 1rem; left: 1rem; padding: 0.4rem 1rem; border-radius: 10px; font-size: 0.75rem; font-weight: 600; z-index: 10; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .product-info { padding: 1.5rem; }
    .product-name { font-weight: 700; font-size: 1rem; margin-bottom: 0.5rem; color: #1a202c; height: 2.5rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; text-decoration: none; line-height: 1.3; }
    .product-name:hover { color: #0d6efd; }
    .product-price { font-size: 1.25rem; font-weight: 800; color: #0d6efd; }
    .btn-cart { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: #f1f5f9; color: #64748b; transition: all 0.2s; border: none; cursor: pointer; }
    .btn-cart:hover { background: #0d6efd; color: white; }
    .section-title { font-weight: 800; color: #1a202c; margin-bottom: 2rem; position: relative; padding-left: 1.25rem; }
    .section-title::before { content: ''; position: absolute; left: 0; top: 0.5rem; bottom: 0.5rem; width: 4px; background: #0d6efd; border-radius: 10px; }
</style>

<div class="container-fluid px-0">
    <!-- 1. Hero Banner -->
    <div class="hero-banner">
        <div class="row align-items-center hero-content">
            <div class="col-lg-7 px-lg-5">
                <span class="badge bg-white text-primary mb-3 px-3 py-2 rounded-pill fw-bold shadow-sm">Phiên bản 2026 - NeoMart NextGen</span>
                <h1 class="display-4 fw-black mb-4">Trải nghiệm <br> Công nghệ Đỉnh cao</h1>
                <p class="lead text-white-50 mb-5">Hệ thống quản lý và mua sắm công nghệ thông minh, mang lại hiệu quả tối ưu cho doanh nghiệp của bạn.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-lg px-4 fw-bold rounded-pill" style="background: white; color: #0d6efd;">Mua sắm ngay</a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-light btn-lg px-4 fw-bold rounded-pill">Khám phá giải pháp</a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center position-relative">
                <div class="hero-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?q=80&w=800&auto=format&fit=crop"
                         alt="Technology Hero" class="img-fluid rounded-4 shadow-lg floating-img">
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Banner Carousel -->
    @if(count($banners) > 0)
    <div id="homeBanner" class="carousel slide mb-5 shadow-sm overflow-hidden rounded-4" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach($banners as $index => $banner)
                <button type="button" data-bs-target="#homeBanner" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @foreach($banners as $index => $banner)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <img src="{{ $banner['image'] }}" class="d-block w-100" alt="{{ $banner['title'] }}">
                    <div class="carousel-caption d-none d-md-block text-start p-5 bg-dark bg-opacity-25" style="left: 5%; right: auto; bottom: 10%; border-radius: 20px;">
                        <h2 class="display-5 fw-black text-white mb-3">{{ $banner['title'] }}</h2>
                        <a href="{{ $banner['link'] }}" class="btn btn-primary btn-lg rounded-pill px-4 shadow">Mua ngay <i class="bi bi-arrow-right ms-2"></i></a>
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#homeBanner" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeBanner" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>
    @endif

    <!-- 3. Flash Sale -->
    @if($flash_sales->isNotEmpty())
    <div class="flash-sale-card">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div class="d-flex align-items-center gap-3">
                <h4 class="fw-black mb-0 text-danger"><i class="bi bi-lightning-fill"></i> FLASH SALE</h4>
                <div class="d-flex align-items-center gap-2">
                    <span class="small opacity-75 d-none d-sm-inline">Kết thúc sau:</span>
                    <div class="timer-box" id="timer-h">00</div><span class="fw-bold">:</span>
                    <div class="timer-box" id="timer-m">00</div><span class="fw-bold">:</span>
                    <div class="timer-box" id="timer-s">00</div>
                </div>
            </div>
            <a href="{{ route('products.index') }}" class="text-white text-decoration-none small fw-bold">Xem tất cả <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-3">
            @foreach($flash_sales as $product)
                <div class="col-6 col-md-3">
                    <div class="flash-item h-100">
                        <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-white">
                            <img src="{{ $product->image_url ?: 'https://placehold.co/300x200?text='.urlencode($product->name) }}" class="img-fluid mb-3" style="height: 120px; object-fit: contain;" alt="{{ $product->name }}">
                            <div class="small fw-bold text-truncate mb-2">{{ $product->name }}</div>
                            <div class="text-danger fw-black fs-5">{{ number_format((float)$product->price, 0, ',', '.') }}đ</div>
                            @if($product->original_price)
                                <div class="small opacity-50 text-decoration-line-through">{{ number_format((float)$product->original_price, 0, ',', '.') }}đ</div>
                            @endif
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- 4. Danh mục sản phẩm (từ Database) -->
    @if($categories->isNotEmpty())
    <div class="mb-5">
        <h3 class="section-title">Danh mục sản phẩm</h3>
        <div class="row g-4">
            @foreach($categories as $category)
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden group-card">
                        <div class="card-header bg-white border-0 pt-3 px-4 d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3 text-primary">
                                <i class="fa-solid {{ $category->icon ?? 'fa-box' }} fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $category->name }}</h6>
                                <small class="text-muted">{{ $category->products_count }} sản phẩm</small>
                            </div>
                        </div>
                        <div class="card-body px-4 pb-4">
                            @if($category->description)
                                <p class="small text-secondary mb-3">{{ $category->description }}</p>
                            @endif
                            <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill">
                                Xem tất cả <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- 5. Sản phẩm nổi bật (từ Database) -->
    @if($featured_products->isNotEmpty())
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="section-title mb-0">Sản phẩm gợi ý cho bạn</h3>
            <a href="{{ route('products.index') }}" class="btn btn-link text-primary fw-bold text-decoration-none">
                Xem tất cả <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-4">
            @foreach($featured_products as $product)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="product-card h-100 d-flex flex-column">
                        <a href="{{ route('products.show', $product) }}" class="product-img-wrapper d-block text-decoration-none">
                            @if($product->original_price && $product->original_price > $product->price)
                                <span class="badge badge-custom bg-danger">
                                    -{{ round((1 - $product->price / $product->original_price) * 100) }}%
                                </span>
                            @elseif($product->created_at->gt(now()->subDays(7)))
                                <span class="badge badge-custom bg-success">Mới</span>
                            @endif
                            <img src="{{ $product->image_url ?: 'https://placehold.co/400x300?text='.urlencode($product->name) }}" alt="{{ $product->name }}">
                        </a>
                        <div class="product-info flex-grow-1">
                            <div class="text-muted small mb-1">{{ $product->category?->name }}</div>
                            <a href="{{ route('products.show', $product) }}" class="product-name" title="{{ $product->name }}">
                                {{ $product->name }}
                            </a>
                            <div class="d-flex justify-content-between align-items-end mt-3">
                                <div>
                                    <div class="product-price">{{ number_format((float)$product->price, 0, ',', '.') }}đ</div>
                                    @if($product->original_price)
                                        <div class="text-muted small text-decoration-line-through">
                                            {{ number_format((float)$product->original_price, 0, ',', '.') }}đ
                                        </div>
                                    @endif
                                </div>
                                <form method="post" action="{{ route('cart.add', $product) }}">
                                    @csrf
                                    <button type="submit" class="btn-cart" title="Thêm vào giỏ" @disabled($product->stock <= 0)>
                                        <i class="bi bi-plus-lg"></i>
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

    <!-- 6. Tiện ích -->
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="bg-white p-4 rounded-4 shadow-sm border-0 h-100 d-flex align-items-center">
                <div class="row align-items-center">
                    <div class="col-4 text-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-4"><i class="bi bi-truck text-primary fs-1"></i></div>
                    </div>
                    <div class="col-8">
                        <h5 class="fw-bold">Giao hàng siêu tốc</h5>
                        <p class="text-muted small mb-0">Nhận hàng chỉ trong 2 giờ tại khu vực nội thành. Miễn phí vận chuyển cho đơn từ 2 triệu.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="bg-white p-4 rounded-4 shadow-sm border-0 h-100 d-flex align-items-center">
                <div class="row align-items-center">
                    <div class="col-4 text-center">
                        <div class="bg-success bg-opacity-10 p-3 rounded-4"><i class="bi bi-shield-check text-success fs-1"></i></div>
                    </div>
                    <div class="col-8">
                        <h5 class="fw-bold">Bảo hành 24 tháng</h5>
                        <p class="text-muted small mb-0">Chăm sóc sản phẩm trọn đời với gói bảo hành vàng NeoCare độc quyền.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
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
</script>
@endpush
@endsection
