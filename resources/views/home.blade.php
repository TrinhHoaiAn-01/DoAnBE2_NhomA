@extends('layouts.app', ['title' => 'NeoMart'])

@section('content')
    <!-- Banner Carousel -->
    <div id="homeBanner" class="carousel slide mb-4 shadow-sm" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach($banners as $index => $banner)
                <button type="button" data-bs-target="#homeBanner" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner rounded-4 overflow-hidden">
            @foreach($banners as $index => $banner)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <img src="{{ $banner['image'] }}" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="{{ $banner['title'] }}">
                    <div class="carousel-caption d-none d-md-block text-start p-5 bg-dark bg-opacity-25 rounded-4" style="left: 5%; right: auto; bottom: 10%;">
                        <h2 class="display-5 fw-black text-white mb-3">{{ $banner['title'] }}</h2>
                        <a href="{{ $banner['link'] }}" class="btn btn-primary btn-lg rounded-pill px-4">Mua ngay <i class="bi bi-arrow-right"></i></a>
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

    <!-- Giao diện ban đầu của NGOC_AI/Trang_chu -->
    <section class="hero-panel rounded-4 p-4 p-lg-5 mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <p class="text-uppercase small fw-semibold text-primary mb-2">NeoMart Demo</p>
                <h1 class="display-6 fw-bold mb-3">Nen tang Laravel cho cua hang ban le hien dai</h1>
                <p class="text-secondary mb-4">
                    Ban hien tai dang o dot mo phong tien do xay dung. Nhom bat dau tu quan tri danh muc de tao nen du lieu cho cac man hinh san pham va mua hang.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <div class="soft-surface rounded-4 px-3 py-2 text-center">
                        <div class="small text-secondary">Danh mục đang hiển thị</div>
                        <div class="fs-4 fw-bold">{{ $activeCategoryCount }}</div>
                    </div>
                    <div class="soft-surface rounded-4 px-3 py-2 text-center">
                        <div class="small text-secondary">Sản phẩm được mở bán</div>
                        <div class="fs-4 fw-bold">{{ $featuredProductCount }}</div>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-primary" href="{{ route('admin.categories.index') }}">Mở quản trị danh mục</a>
                    <a class="btn btn-outline-secondary" href="{{ route('product.list') }}">Mở quản trị sản phẩm</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="surface rounded-4 p-4 h-100">
                    <h2 class="h5 fw-bold mb-3">Danh mục hiện có</h2>
                    <div class="row row-cols-2 g-3">
                        @foreach ($category_groups as $group)
                            <div class="col">
                                <div class="soft-surface rounded-4 p-3 h-100">
                                    <div class="icon-chip mb-2"><i class="bi {{ $group['icon'] }}"></i></div>
                                    <div class="fw-semibold small">{{ $group['name'] }}</div>
                                    <div class="small text-secondary">{{ count($group['items']) }} nhóm</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Thêm Flash Sale bên dưới -->
    <style>
        .flash-sale-card {
            background: #1a202c;
            border-radius: 20px;
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
        }
        .timer-box {
            background: #ef4444;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: 800;
            min-width: 45px;
            display: inline-block;
            text-align: center;
        }
        .flash-item {
            background: rgba(255,255,255,0.05);
            border-radius: 15px;
            padding: 1rem;
            transition: 0.3s;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .flash-item:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-5px);
        }
    </style>

    <div class="flash-sale-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <h4 class="fw-black mb-0 text-danger"><i class="bi bi-lightning-fill"></i> FLASH SALE</h4>
                <div class="d-flex align-items-center gap-2">
                    <span class="small opacity-75">Kết thúc sau:</span>
                    <div class="timer-box" id="timer-h">00</div>
                    <span>:</span>
                    <div class="timer-box" id="timer-m">00</div>
                    <span>:</span>
                    <div class="timer-box" id="timer-s">00</div>
                </div>
            </div>
            <a href="{{ route('product.list') }}" class="text-white text-decoration-none small fw-bold">Xem tất cả <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="row g-3">
            @foreach($flash_sales as $p)
                <div class="col-md-3">
                    <div class="flash-item text-center h-100">
                        <a href="{{ route('product.detail', $p['id']) }}" class="text-decoration-none text-white">
                            <img src="{{ $p['image'] }}" class="img-fluid mb-3" style="height: 100px; object-fit: contain;" alt="{{ $p['name'] }}">
                            <div class="small fw-bold text-truncate mb-2">{{ $p['name'] }}</div>
                            <div class="text-danger fw-black">{{ number_format($p['price'], 0, ',', '.') }}đ</div>
                            <div class="small opacity-50 text-decoration-line-through">{{ number_format($p['old_price'], 0, ',', '.') }}đ</div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function updateTimer() {
        const endTime = new Date("{{ $flash_sale_end }}").getTime();
        const now = new Date().getTime();
        const diff = endTime - now;

        if (diff > 0) {
            const h = Math.floor(diff / (1000 * 60 * 60));
            const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((diff % (1000 * 60)) / 1000);

            document.getElementById('timer-h').innerText = h.toString().padStart(2, '0');
            document.getElementById('timer-m').innerText = m.toString().padStart(2, '0');
            document.getElementById('timer-s').innerText = s.toString().padStart(2, '0');
        }
    }
    setInterval(updateTimer, 1000);
    updateTimer();
</script>
@endpush
