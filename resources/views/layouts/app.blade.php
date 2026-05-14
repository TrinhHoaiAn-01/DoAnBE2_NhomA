<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'NeoMart' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .brand-mark {
            font-weight: 900;
            letter-spacing: 2px;
            color: #1a202c;
            font-size: 1.25rem;
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
    </style>
</head>
<body class="bg-body-tertiary">
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
        <div class="container py-2">
            <a class="brand-mark text-decoration-none" href="{{ route('welcome') }}">NEOMART</a>
            <div class="d-flex align-items-center gap-2 ms-auto">
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('welcome') }}">Trang chủ</a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('product.list') }}">Sản phẩm</a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('cart.index') }}">Giỏ hàng</a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.categories.index') }}">Quản trị</a>
                @auth
                    <span class="small text-secondary d-none d-lg-inline">Xin chào, {{ auth()->user()->name }}</span>
                    <form method="post" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button class="btn btn-outline-dark btn-sm" type="submit">Đăng xuất</button>
                    </form>
                @else
                    <a class="btn btn-outline-dark btn-sm" href="{{ route('login') }}">Đăng nhập</a>
                    <a class="btn btn-primary btn-sm" href="{{ route('register') }}">Đăng ký</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @include('partials.flash')
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
