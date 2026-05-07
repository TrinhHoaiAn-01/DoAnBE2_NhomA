<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'NeoMart Admin' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/neomart.css') }}" rel="stylesheet">
</head>
<body class="admin-body">
    <div class="container-fluid">
        <div class="row min-vh-100">
            <aside class="col-lg-3 col-xl-2 admin-sidebar p-4">
                <a class="brand-mark text-decoration-none d-inline-block mb-4" href="{{ route('home') }}">NEOMART</a>
                <div class="small text-uppercase text-secondary fw-semibold mb-3">Quan tri ban hang</div>
                <nav class="nav flex-column gap-2">
                    <a class="nav-link px-3 py-2 rounded {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">Danh muc</a>
                    <a class="nav-link px-3 py-2 rounded {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">San pham</a>
                </nav>
            </aside>
            <main class="col-lg-9 col-xl-10 p-4 p-lg-5">
                @include('partials.flash')
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
