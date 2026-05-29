<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'NeoMart' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }
        .brand-mark {
            font-weight: 900;
            letter-spacing: 2px;
            color: #1a202c;
            font-size: 1.1rem;
        }
        .hero-panel {
            background-color: #fff;
            border: 1px solid #dee2e6;
        }
        .soft-surface {
            background-color: #f8f9fa;
            border-radius: 1rem;
            padding: 1rem;
        }
        .surface {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 1rem;
        }
        .icon-chip {
            width: 40px;
            height: 40px;
            background-color: #fff;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            color: #0d6efd;
        }
        /* Navbar */
        .top-nav {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .top-nav .nav-link-custom {
            color: #495057;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            transition: all 0.2s;
            text-decoration: none;
        }
        .top-nav .nav-link-custom:hover {
            color: #0d6efd;
            background: #e8f0fe;
        }
        .top-nav .nav-link-custom.active {
            color: #0d6efd;
            font-weight: 600;
        }
        /* Footer */
        .site-footer {
            background: #1a202c;
            color: #94a3b8;
            padding: 3rem 0 0;
            margin-top: 3rem;
        }
        .site-footer h6 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 1.25rem;
            font-size: 0.95rem;
        }
        .site-footer a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s;
        }
        .site-footer a:hover {
            color: #fff;
        }
        .footer-bottom {
            border-top: 1px solid #334155;
            padding: 1.25rem 0;
            margin-top: 2.5rem;
            font-size: 0.8rem;
        }
        .footer-brand {
            font-weight: 900;
            font-size: 1.5rem;
            color: #fff;
            letter-spacing: 2px;
        }
        .footer-brand span {
            color: #0d6efd;
        }
        .social-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #334155;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            transition: all 0.2s;
            font-size: 0.9rem;
        }
        .social-icon:hover {
            background: #0d6efd;
            color: #fff;
        }
        .store-badge {
            display: inline-block;
            background: #334155;
            color: #fff;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            transition: background 0.2s;
        }
        .store-badge:hover {
            background: #475569;
            color: #fff;
            text-decoration: none;
        }
    </style>
    @stack('styles')
</head>
<body>
	@if(!($hideNavbar ?? false))
    <!-- Top Navbar -->
    <nav class="top-nav sticky-top">
        <div class="container py-2">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-1">
                    <a class="brand-mark text-decoration-none me-3" href="{{ route('home') }}">
                        Trang chủ - <span class="text-primary">NeoMart</span>
                    </a>
                    <a class="nav-link-custom {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house-door me-1"></i>Trang chủ
                    </a>
                    <a class="nav-link-custom {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                        <i class="bi bi-grid me-1"></i>Sản phẩm
                    </a>
                    <a class="nav-link-custom {{ request()->routeIs('cart.*') ? 'active' : '' }}" href="{{ route('cart.index') }}">
                        <i class="bi bi-cart3 me-1"></i>Giỏ hàng
                    </a>
                    @auth
                        <a class="nav-link-custom {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                            <i class="bi bi-receipt me-1"></i>Đơn hàng
                        </a>
                    @endauth
                </div>
                <div class="dropdown">
    @auth
        @php
            $user = auth()->user();
            $initial = strtoupper(substr($user->name, 0, 1));
        @endphp

        <!-- AVATAR BUTTON -->
        <button class="btn d-flex align-items-center gap-2 dropdown-toggle border-0 bg-transparent"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">

            <!-- avatar circle -->
            <div style="
                width: 38px;
                height: 38px;
                border-radius: 50%;
                background: #0d6efd;
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                font-size: 14px;
            ">
                {{ $initial }}
            </div>

            <!-- text -->
            <div class="text-start d-none d-lg-block">
                <div class="small text-muted">Xin chào</div>
                <div class="fw-semibold">{{ $user->name }}</div>
            </div>

        </button>

        <!-- DROPDOWN -->
		<ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">

			@php
				$user = auth()->user();
				$isAdmin = $user->role_id == 5;
			@endphp

			<li>
				@if($isAdmin)
					<a class="dropdown-item" href="{{ route('admin.dashboard') }}">
						<i class="bi bi-gear me-2"></i>Quản trị
					</a>
				@else
					<a class="dropdown-item" href="{{ route('profile') }}">
						<i class="bi bi-person me-2"></i>Hồ sơ
					</a>
				@endif
			</li>

			<li><hr class="dropdown-divider"></li>

			<li>
				<form method="POST" action="{{ route('logout') }}">
					@csrf
					<button class="dropdown-item text-danger">
						<i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
					</button>
				</form>
			</li>

		</ul>
    @endauth

    @guest
        <a class="btn btn-outline-dark btn-sm rounded-pill px-3" href="{{ route('login') }}">
            <i class="bi bi-box-arrow-in-right me-1"></i>Đăng nhập
        </a>
    @endguest
</div>
            </div>
        </div>
    </nav>
	@endif

    <!-- Main Content -->
    <main class="container py-4">
        @if(View::exists('partials.flash'))
            @include('partials.flash')
        @endif
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="row g-4">
                <!-- Brand -->
                <div class="col-lg-4">
                    <div class="footer-brand mb-3"><span>Neo</span>Mart</div>
                    <p class="small mb-3">
                        Hệ thống bán lẻ công nghệ hàng đầu với chương trình khuyến mãi hấp dẫn và dịch vụ chăm sóc khách hàng tận tâm. Chúng tôi cam kết mang đến những sản phẩm chất lượng cao nhất với mức giá tốt nhất thị trường.
                    </p>
                    <div class="d-flex gap-2 mb-3">
                        <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>

                <!-- Sản phẩm -->
                <div class="col-6 col-lg-2">
                    <h6>Sản phẩm</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="{{ route('products.index', ['category' => 'Thiết bị di động']) }}">Điện thoại</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'Máy tính & Laptop']) }}">Laptop</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'Phụ kiện & Âm thanh']) }}">Phụ kiện</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'Thiết bị đeo']) }}">Âm thanh</a></li>
                    </ul>
                </div>

                <!-- Hỗ trợ -->
                <div class="col-6 col-lg-3">
                    <h6>Hỗ trợ khách hàng</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="#">Hướng dẫn mua hàng</a></li>
                        <li><a href="#">Chính sách bảo hành</a></li>
                        <li><a href="#">Vận chuyển & Đổi hàng</a></li>
                        <li><a href="#">Hướng dẫn & Trả hàng</a></li>
                    </ul>
                </div>

                <!-- Liên hệ -->
                <div class="col-lg-3">
                    <h6>Liên hệ với chúng tôi</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><i class="bi bi-geo-alt me-2 text-primary"></i>123 Đường Công Nghệ, Quận 1, TP. HCM</li>
                        <li><i class="bi bi-telephone me-2 text-primary"></i>(+84) 123 456 789</li>
                        <li><i class="bi bi-envelope me-2 text-primary"></i>support@neomart.com</li>
                    </ul>
                    <p class="small mt-3 mb-2 text-white fw-semibold">Tải ứng dụng ngay:</p>
                    <div class="d-flex gap-2">
                        <a href="#" class="store-badge"><i class="bi bi-apple me-1"></i> App Store</a>
                        <a href="#" class="store-badge"><i class="bi bi-google-play me-1"></i> Play Store</a>
                    </div>
                </div>
            </div>

            <!-- Bottom -->
            <div class="footer-bottom d-flex flex-wrap justify-content-between align-items-center">
                <span>© 2026 NeoMart. Tất cả quyền được bảo lưu. · Thiết kế bởi Nhóm A</span>
                <div class="d-flex gap-3">
                    <a href="#">Điều khoản sử dụng</a>
                    <a href="#">Chính sách bảo mật</a>
                    <a href="#">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
