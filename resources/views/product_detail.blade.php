@extends('layouts.admin')

@section('title', $product['name'] . ' - NeoMart')

@section('content')
<style>
    .product-detail-container {
        background: #fff;
        border-radius: 32px;
        padding: 3rem;
        box-shadow: 0 20px 50px rgba(0,0,0,0.03);
    }
    .detail-img-wrapper {
        background: #f8fafc;
        border-radius: 24px;
        padding: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 500px;
        position: sticky;
        top: 2rem;
    }
    .detail-img-wrapper img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.5s ease;
    }
    .detail-img-wrapper:hover img {
        transform: scale(1.05);
    }
    .product-category-badge {
        display: inline-block;
        padding: 0.5rem 1.25rem;
        background: #e0e7ff;
        color: #4338ca;
        border-radius: 100px;
        font-weight: 800;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1.5rem;
    }
    .product-main-title {
        font-size: 2.5rem;
        font-weight: 900;
        color: #1a202c;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        letter-spacing: -1px;
    }
    .detail-price-box {
        background: #f8fafc;
        padding: 2rem;
        border-radius: 24px;
        margin-bottom: 2rem;
    }
    .detail-current-price {
        font-size: 2.25rem;
        font-weight: 900;
        color: #0d6efd;
        margin-bottom: 0.5rem;
    }
    .detail-old-price {
        font-size: 1.1rem;
        color: #94a3b8;
        text-decoration: line-through;
    }
    .spec-table {
        margin-top: 2rem;
    }
    .spec-item {
        display: flex;
        padding: 1rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .spec-label {
        width: 150px;
        color: #64748b;
        font-weight: 600;
    }
    .spec-value {
        color: #1e293b;
        font-weight: 700;
    }
    .action-btn {
        padding: 1.25rem;
        border-radius: 18px;
        font-weight: 800;
        font-size: 1.1rem;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }
    .btn-buy-now {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        border: none;
        color: #fff;
        box-shadow: 0 10px 25px rgba(13, 110, 253, 0.2);
    }
    .btn-buy-now:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(13, 110, 253, 0.3);
        color: #fff;
    }
    .btn-add-cart {
        background: #fff;
        border: 2px solid #e2e8f0;
        color: #1e293b;
    }
    .btn-add-cart:hover {
        border-color: #0d6efd;
        color: #0d6efd;
        background: #f0f7ff;
    }
</style>

<div class="container-fluid py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('product.list') }}" class="text-decoration-none">Sản phẩm</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product['name'] }}</li>
        </ol>
    </nav>

    <div class="product-detail-container">
        <div class="row g-5">
            <!-- Ảnh sản phẩm -->
            <div class="col-lg-6">
                <div class="detail-img-wrapper">
                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" id="mainProductImg">
                </div>
            </div>

            <!-- Thông tin chi tiết -->
            <div class="col-lg-6">
                <span class="product-category-badge">{{ $product['category'] }}</span>
                <h1 class="product-main-title">{{ $product['name'] }}</h1>
                
                <div class="d-flex align-items-center gap-4 mb-4">
                    <div class="rating text-warning">
                        @for($i=1; $i<=5; $i++)
                            <i class="bi bi-star{{ $i <= $product['rating'] ? '-fill' : '' }}"></i>
                        @endfor
                        <span class="text-dark fw-bold ms-2">{{ $product['rating'] }}</span>
                    </div>
                    <div class="text-muted small">
                        <i class="bi bi-chat-left-text me-1"></i> {{ $product['reviews_count'] }} đánh giá
                    </div>
                    <div class="text-muted small">
                        <i class="bi bi-box-seam me-1"></i> 
                        <span class="{{ $product['stock'] > 0 ? 'text-success' : 'text-danger' }} fw-bold">
                            {{ $product['stock'] > 0 ? 'Còn hàng (' . $product['stock'] . ')' : 'Hết hàng' }}
                        </span>
                    </div>
                </div>

                <div class="detail-price-box">
                    @if($product['old_price'] > $product['price'])
                        <div class="detail-old-price">{{ number_format($product['old_price'], 0, ',', '.') }}đ</div>
                    @endif
                    <div class="detail-current-price">{{ number_format($product['price'], 0, ',', '.') }}đ</div>
                    <p class="text-muted small mb-0 mt-2 text-uppercase fw-bold">Miễn phí giao hàng toàn quốc cho đơn hàng từ 10tr</p>
                </div>

                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Mô tả sản phẩm</h5>
                    <p class="text-secondary leading-relaxed">
                        {{ $product['description'] }}
                    </p>
                </div>

                <div class="spec-table mb-5">
                    <h5 class="fw-bold mb-3">Thông số kỹ thuật</h5>
                    @foreach($product['specs'] as $label => $value)
                        <div class="spec-item">
                            <div class="spec-label">{{ $label }}</div>
                            <div class="spec-value">{{ $value }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <form action="{{ route('cart.add', $product['id']) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn action-btn btn-add-cart w-100">
                                <i class="bi bi-cart-plus fs-5"></i> Thêm vào giỏ
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('cart.buy-now', $product['id']) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn action-btn btn-buy-now w-100">
                                <i class="bi bi-lightning-fill fs-5"></i> Mua ngay
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
