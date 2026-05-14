@extends('layouts.admin')

@section('title', 'Danh sách sản phẩm - NeoMart')

@section('content')
    <style>
        .filter-section {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            margin-bottom: 3rem;
        }
        .filter-title {
            font-size: 0.9rem;
            font-weight: 800;
            color: #1a202c;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .filter-control {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: #475569;
            transition: all 0.3s;
        }
        .filter-control:focus {
            background-color: #fff;
            border-color: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
            outline: none;
        }
        .btn-premium {
            border-radius: 14px;
            padding: 0.75rem 1.5rem;
            font-weight: 700;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-premium-primary {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            border: none;
            color: #fff;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.2);
        }
        .btn-premium-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
            color: #fff;
        }
        .btn-premium-outline {
            background: #fff;
            border: 1px solid #e2e8f0;
            color: #64748b;
        }
        .btn-premium-outline:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #1a202c;
        }

        .product-card {
            border: none;
            border-radius: 28px;
            background: white;
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            position: relative;
        }
        .product-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.08);
        }
        .product-img-wrapper {
            position: relative;
            padding: 2.5rem;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 240px;
        }
        .product-img-wrapper img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
            transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .product-card:hover .product-img-wrapper img {
            transform: scale(1.1) rotate(2deg);
        }
        .badge-premium {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            padding: 0.6rem 1.2rem;
            border-radius: 14px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            z-index: 10;
            backdrop-filter: blur(10px);
        }
        .product-info { padding: 1.75rem; }
        .product-name {
            font-weight: 800;
            font-size: 1.1rem;
            margin-bottom: 0.75rem;
            color: #1a202c;
            height: 3rem;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            text-decoration: none;
            line-height: 1.4;
            transition: color 0.3s;
        }
        .product-name:hover { color: #0d6efd; }
        .product-price {
            font-size: 1.4rem;
            font-weight: 900;
            color: #0d6efd;
            letter-spacing: -0.5px;
        }
        .status-pill {
            font-size: 0.7rem;
            font-weight: 800;
            padding: 0.4rem 1rem;
            border-radius: 100px;
            text-transform: uppercase;
        }
        .status-in { background: #dcfce7; color: #166534; }
        .status-out { background: #fee2e2; color: #991b1b; }
        
        .pagination-premium .page-link {
            border: none;
            background: #fff;
            color: #64748b;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px !important;
            margin: 0 5px;
            font-weight: 800;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            transition: all 0.3s;
        }
        .pagination-premium .page-item.active .page-link {
            background: #0d6efd;
            color: #fff;
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.3);
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Bộ lọc lên đầu -->
        <div class="filter-section">
            <div class="filter-title">
                <i class="bi bi-funnel-fill text-primary"></i> Bộ lọc sản phẩm
            </div>
            
            <form action="{{ route('product.list') }}" method="GET" class="row g-3">
                <!-- Tìm kiếm -->
                <div class="col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-4 ps-3 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 rounded-end-4 filter-control" 
                               placeholder="Tìm tên sản phẩm hoặc mô tả..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Danh mục -->
                <div class="col-md-6 col-lg-2">
                    <select name="category" class="form-select filter-control">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Thương hiệu -->
                <div class="col-md-6 col-lg-2">
                    <select name="brand" class="form-select filter-control">
                        <option value="">Thương hiệu</option>
                        @foreach($brands as $b)
                            <option value="{{ $b }}" {{ request('brand') == $b ? 'selected' : '' }}>
                                {{ $b }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Sắp xếp -->
                <div class="col-md-6 col-lg-2">
                    <select name="sort" class="form-select filter-control">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                    </select>
                </div>

                <!-- Nút bấm -->
                <div class="col-md-6 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-premium btn-premium-primary w-100">
                        Lọc
                    </button>
                    <a href="{{ route('product.list') }}" class="btn btn-premium btn-premium-outline" title="Xóa lọc">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>

                <!-- Giá & Khuyến mãi (Hàng dưới) -->
                <div class="col-12 mt-4 pt-3 border-top d-flex flex-wrap align-items-center gap-4">
                    <div class="d-flex align-items-center gap-3">
                        <span class="small fw-bold text-muted text-uppercase">Khoảng giá:</span>
                        <input type="number" name="min_price" class="form-control form-control-sm filter-control py-1 px-3" style="width: 140px" placeholder="Từ..." value="{{ request('min_price') }}">
                        <span class="text-muted">-</span>
                        <input type="number" name="max_price" class="form-control form-control-sm filter-control py-1 px-3" style="width: 140px" placeholder="Đến..." value="{{ request('max_price') }}">
                    </div>
                    
                    <div class="vr mx-2 text-muted opacity-25 d-none d-lg-block"></div>

                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="on_sale" id="onSaleTop" value="1" {{ request('on_sale') ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold text-muted small" for="onSaleTop">ĐANG KHUYẾN MÃI</label>
                    </div>

                    <div class="ms-auto">
                        <span class="text-muted small">Tìm thấy <strong>{{ $total }}</strong> kết quả</span>
                    </div>
                </div>
            </form>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4 mb-5">
            @forelse($products as $product)
                <div class="col">
                    <div class="product-card h-100 d-flex flex-column">
                        <a href="{{ route('product.detail', $product['id']) }}" class="product-img-wrapper text-decoration-none">
                            @php
                                $tagClass = 'bg-primary text-white';
                                if($product['tag'] == 'Bán chạy') $tagClass = 'bg-danger text-white';
                                if($product['tag'] == 'Mới về') $tagClass = 'bg-success text-white';
                                if($product['tag'] == 'Ưu đãi') $tagClass = 'bg-warning text-dark';
                            @endphp
                            <span class="badge-premium {{ $tagClass }}">
                                {{ $product['tag'] }}
                            </span>
                            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                        </a>
                        
                        <div class="product-info flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small fw-bold text-uppercase">{{ $product['category'] }}</span>
                                <span class="status-pill {{ $product['stock'] > 0 ? 'status-in' : 'status-out' }}">
                                    {{ $product['stock'] > 0 ? 'Sẵn hàng' : 'Hết hàng' }}
                                </span>
                            </div>
                            
                            <a href="{{ route('product.detail', $product['id']) }}" class="product-name" title="{{ $product['name'] }}">
                                {{ $product['name'] }}
                            </a>
                            
                            <div class="mt-auto d-flex justify-content-between align-items-end">
                                <div>
                                    @if(isset($product['old_price']) && $product['old_price'] > $product['price'])
                                        <div class="text-muted small text-decoration-line-through mb-1">
                                            {{ number_format($product['old_price'], 0, ',', '.') }}đ
                                        </div>
                                    @endif
                                    <div class="product-price">{{ number_format($product['price'], 0, ',', '.') }}đ</div>
                                </div>
                                <div class="rating text-warning mb-1">
                                    <i class="bi bi-star-fill small"></i>
                                    <span class="text-dark fw-black small ms-1">{{ $product['rating'] }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="px-4 pb-4">
                            <a href="{{ route('product.detail', $product['id']) }}" class="btn btn-premium btn-premium-primary w-100 shadow-sm">
                                <i class="bi bi-eye-fill"></i> Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="display-1 text-muted opacity-25 mb-4">
                        <i class="bi bi-search"></i>
                    </div>
                    <h3 class="fw-bold text-dark">Không tìm thấy sản phẩm nào</h3>
                    <p class="text-muted">Vui lòng thử lại với các tiêu chí lọc khác.</p>
                    <a href="{{ route('product.list') }}" class="btn btn-premium btn-premium-primary px-5 mt-3 d-inline-flex">
                        Xem tất cả sản phẩm
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($total > $perPage)
            <nav class="pb-5">
                <ul class="pagination pagination-premium justify-content-center">
                    <li class="page-item {{ $page == 1 ? 'disabled' : '' }}">
                        <a class="page-link shadow-sm" href="{{ route('product.list', array_merge(request()->query(), ['page' => $page - 1])) }}">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                    @for($i = 1; $i <= ceil($total / $perPage); $i++)
                        <li class="page-item {{ $page == $i ? 'active' : '' }}">
                            <a class="page-link shadow-sm" href="{{ route('product.list', array_merge(request()->query(), ['page' => $i])) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <li class="page-item {{ $page >= ceil($total / $perPage) ? 'disabled' : '' }}">
                        <a class="page-link shadow-sm" href="{{ route('product.list', array_merge(request()->query(), ['page' => $page + 1])) }}">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        @endif
    </div>
@endsection
