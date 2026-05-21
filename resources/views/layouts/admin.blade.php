<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Quản trị hệ thống (Người 5)</title>
    
    <!-- Bootstrap 5 (Dùng hàng Offline từ public/bootstrap theo cập nhật của Trọng) -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    
    <!-- Bootstrap Icons (CDN để dùng icon) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            color: #1e293b;
        }
        
        /* Sidebar Styling */
        #sidebar {
            min-width: 260px;
            max-width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            box-shadow: 4px 0 25px rgba(15, 23, 42, 0.15);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 100;
            display: flex;
            flex-direction: column;
        }
        #sidebar .sidebar-header {
            padding: 24px 20px;
            background: rgba(15, 23, 42, 0.4);
            color: #fff;
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        #sidebar ul.components {
            padding: 20px 12px;
            flex-grow: 1;
            overflow-y: auto;
            margin-bottom: 0;
        }
        #sidebar ul p {
            color: #64748b;
            padding: 10px 16px;
            font-size: 0.75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 4px;
        }
        #sidebar ul li {
            margin-bottom: 4px;
        }
        #sidebar ul li a {
            padding: 10px 16px;
            font-size: 0.92rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.2s ease;
        }
        #sidebar ul li a i {
            margin-right: 12px;
            font-size: 1.2rem;
            transition: transform 0.2s ease;
        }
        #sidebar ul li a:hover {
            color: #f8fafc;
            background: rgba(255, 255, 255, 0.06);
        }
        #sidebar ul li a:hover i {
            transform: translateX(3px);
        }
        #sidebar ul li.active > a {
            color: #fff;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            font-weight: 600;
        }
        #sidebar ul li.active > a i {
            color: #fff;
        }

        /* User Profile in Sidebar */
        .sidebar-profile {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            background: rgba(15, 23, 42, 0.4);
            color: #fff;
            margin-top: auto;
        }
        .sidebar-profile .avatar-circle {
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            box-shadow: 0 4px 8px rgba(37, 99, 235, 0.2);
            transition: transform 0.3s ease;
        }
        .sidebar-profile:hover .avatar-circle {
            transform: scale(1.05) rotate(5deg);
        }

        /* Main Content */
        #content {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f8fafc;
        }
        
        /* Top Navbar */
        .top-navbar {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .top-navbar .navbar-brand {
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .main-container {
            padding: 30px;
            flex-grow: 1;
        }

        /* Utility Cards */
        .surface {
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            transition: all 0.3s ease;
        }
        .surface:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            transform: translateY(-2px);
        }
        .soft-surface {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }
        
        /* Table Styling */
        .table-light th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 700;
            border-bottom: 1px solid #e2e8f0;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.8px;
        }
        .table > :not(caption) > * > * {
            padding: 1.1rem 0.85rem;
            border-bottom-color: #f1f5f9;
        }
        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }
    </style>
</head>
<body>

    <div class="d-flex">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header d-flex align-items-center">
                <i class="bi bi-box-seam text-primary fs-3 me-2"></i>
                <span>Neo<span class="text-white">Mart</span></span>
            </div>

            <ul class="list-unstyled components">
                <p>Quản lý chung</p>
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-grid-1x2"></i> Tổng quan
                    </a>
                </li>

                <p class="mt-3">Sản phẩm & Danh mục</p>
                <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.categories.index') }}"><i class="bi bi-tags"></i> Quản lý Danh mục</a>
                </li>
                <li class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.products.index') }}"><i class="bi bi-box"></i> Quản lý Sản phẩm</a>
                </li>
                <li class="{{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.promotions.index') }}"><i class="bi bi-percent"></i> Khuyến mãi</a>
                </li>
                <li class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.reviews.index') }}"><i class="bi bi-star"></i> Đánh giá sản phẩm</a>
                </li>

                <p class="mt-3">Kho vận & Mua hàng</p>
                <li class="{{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.suppliers.index') }}"><i class="bi bi-truck"></i> Quản lý Nhà cung cấp</a>
                </li>
                <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.orders.index') }}"><i class="bi bi-receipt"></i> Quản lý Đơn hàng</a>
                </li>
                <li class="{{ request()->routeIs('admin.warehouse.receipts*') ? 'active' : '' }}">
                    <a href="{{ route('admin.warehouse.receipts') }}"><i class="bi bi-box-arrow-in-right"></i> Phiếu Nhập Kho</a>
                </li>
                <li class="{{ request()->routeIs('admin.warehouse.issues*') ? 'active' : '' }}">
                    <a href="{{ route('admin.warehouse.issues') }}"><i class="bi bi-box-arrow-up-right"></i> Phiếu Xuất Kho</a>
                </li>
                <li class="{{ request()->routeIs('admin.warehouse.checks*') ? 'active' : '' }}">
                    <a href="{{ route('admin.warehouse.checks') }}"><i class="bi bi-ui-checks"></i> Kiểm kê kho</a>
                </li>
                <li class="{{ request()->routeIs('admin.warehouse.inventory') ? 'active' : '' }}">
                    <a href="{{ route('admin.warehouse.inventory') }}"><i class="bi bi-box-seam"></i> Tồn kho & Lô hàng</a>
                </li>

                <p class="mt-3">Hỗ trợ & Nội dung</p>
                <li class="{{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.banners.index') }}"><i class="bi bi-image"></i> Banner & Trang chủ</a>
                </li>
                <li class="{{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.faqs.index') }}"><i class="bi bi-question-square"></i> Trung tâm trợ giúp</a>
                </li>
                <li class="{{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.contacts.index') }}"><i class="bi bi-envelope"></i> Liên hệ từ khách hàng</a>
                </li>

                <p class="mt-3">Hệ thống & Bảo mật</p>
                <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people"></i> Tài khoản hệ thống
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.permissions') ? 'active' : '' }}">
                    <a href="{{ route('admin.permissions') }}">
                        <i class="bi bi-shield-lock"></i> Phân quyền hệ thống
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                    <a href="{{ route('admin.logs') }}">
                        <i class="bi bi-journal-text"></i> Nhật ký hệ thống
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
                    <a href="{{ route('admin.statistics') }}">
                        <i class="bi bi-bar-chart-line"></i> Thống kê cơ bản
                    </a>
                </li>
            </ul>

			<!-- PROFILE -->
			<div class="sidebar-profile dropdown">

				<!-- BUTTON -->
				<button class="btn w-100 text-start border-0 bg-transparent p-0"
						type="button"
						data-bs-toggle="dropdown"
						aria-expanded="false">

					<div class="d-flex align-items-center">

						<!-- AVATAR -->
						<div class="avatar-circle text-white rounded-circle d-flex justify-content-center align-items-center fw-bold text-uppercase"
							 style="
								width: 42px;
								height: 42px;
								font-size: 1.1rem;
								flex-shrink: 0;
							 ">

							{{ Auth::check() ? substr(Auth::user()->name, 0, 1) : 'U' }}

						</div>


						<!-- INFO -->
						<div class="ms-3">

							<div class="fw-semibold fs-6 text-white">

								{{ Auth::check() ? Auth::user()->name : 'Người dùng' }}

							</div>

							<div class="text-success small d-flex align-items-center">

								<span class="d-inline-block bg-success rounded-circle me-1"
									  style="width:6px;height:6px;"></span>

								Online

							</div>

						</div>

					</div>

				</button>

				<!-- DROPDOWN -->
				<ul class="dropdown-menu dropdown-menu-dark shadow border-0 w-100 mt-3"
					style="border-radius:16px;">

					<!-- PROFILE -->
					<li>

						<a class="dropdown-item py-2 rounded-3"
						   href="{{ route('profile.user') }}">

							<i class="bi bi-person me-2"></i>

							Hồ sơ người dùng

						</a>

					</li>

					<!-- SETTINGS -->
					<li>

						<a class="dropdown-item py-2 rounded-3"
						   href="{{ route('settings') }}">

							<i class="bi bi-gear me-2"></i>

							Cài đặt

						</a>

					</li>

					<!-- DIVIDER -->
					<li>
						<hr class="dropdown-divider">
					</li>

					<!-- LOGOUT -->
					<li>

						<form action="{{ route('logout') }}"
							  method="POST">

							@csrf

							<button type="submit"
									class="dropdown-item py-2 rounded-3 text-danger">

								<i class="bi bi-box-arrow-right me-2"></i>

								Đăng xuất

							</button>

						</form>

					</li>

				</ul>

			</div>

        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg top-navbar py-3 px-4">
                <div class="container-fluid">
                    <button type="button" class="btn btn-light d-lg-none me-3">
                        <i class="bi bi-list"></i>
                    </button>
                    
                    <span class="navbar-brand mb-0 h1 fs-5">@yield('title', 'Bảng điều khiển')</span>

                    <div class="ms-auto d-flex align-items-center gap-3">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3" target="_blank">
                            <i class="bi bi-house-door me-1"></i>Quay lại
                        </a>
                        <button class="btn btn-light position-relative rounded-circle p-2">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                <span class="visually-hidden">New alerts</span>
                            </span>
                        </button>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <div class="main-container">
                @include('partials.flash')
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS (Hàng của Trọng dời sang public) -->
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    

    
    @stack('scripts')
</body>
</html>
