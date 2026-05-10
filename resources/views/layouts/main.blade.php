<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NeoMart')</title>
    
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background-color: #212529; /* Cùng tông với sidebar admin */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .hero-section {
            background: linear-gradient(135deg, #212529 0%, #343a40 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .product-img {
            height: 180px; /* Ảnh nhỏ theo yêu cầu */
            object-fit: contain;
            padding: 15px;
            background: #fff;
        }
        .category-icon {
            font-size: 2rem;
            color: #0d6efd;
            margin-bottom: 10px;
        }
        footer {
            background-color: #212529;
            color: #adb5bd;
            padding: 40px 0;
            margin-top: 60px;
        }
    </style>
</head>
<body>

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-box-seam text-primary me-2"></i>NeoMart
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sản phẩm</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    @auth
                        <span class="text-light small">Chào, {{ Auth::user()->name }}</span>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">Quản trị</a>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-link text-light p-0"><i class="bi bi-box-arrow-right"></i></button>
                        </form>
                    @else
                        <a href="{{ route('login.form') }}" class="btn btn-primary btn-sm px-4">Đăng nhập</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p class="mb-2 font-weight-bold text-white">NeoMart - Hệ thống quản lý kho & bán lẻ</p>
            <p class="small mb-0">&copy; 2026 Bản quyền thuộc về Nhóm A - DOAN_BE</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
