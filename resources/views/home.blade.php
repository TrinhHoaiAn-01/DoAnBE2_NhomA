@extends('layouts.admin')

@section('title', 'Trang chủ - NeoMart')

@section('content')
<style>
    .hero-banner {
        background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
        border-radius: 20px;
        padding: 4rem 2rem;
        color: white;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(13, 110, 253, 0.2);
    }
    .hero-content {
        position: relative;
        z-index: 1;
    }
    .hero-img-wrapper {
        perspective: 1000px;
    }
    .floating-img {
        animation: floating 6s ease-in-out infinite;
        border: 8px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
    }
    @keyframes floating {
        0% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(2deg); }
        100% { transform: translateY(0px) rotate(0deg); }
    }
    .group-card {
        transition: all 0.3s ease;
        border: 1px solid transparent !important;
        overflow: hidden;
    }
    .group-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        border-color: #0d6efd !important;
    }
    .group-img {
        height: 120px;
        width: 100%;
        object-fit: cover;
        opacity: 0.8;
        transition: transform 0.5s ease;
    }
    .group-card:hover .group-img {
        transform: scale(1.1);
        opacity: 1;
    }
    .product-card {
        border: none;
        border-radius: 20px;
        background: white;
        transition: all 0.3s ease;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
    }
    .product-img-wrapper {
        position: relative;
        padding: 1.5rem;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 220px;
    }
    .product-img-wrapper img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.5s ease;
    }
    .product-card:hover .product-img-wrapper img {
        transform: scale(1.1);
    }
    .badge-custom {
        position: absolute;
        top: 1rem;
        left: 1rem;
        padding: 0.4rem 1rem;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 10;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .product-info {
        padding: 1.5rem;
    }
    .product-name {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 0.5rem;
        color: #1a202c;
        height: 2.5rem;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        text-decoration: none;
    }
    .product-name:hover {
        color: #0d6efd;
    }
    .product-price {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0d6efd;
    }
    .btn-cart {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        color: #64748b;
        transition: all 0.2s;
        border: none;
    }
    .btn-cart:hover {
        background: #0d6efd;
        color: white;
    }
    .section-title {
        font-weight: 800;
        color: #1a202c;
        margin-bottom: 2rem;
        position: relative;
        padding-left: 1.25rem;
    }
    .section-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0.5rem;
        bottom: 0.5rem;
        width: 4px;
        background: #0d6efd;
        border-radius: 10px;
    }
</style>

<div class="container-fluid">
    <!-- Hero Banner -->
    <div class="hero-banner">
        <div class="row align-items-center hero-content">
            <div class="col-lg-7 px-lg-5">
                <span class="badge bg-white text-primary mb-3 px-3 py-2 rounded-pill fw-bold shadow-sm">Phiên bản 2026 - NeoMart NextGen</span>
                <h1 class="display-4 fw-black mb-4">Trải nghiệm <br> Công nghệ Đỉnh cao</h1>
                <p class="lead text-white-50 mb-5">Hệ thống quản lý và mua sắm công nghệ thông minh, mang lại hiệu quả tối ưu cho doanh nghiệp của bạn.</p>
                <div class="d-flex gap-3">
                    <button class="btn btn-white btn-lg px-4 fw-bold rounded-pill" style="background: white; color: #0d6efd;">Mua sắm ngay</button>
                    <button class="btn btn-outline-light btn-lg px-4 fw-bold rounded-pill">Khám phá giải pháp</button>
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

    <!-- Detailed Category Groups with Images -->
    <div class="mb-5">
        <h3 class="section-title">Danh mục sản phẩm theo nhóm ngành</h3>
        <div class="row g-4">
            @foreach($category_groups as $group)
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden group-card">
                        <img src="{{ $group['image'] }}" class="group-img" alt="{{ $group['name'] }}">
                        <div class="card-header bg-white border-0 pt-3 px-4 d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3 text-primary">
                                <i class="bi {{ $group['icon'] }} fs-5"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-dark">{{ $group['name'] }}</h6>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div class="list-group list-group-flush border-0">
                                @foreach($group['items'] as $item)
                                    <a href="#" class="list-group-item list-group-item-action border-0 px-0 d-flex justify-content-between align-items-center py-2 bg-transparent">
                                        <div class="d-flex align-items-center">
                                            <i class="bi {{ $item['icon'] }} text-muted me-2 small"></i>
                                            <span class="small text-secondary">{{ $item['name'] }}</span>
                                        </div>
                                        <span class="badge bg-light text-muted rounded-pill small">{{ $item['count'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                            <a href="{{ route('product.list', ['category' => $group['name']]) }}" class="btn btn-outline-primary btn-sm w-100 mt-3 rounded-pill">Xem tất cả</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Featured Products -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="section-title mb-0">Sản phẩm gợi ý cho bạn</h3>
            <a href="#" class="btn btn-link text-primary fw-bold text-decoration-none">
                Xem tất cả <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        
        <div class="row g-4">
            @foreach($featured_products as $product)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="product-card">
                        <a href="{{ route('product.detail', $product['id']) }}" class="product-img-wrapper d-block text-decoration-none">
                            <span class="badge badge-custom {{ $product['tag'] == 'Bán chạy' ? 'bg-danger' : ($product['tag'] == 'Mới về' ? 'bg-success' : 'bg-primary') }}">
                                {{ $product['tag'] }}
                            </span>
                            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                        </a>
                        <div class="product-info">
                            <div class="text-muted small mb-1">{{ $product['category'] }}</div>
                            <a href="{{ route('product.detail', $product['id']) }}" class="product-name" title="{{ $product['name'] }}">
                                {{ $product['name'] }}
                            </a>
                            <div class="d-flex justify-content-between align-items-end mt-3">
                                <div>
                                    <div class="product-price">{{ number_format($product['price'], 0, ',', '.') }}đ</div>
                                    <div class="text-muted small">
                                        <i class="bi bi-star-fill text-warning"></i> {{ $product['rating'] }} 
                                        <span class="ms-1">({{ $product['reviews_count'] }})</span>
                                    </div>
                                </div>
                                <button class="btn-cart" title="Thêm vào giỏ">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Overview Statistics -->
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="bg-white p-4 rounded-4 shadow-sm border-0 h-100 d-flex align-items-center">
                <div class="row align-items-center">
                    <div class="col-4">
                        <div class="bg-primary bg-opacity-10 p-4 rounded-4 text-center">
                            <i class="bi bi-truck text-primary fs-1"></i>
                        </div>
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
                    <div class="col-4">
                        <div class="bg-success bg-opacity-10 p-4 rounded-4 text-center">
                            <i class="bi bi-shield-check text-success fs-1"></i>
                        </div>
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
@endsection
