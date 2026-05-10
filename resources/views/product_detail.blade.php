@extends('layouts.admin')

@section('title', $product['name'] . ' - Chi tiết sản phẩm')

@section('content')
<style>
    .product-detail-container {
        background: white;
        border-radius: 24px;
        padding: 2.5rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
        margin-bottom: 3rem;
    }
    .gallery-main {
        background: #f8fafc;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        margin-bottom: 1.5rem;
        height: 450px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .gallery-main img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }
    .gallery-thumb {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        border: 2px solid transparent;
        padding: 5px;
        cursor: pointer;
        background: #f8fafc;
        transition: all 0.2s;
    }
    .gallery-thumb.active {
        border-color: #0d6efd;
        background: white;
    }
    .product-category-badge {
        background: #f1f5f9;
        color: #64748b;
        padding: 0.5rem 1rem;
        border-radius: 100px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .product-title {
        font-weight: 800;
        font-size: 2.25rem;
        color: #1a202c;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
    }
    .rating-stars i {
        color: #fbbf24;
        font-size: 1.1rem;
    }
    .price-large {
        font-size: 2.5rem;
        font-weight: 800;
        color: #0d6efd;
    }
    .price-old {
        font-size: 1.25rem;
        text-decoration: line-through;
        color: #94a3b8;
    }
    .stock-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    .stock-in { background: #dcfce7; color: #166534; }
    .stock-out { background: #fee2e2; color: #991b1b; }
    
    .spec-table tr td {
        padding: 1rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .spec-label {
        color: #64748b;
        font-weight: 500;
        width: 150px;
    }
    .spec-value {
        color: #1a202c;
        font-weight: 600;
    }
    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: #f8fafc;
        padding: 0.5rem;
        border-radius: 15px;
        width: fit-content;
    }
    .btn-qty {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: none;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .btn-qty:hover { background: #0d6efd; color: white; }
    
    .review-card {
        padding: 1.5rem;
        border-radius: 16px;
        background: #f8fafc;
        margin-bottom: 1rem;
    }
</style>

<div class="container-fluid pb-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none">{{ $product['category'] }}</a></li>
            <li class="breadcrumb-item active" aria-current="page text-truncate" style="max-width: 200px;">{{ $product['name'] }}</li>
        </ol>
    </nav>

    <div class="product-detail-container">
        <div class="row g-5">
            <!-- Left: Gallery -->
            <div class="col-lg-6">
                <div class="gallery-main shadow-sm">
                    <img src="{{ $product['images'][0] }}" id="mainImg" alt="{{ $product['name'] }}">
                </div>
                <div class="d-flex gap-3 justify-content-center">
                    @foreach($product['images'] as $index => $img)
                        <img src="{{ $img }}" class="gallery-thumb {{ $index == 0 ? 'active' : '' }}" 
                             onclick="changeImg(this, '{{ $img }}')">
                    @endforeach
                </div>
            </div>

            <!-- Right: Info -->
            <div class="col-lg-6">
                <div class="product-category-badge mb-2">{{ $product['category'] }}</div>
                <h1 class="product-title">{{ $product['name'] }}</h1>
                
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rating-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                        <span class="ms-2 fw-bold text-dark">{{ $product['rating'] }}</span>
                    </div>
                    <div class="text-muted small">|</div>
                    <div class="text-muted small fw-medium">{{ $product['reviews_count'] }} đánh giá khách hàng</div>
                </div>

                <div class="mb-4">
                    <div class="price-old">{{ number_format($product['old_price'], 0, ',', '.') }}đ</div>
                    <div class="price-large">{{ number_format($product['price'], 0, ',', '.') }}đ</div>
                </div>

                <div class="mb-4">
                    <div class="stock-badge {{ $product['stock'] > 0 ? 'stock-in' : 'stock-out' }}">
                        <i class="bi {{ $product['stock'] > 0 ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                        {{ $product['stock'] > 0 ? 'Còn hàng (' . $product['stock'] . ' sản phẩm)' : 'Hết hàng' }}
                    </div>
                </div>

                <p class="text-muted mb-5 lead fs-6" style="line-height: 1.8;">
                    {{ $product['description'] }}
                </p>

                <div class="d-flex align-items-center gap-4 mb-5">
                    <div class="quantity-selector">
                        <button class="btn-qty"><i class="bi bi-dash-lg"></i></button>
                        <span class="px-3 fw-bold">1</span>
                        <button class="btn-qty"><i class="bi bi-plus-lg"></i></button>
                    </div>
                    <button class="btn btn-primary btn-lg px-5 py-3 rounded-pill fw-bold flex-grow-1 shadow-lg">
                        <i class="bi bi-cart-plus me-2"></i> Thêm vào giỏ hàng
                    </button>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 border rounded-4 d-flex align-items-center">
                            <i class="bi bi-arrow-repeat fs-3 text-primary me-3"></i>
                            <div>
                                <div class="fw-bold small">Đổi trả 30 ngày</div>
                                <div class="text-muted extra-small">Nếu có lỗi sản xuất</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded-4 d-flex align-items-center">
                            <i class="bi bi-shield-check fs-3 text-success me-3"></i>
                            <div>
                                <div class="fw-bold small">Chính hãng 100%</div>
                                <div class="text-muted extra-small">Đền 200% nếu hàng giả</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-5 opacity-25">

        <div class="row g-5">
            <!-- Left: Specs -->
            <div class="col-lg-7">
                <h4 class="fw-bold mb-4">Thông số kỹ thuật</h4>
                <table class="w-100 spec-table">
                    @foreach($product['specs'] as $label => $value)
                        <tr>
                            <td class="spec-label">{{ $label }}</td>
                            <td class="spec-value">{{ $value }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>

            <!-- Right: Reviews -->
            <div class="col-lg-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0">Đánh giá khách hàng</h4>
                    <button class="btn btn-link text-primary p-0 fw-bold text-decoration-none small">Viết đánh giá</button>
                </div>

                <div class="review-card">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="fw-bold">Nguyễn Văn A</div>
                        <div class="small text-muted">2 ngày trước</div>
                    </div>
                    <div class="rating-stars mb-2" style="font-size: 0.8rem;">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <p class="small text-muted mb-0">Sản phẩm rất tuyệt vời, giao hàng nhanh chóng, đóng gói cẩn thận. Rất hài lòng!</p>
                </div>

                <div class="review-card">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="fw-bold">Trần Thị B</div>
                        <div class="small text-muted">1 tuần trước</div>
                    </div>
                    <div class="rating-stars mb-2" style="font-size: 0.8rem;">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <p class="small text-muted mb-0">Màu Titan tự nhiên đẹp xuất sắc. Máy chạy cực mượt, camera chụp đêm đỉnh cao.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function changeImg(thumb, src) {
        document.getElementById('mainImg').src = src;
        document.querySelectorAll('.gallery-thumb').forEach(el => el.classList.remove('active'));
        thumb.classList.add('active');
    }
</script>
@endsection
