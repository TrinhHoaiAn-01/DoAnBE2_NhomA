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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
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
            padding: 20px;
            flex-grow: 1;
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
                <li>
                    <a href="{{ route('welcome') }}">
                        <i class="bi bi-house-door"></i> Trang chủ
                    </a>
                </li>
                @auth
                <li class="{{ request()->routeIs('product.list') ? 'active' : '' }}">
                    <a href="{{ route('product.list') }}">
                        <i class="bi bi-view-list"></i> Danh sách sản phẩm
                    </a>
                </li>
                @endauth
                <p class="mt-2">Quản lý chung</p>
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-grid-1x2"></i> Tổng quan
                    </a>
                </li>

                <p class="mt-3">Kho vận & Mua hàng</p>
                <li class="{{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.suppliers.index') }}"><i class="bi bi-truck"></i> Quản lý Nhà cung cấp</a>
                </li>
                <li>
                    <a href="#"><i class="bi bi-file-earmark-text"></i> Nhập / Xuất kho</a>
                </li>
                <li>
                    <a href="#"><i class="bi bi-box-seam"></i> Tồn kho & Lô hàng</a>
                </li>

                <p class="mt-3">Hệ thống & Bảo mật</p>
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
                
                <p class="mt-3">Hỗ trợ & Nội dung</p>
                <li>
                    <a href="#"><i class="bi bi-image"></i> Banner & Trang chủ</a>
                </li>
                <li>
                    <a href="#"><i class="bi bi-question-circle"></i> Trung tâm trợ giúp</a>
                </li>
            </ul>

            @auth
            <div class="sidebar-profile d-flex align-items-center mt-auto">
                <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 40px; height: 40px;">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="ms-3">
                    <div class="fw-bold fs-6">{{ Auth::user()->name }}</div>
                    <div class="text-success small"><i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i> Online</div>
                </div>
            </div>
            @else
            <div class="sidebar-profile d-flex align-items-center mt-auto">
                <a href="{{ route('login.form') }}" class="btn btn-outline-primary w-100 btn-sm">Đăng nhập</a>
            </div>
            @endauth
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

                    <div class="ms-auto d-flex align-items-center">
                        @auth
                        <button class="btn btn-light position-relative rounded-circle p-2 me-3">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                <span class="visually-hidden">New alerts</span>
                            </span>
                        </button>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-box-arrow-right"></i> Đăng xuất
                            </button>
                        </form>
                        @else
                        <a href="{{ route('login.form') }}" class="btn btn-primary btn-sm px-4">Đăng nhập</a>
                        @endauth
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <div class="main-container">
                @yield('content')
            </div>

            <!-- Footer Area -->
            <footer class="bg-white border-top mt-auto py-5">
                <div class="container-fluid px-5">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-box-seam text-primary fs-3 me-2"></i>
                                <span class="h4 mb-0 fw-bold">Neo<span class="text-primary">Mart</span></span>
                            </div>
                            <p class="text-muted small">
                                Hệ thống NeoMart cung cấp các giải pháp công nghệ toàn diện cho doanh nghiệp và người tiêu dùng cá nhân. Chúng tôi cam kết mang lại sản phẩm chất lượng cao nhất với dịch vụ hậu mãi vượt trội.
                            </p>
                            <div class="d-flex gap-3 mt-4">
                                <a href="#" class="text-muted fs-5 hover-primary"><i class="bi bi-facebook"></i></a>
                                <a href="#" class="text-muted fs-5 hover-primary"><i class="bi bi-instagram"></i></a>
                                <a href="#" class="text-muted fs-5 hover-primary"><i class="bi bi-twitter-x"></i></a>
                                <a href="#" class="text-muted fs-5 hover-primary"><i class="bi bi-youtube"></i></a>
                            </div>
                        </div>
                        <div class="col-md-2 offset-md-1">
                            <h6 class="fw-bold mb-4">Sản phẩm</h6>
                            <ul class="list-unstyled small text-muted">
                                <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Điện thoại</a></li>
                                <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Laptop</a></li>
                                <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Phụ kiện</a></li>
                                <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Âm thanh</a></li>
                            </ul>
                        </div>
                        <div class="col-md-2">
                            <h6 class="fw-bold mb-4">Hỗ trợ khách hàng</h6>
                            <ul class="list-unstyled small text-muted">
                                <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Trung tâm trợ giúp</a></li>
                                <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Chính sách bảo hành</a></li>
                                <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Vận chuyển & Giao hàng</a></li>
                                <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Hoàn tiền & Trả hàng</a></li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <h6 class="fw-bold mb-4">Liên hệ với chúng tôi</h6>
                            <div class="small text-muted mb-2">
                                <i class="bi bi-geo-alt me-2"></i> 123 Đường Công Nghệ, Quận 1, TP. HCM
                            </div>
                            <div class="small text-muted mb-2">
                                <i class="bi bi-telephone me-2"></i> (+84) 123 456 789
                            </div>
                            <div class="small text-muted mb-2">
                                <i class="bi bi-envelope me-2"></i> support@neomart.com
                            </div>
                            <div class="mt-4 p-3 bg-light rounded-3">
                                <h6 class="small fw-bold mb-2">Tải ứng dụng ngay</h6>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-dark btn-sm px-3"><i class="bi bi-apple me-1"></i> App Store</button>
                                    <button class="btn btn-dark btn-sm px-3"><i class="bi bi-play-fill me-1"></i> Play Store</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-4 text-muted opacity-25">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <div class="small text-muted">
                            &copy; 2026 NeoMart. Tất cả quyền được bảo lưu. Thiết kế bởi Nhóm A.
                        </div>
                        <div class="d-flex gap-4">
                            <a href="#" class="text-muted text-decoration-none small">Điều khoản sử dụng</a>
                            <a href="#" class="text-muted text-decoration-none small">Chính sách bảo mật</a>
                            <a href="#" class="text-muted text-decoration-none small">Cookies</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap 5 JS (Hàng của Trọng dời sang public) -->
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    

    
    @stack('scripts')
</body>
</html>
