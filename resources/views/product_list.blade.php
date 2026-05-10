@extends('layouts.admin')

@section('title', 'Danh sách sản phẩm - NeoMart')

@section('content')
<style>
    .filter-bar {
        background: white;
        border-radius: 20px;
        padding: 1.25rem 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        margin-bottom: 2.5rem;
    }
    .filter-item {
        position: relative;
    }
    .filter-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
        display: block;
    }
    .filter-select {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.6rem 1.2rem;
        font-size: 0.95rem;
        font-weight: 600;
        color: #1a202c;
        width: 100%;
        appearance: none;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2364748b' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E") no-repeat right 1rem center;
        transition: all 0.2s;
    }
    .filter-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        outline: none;
    }
    
    .product-card {
        border: none;
        border-radius: 24px;
        background: white;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    }
    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(0,0,0,0.07);
    }
    .product-img-wrapper {
        position: relative;
        padding: 2rem;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 220px;
        overflow: hidden;
    }
    .product-img-wrapper img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.6s ease;
    }
    .product-card:hover .product-img-wrapper img {
        transform: scale(1.15);
    }
    .badge-custom {
        position: absolute;
        top: 1.25rem;
        left: 1.25rem;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 700;
        z-index: 10;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .product-info { padding: 1.5rem; }
    .product-name {
        font-weight: 700;
        font-size: 1.05rem;
        margin-bottom: 0.75rem;
        color: #1a202c;
        height: 2.8rem;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        text-decoration: none;
        line-height: 1.4;
    }
    .product-name:hover { color: #0d6efd; }
    .product-price {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0d6efd;
    }
    
    .status-badge {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.35rem 0.8rem;
        border-radius: 100px;
    }
    .status-in { background: #dcfce7; color: #166534; }
    .status-out { background: #fee2e2; color: #991b1b; }
    
    .pagination-custom .page-link {
        border: none;
        color: #64748b;
        margin: 0 4px;
        border-radius: 12px !important;
        font-weight: 700;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }
    .pagination-custom .page-item.active .page-link {
        background: #0d6efd;
        color: white;
        box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3);
    }
    .pagination-custom .page-link:hover:not(.active) {
        background: #f1f5f9;
        color: #1a202c;
    }
</style>

<div class="container-fluid py-4">
    <!-- Top Filter Bar -->
    <div class="filter-bar">
        <div class="row g-4 align-items-end">
            <div class="col-lg-4">
                <div class="filter-item">
                    <label class="filter-label">Theo danh mục</label>
                    <select class="filter-select" onchange="location = this.value;">
                        <option value="{{ route('product.list') }}">Tất cả sản phẩm</option>
                        @foreach($category_groups as $group)
                            <optgroup label="{{ $group['name'] }}">
                                <option value="{{ route('product.list', ['category' => $group['name']]) }}" {{ request('category') == $group['name'] ? 'selected' : '' }}>
                                    Tất cả {{ $group['name'] }}
                                </option>
                                @foreach($group['items'] as $item)
                                    <option value="{{ route('product.list', ['category' => $item['name']]) }}" {{ request('category') == $item['name'] ? 'selected' : '' }}>
                                        {{ $item['name'] }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="filter-item">
                    <label class="filter-label">Sắp xếp theo</label>
                    <select class="filter-select" onchange="location = this.value;">
                        <option value="{{ route('product.list', array_merge(request()->query(), ['sort' => 'newest'])) }}" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="{{ route('product.list', array_merge(request()->query(), ['sort' => 'price_asc'])) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                        <option value="{{ route('product.list', array_merge(request()->query(), ['sort' => 'price_desc'])) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 ms-auto text-end">
                <p class="text-muted small mb-1">Tìm thấy <strong>{{ $total }}</strong> sản phẩm</p>
                <div class="d-inline-flex bg-light p-1 rounded-3">
                    <button class="btn btn-sm btn-white shadow-sm border-0 px-3"><i class="bi bi-grid-3x3-gap-fill"></i></button>
                    <button class="btn btn-sm text-muted border-0 px-3"><i class="bi bi-list-task"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="row g-4">
        @foreach($products as $product)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="product-card h-100 d-flex flex-column">
                    <a href="{{ route('product.detail', $product['id']) }}" class="product-img-wrapper text-decoration-none d-block">
                        <span class="badge badge-custom {{ $product['tag'] == 'Bán chạy' ? 'bg-danger' : ($product['tag'] == 'Mới về' ? 'bg-success' : 'bg-primary') }}">
                            {{ $product['tag'] }}
                        </span>
                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                    </a>
                    <div class="product-info flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small fw-bold text-uppercase">{{ $product['category'] }}</span>
                            <span class="status-badge {{ $product['stock'] > 0 ? 'status-in' : 'status-out' }}">
                                {{ $product['stock'] > 0 ? 'Còn hàng' : 'Hết hàng' }}
                            </span>
                        </div>
                        <a href="{{ route('product.detail', $product['id']) }}" class="product-name" title="{{ $product['name'] }}">
                            {{ $product['name'] }}
                        </a>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="product-price">{{ number_format($product['price'], 0, ',', '.') }}đ</div>
                                <div class="rating small text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <span class="text-dark fw-bold ms-1">{{ $product['rating'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 pb-4">
                        <a href="{{ route('product.detail', $product['id']) }}" class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-sm d-block text-decoration-none">
                            <i class="bi bi-cart-plus me-2"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($total > $perPage)
        <nav class="mt-5">
            <ul class="pagination pagination-custom justify-content-center">
                <li class="page-item {{ $page == 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ route('product.list', array_merge(request()->query(), ['page' => $page - 1])) }}">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
                @for($i = 1; $i <= ceil($total / $perPage); $i++)
                    <li class="page-item {{ $page == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ route('product.list', array_merge(request()->query(), ['page' => $i])) }}">{{ $i }}</a>
                    </li>
                @endfor
                <li class="page-item {{ $page >= ceil($total / $perPage) ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ route('product.list', array_merge(request()->query(), ['page' => $page + 1])) }}">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    @endif
</div>
@endsection
