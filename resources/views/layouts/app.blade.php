<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'NeoMart' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/neomart.css') }}" rel="stylesheet">
</head>
<body class="bg-body-tertiary">
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
        <div class="container py-2">
            <a class="brand-mark text-decoration-none" href="{{ route('home') }}">NEOMART</a>
            <div class="d-flex align-items-center gap-2 ms-auto">
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('home') }}">Trang chu</a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('products.index') }}">San pham</a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('cart.index') }}">Gio hang</a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.categories.index') }}">Quan tri danh muc</a>
                @auth
                    <span class="small text-secondary d-none d-lg-inline">Xin chao, {{ auth()->user()->name }}</span>
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-dark btn-sm" type="submit">Dang xuat</button>
                    </form>
                @else
                    <a class="btn btn-outline-dark btn-sm" href="{{ route('login') }}">Dang nhap</a>
                    <a class="btn btn-primary btn-sm" href="{{ route('register') }}">Dang ky</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @include('partials.flash')
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
