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
            background-color: #f4f6f9;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            color: #333;
        }
        
        /* Sidebar Styling */
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            min-height: 100vh;
            background-color: #212529; /* Dark theme */
            transition: all 0.3s;
        }
        #sidebar .sidebar-header {
            padding: 20px;
            background: #1a1e21;
            color: #fff;
            font-weight: bold;
            font-size: 1.2rem;
            border-bottom: 1px solid #343a40;
        }
        #sidebar ul.components {
            padding: 20px 0;
        }
        #sidebar ul p {
            color: #6c757d;
            padding: 10px 20px;
            font-size: 0.8rem;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 0;
        }
        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 1rem;
            display: block;
            color: #adb5bd;
            text-decoration: none;
            transition: 0.2s;
        }
        #sidebar ul li a:hover, #sidebar ul li.active > a {
            color: #fff;
            background: #0d6efd; /* Primary color */
        }
        #sidebar ul li a i {
            margin-right: 10px;
            font-size: 1.1rem;
        }

        /* User Profile in Sidebar */
        .sidebar-profile {
            padding: 15px 20px;
            border-top: 1px solid #343a40;
            background: #1a1e21;
            color: #fff;
        }

        /* Main Content */
        #content {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Top Navbar */
        .top-navbar {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }

        .main-container {
            padding: 24px;
            flex-grow: 1;
        }

        /* Utility Classes cho các module khác (Categories, Products, Users, Orders) */
        .surface {
            background-color: #ffffff;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.04);
            border: 1px solid #eef0f3;
        }
        .soft-surface {
            background-color: #fdfdfd;
            border: 1px solid #e9ecef;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        
        /* Đồng bộ Table Header */
        .table-light th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            border-bottom: 1px solid #dee2e6;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }
        .table > :not(caption) > * > * {
            padding: 1rem 0.75rem;
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

                <p class="mt-3">Kho vận & Mua hàng</p>
                <li class="{{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.suppliers.index') }}"><i class="bi bi-truck"></i> Quản lý Nhà cung cấp</a>
                </li>
                <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.orders.index') }}"><i class="bi bi-receipt"></i> Quản lý Đơn hàng</a>
                </li>
                <li class="{{ request()->routeIs('admin.warehouse.receipts*') ? 'active' : '' }}">
                    <a href="{{ route('admin.warehouse.receipts') }}"><i class="bi bi-file-earmark-text"></i> Nhập / Xuất kho</a>
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
            </ul>

            <div class="sidebar-profile d-flex align-items-center mt-auto">
                <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center fw-bold text-uppercase" style="width: 40px; height: 40px;">
                    {{ Auth::check() ? substr(Auth::user()->name, 0, 1) : 'U' }}
                </div>
                <div class="ms-3">
                    <div class="fw-bold fs-6">{{ Auth::check() ? Auth::user()->name : 'Người dùng' }}</div>
                    <div class="text-success small"><i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i> Online</div>
                </div>
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
