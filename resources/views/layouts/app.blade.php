<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="NeoMart – Siêu thị trực tuyến hàng đầu. Mua sắm tiện lợi, giao hàng siêu tốc.">
    <title>{{ $title ?? 'NeoMart – Mua sắm tiện lợi' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* ============================================================
           DESIGN SYSTEM – CSS Variables
        ============================================================ */
        :root {
            --primary:        #6366f1;
            --primary-dark:   #4f46e5;
            --primary-light:  #e0e7ff;
            --accent:         #f59e0b;
            --accent-light:   #fef3c7;
            --danger:         #ef4444;
            --success:        #10b981;
            --warning:        #f59e0b;
            --surface:        #ffffff;
            --surface-2:      #f8fafc;
            --surface-3:      #f1f5f9;
            --border:         #e2e8f0;
            --text-primary:   #0f172a;
            --text-secondary: #475569;
            --text-muted:     #94a3b8;
            --shadow-sm:      0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md:      0 4px 12px rgba(0,0,0,0.08), 0 2px 4px rgba(0,0,0,0.04);
            --shadow-lg:      0 20px 40px rgba(0,0,0,0.10), 0 8px 16px rgba(0,0,0,0.06);
            --radius-sm:      8px;
            --radius-md:      12px;
            --radius-lg:      20px;
            --radius-xl:      28px;
            --transition:     all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ============================================================
           BASE
        ============================================================ */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--surface-2);
            color: var(--text-primary);
            -webkit-font-smoothing: antialiased;
            line-height: 1.6;
        }

        /* Override Bootstrap primary color */
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover, .btn-primary:focus { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-outline-primary { color: var(--primary); border-color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary); border-color: var(--primary); }
        .text-primary { color: var(--primary) !important; }
        .bg-primary { background-color: var(--primary) !important; }
        .border-primary { border-color: var(--primary) !important; }

        /* ============================================================
           PAGE LOADER
        ============================================================ */
        #page-loader {
            position: fixed; inset: 0; background: var(--surface);
            z-index: 9999; display: flex; align-items: center; justify-content: center;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }
        #page-loader.hidden { opacity: 0; visibility: hidden; }
        .loader-logo {
            font-size: 2rem; font-weight: 900; letter-spacing: 3px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            animation: pulse-logo 1.2s ease-in-out infinite;
        }
        @keyframes pulse-logo {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.6; transform: scale(0.95); }
        }

        /* ============================================================
           TOP NAVBAR
        ============================================================ */
        .top-nav {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .nav-inner { padding: 0.75rem 0; }

        /* Brand */
        .brand-logo {
            font-weight: 900;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
            text-decoration: none;
            line-height: 1;
            white-space: nowrap;
        }
        .brand-logo .neo { color: var(--primary); }
        .brand-logo .mart { color: var(--text-primary); }

        /* Search bar */
        .nav-search-form {
            flex: 1;
            max-width: 480px;
        }
        .nav-search-wrap {
            position: relative;
            display: flex;
            background: var(--surface-2);
            border: 1.5px solid var(--border);
            border-radius: 50px;
            overflow: hidden;
            transition: var(--transition);
        }
        .nav-search-wrap:focus-within {
            border-color: var(--primary);
            background: var(--surface);
            box-shadow: 0 0 0 3px var(--primary-light);
        }
        .nav-search-input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 0.5rem 1rem 0.5rem 1.1rem;
            font-size: 0.875rem;
            color: var(--text-primary);
            outline: none;
        }
        .nav-search-input::placeholder { color: var(--text-muted); }
        .nav-search-btn {
            background: var(--primary);
            border: none;
            color: #fff;
            padding: 0 1.1rem;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background 0.2s;
            border-radius: 0;
        }
        .nav-search-btn:hover { background: var(--primary-dark); }

        /* Nav links */
        .nav-links { display: flex; align-items: center; gap: 0.25rem; }
        .nav-link-item {
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 500;
            padding: 0.45rem 0.75rem;
            border-radius: var(--radius-sm);
            transition: var(--transition);
            text-decoration: none;
            white-space: nowrap;
        }
        .nav-link-item:hover { color: var(--primary); background: var(--primary-light); }
        .nav-link-item.active { color: var(--primary); font-weight: 700; }

        /* Cart icon */
        .cart-btn {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px; height: 40px;
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition);
            font-size: 1.15rem;
        }
        .cart-btn:hover { color: var(--primary); background: var(--primary-light); }
        .cart-badge {
            position: absolute;
            top: 2px; right: 2px;
            background: var(--danger);
            color: #fff;
            font-size: 0.6rem;
            font-weight: 800;
            width: 16px; height: 16px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--surface);
            line-height: 1;
        }

        /* User dropdown */
        .user-btn {
            display: flex; align-items: center; gap: 0.5rem;
            background: var(--surface-2);
            border: 1.5px solid var(--border);
            border-radius: 50px;
            padding: 0.35rem 0.75rem 0.35rem 0.35rem;
            cursor: pointer;
            transition: var(--transition);
            color: var(--text-primary);
            font-size: 0.85rem;
            font-weight: 600;
        }
        .user-btn:hover { border-color: var(--primary); color: var(--primary); }
        .user-avatar {
            width: 28px; height: 28px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 0.75rem; font-weight: 700;
            flex-shrink: 0;
        }
        .dropdown-menu {
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            padding: 0.5rem;
            min-width: 200px;
        }
        .dropdown-item {
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
            transition: var(--transition);
        }
        .dropdown-item:hover { background: var(--primary-light); color: var(--primary); }
        .dropdown-divider { margin: 0.35rem 0; border-color: var(--border); }

        /* ============================================================
           MAIN CONTENT
        ============================================================ */
        main.page-content { min-height: 70vh; }

        /* ============================================================
           FOOTER
        ============================================================ */
        .site-footer {
            background: #0f172a;
            color: #94a3b8;
            margin-top: 4rem;
        }
        .footer-top {
            padding: 3.5rem 0 2.5rem;
        }
        .footer-brand-logo {
            font-weight: 900;
            font-size: 1.75rem;
            letter-spacing: -0.5px;
            line-height: 1;
        }
        .footer-brand-logo .neo { color: var(--primary); }
        .footer-brand-logo .mart { color: #fff; }
        .footer-desc {
            font-size: 0.875rem;
            line-height: 1.7;
            color: #64748b;
            max-width: 280px;
        }
        .footer-heading {
            color: #fff;
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 1.25rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .footer-link {
            color: #64748b;
            text-decoration: none;
            font-size: 0.875rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .footer-link:hover { color: var(--primary); padding-left: 4px; }
        .footer-links-list {
            list-style: none;
            padding: 0; margin: 0;
            display: flex; flex-direction: column; gap: 0.6rem;
        }

        /* Newsletter */
        .newsletter-wrap {
            background: rgba(99,102,241,0.1);
            border: 1px solid rgba(99,102,241,0.25);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
        }
        .newsletter-form { display: flex; gap: 0.5rem; }
        .newsletter-input {
            flex: 1;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 50px;
            color: #fff;
            padding: 0.55rem 1rem;
            font-size: 0.875rem;
            outline: none;
            transition: var(--transition);
        }
        .newsletter-input::placeholder { color: #64748b; }
        .newsletter-input:focus { border-color: var(--primary); background: rgba(255,255,255,0.1); }
        .newsletter-btn {
            background: var(--primary);
            border: none;
            color: #fff;
            border-radius: 50px;
            padding: 0.55rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
        }
        .newsletter-btn:hover { background: var(--primary-dark); }

        /* Social icons */
        .social-icon {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: #1e293b;
            display: inline-flex; align-items: center; justify-content: center;
            color: #64748b;
            transition: var(--transition);
            font-size: 0.85rem;
            text-decoration: none;
        }
        .social-icon:hover { background: var(--primary); color: #fff; transform: translateY(-2px); }

        /* Contact info */
        .footer-contact-item {
            display: flex; align-items: flex-start; gap: 0.6rem;
            font-size: 0.85rem; color: #64748b;
            margin-bottom: 0.6rem;
        }
        .footer-contact-icon {
            color: var(--primary);
            margin-top: 2px;
            flex-shrink: 0;
        }

        /* App badges */
        .app-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: var(--radius-sm);
            padding: 0.45rem 0.9rem;
            color: #fff;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 600;
            transition: var(--transition);
        }
        .app-badge:hover { background: var(--primary); border-color: var(--primary); color: #fff; }

        /* Footer bottom */
        .footer-bottom {
            border-top: 1px solid #1e293b;
            padding: 1.25rem 0;
            font-size: 0.8rem;
            color: #475569;
        }
        .footer-bottom a { color: #475569; text-decoration: none; transition: color 0.2s; }
        .footer-bottom a:hover { color: var(--primary); }

        /* Payment badges */
        .payment-badge {
            display: inline-flex; align-items: center;
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 6px;
            padding: 0.2rem 0.5rem;
            font-size: 0.7rem;
            color: #94a3b8;
            font-weight: 600;
        }

        /* ============================================================
           FLASH MESSAGES
        ============================================================ */
        .flash-alert {
            border: none;
            border-radius: var(--radius-md);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* ============================================================
           SCROLL TO TOP
        ============================================================ */
        #scroll-top {
            position: fixed;
            bottom: 1.5rem; right: 1.5rem;
            width: 44px; height: 44px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            opacity: 0; visibility: hidden;
            z-index: 999;
            font-size: 1rem;
        }
        #scroll-top.visible { opacity: 1; visibility: visible; }
        #scroll-top:hover { background: var(--primary-dark); transform: translateY(-3px); }
    </style>
    @stack('styles')
</head>
<body>

<!-- Page Loader -->
<div id="page-loader">
    <div class="loader-logo">NEOMART</div>
</div>

<!-- ===== TOP NAVBAR ===== -->
<nav class="top-nav">
    <div class="container">
        <div class="nav-inner d-flex align-items-center gap-3">

            <!-- Brand -->
            <a class="brand-logo me-2 flex-shrink-0" href="{{ route('home') }}">
                <span class="neo">Neo</span><span class="mart">Mart</span>
            </a>

            <!-- Nav links (desktop) -->
            <div class="nav-links d-none d-lg-flex flex-shrink-0">
                <a class="nav-link-item {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                    <i class="bi bi-house-door me-1"></i>Trang chủ
                </a>
                <a class="nav-link-item {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                    <i class="bi bi-grid me-1"></i>Sản phẩm
                </a>
            </div>

            <!-- Search bar -->
            <form class="nav-search-form d-none d-md-flex mx-auto" method="GET" action="{{ route('products.index') }}">
                <div class="nav-search-wrap w-100">
                    <input
                        type="text"
                        name="search"
                        class="nav-search-input"
                        placeholder="Tìm sản phẩm..."
                        value="{{ request('search') }}"
                        autocomplete="off"
                    >
                    <button class="nav-search-btn" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            <!-- Right actions -->
            <div class="d-flex align-items-center gap-2 ms-auto flex-shrink-0">

                <!-- Cart -->
                <a href="{{ route('cart.index') }}" class="cart-btn" title="Giỏ hàng">
                    <i class="bi bi-bag"></i>
                    @auth
                        @php $cartCount = session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0; @endphp
                        @if($cartCount > 0)
                            <span class="cart-badge">{{ $cartCount > 9 ? '9+' : $cartCount }}</span>
                        @endif
                    @endauth
                </a>

                <!-- Auth -->
                @auth
                    <div class="dropdown">
                        <button class="user-btn dropdown-toggle border-0 bg-transparent p-0" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false"
                                style="display:flex;align-items:center;gap:0.5rem;background:var(--surface-2) !important;border:1.5px solid var(--border) !important;border-radius:50px;padding:0.35rem 0.75rem 0.35rem 0.35rem !important;">
                            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                            <span class="d-none d-lg-inline small fw-semibold text-dark" style="max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                {{ auth()->user()->name }}
                            </span>
                            <i class="bi bi-chevron-down small text-muted" style="font-size:0.7rem;"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="px-3 py-2">
                                <div class="fw-bold text-dark small">{{ auth()->user()->name }}</div>
                                <div class="text-muted" style="font-size:0.75rem;">{{ auth()->user()->email }}</div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.index') }}">
                                    <i class="bi bi-person me-2 text-primary"></i>Hồ sơ cá nhân
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2 text-primary"></i>Quản trị
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="post" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a class="btn btn-outline-secondary btn-sm rounded-pill px-3 d-none d-sm-inline-flex" href="{{ route('login') }}">
                        Đăng nhập
                    </a>
                    <a class="btn btn-sm rounded-pill px-3 fw-semibold" href="{{ route('register') }}"
                       style="background:var(--primary);color:#fff;">
                        Đăng ký
                    </a>
                @endauth

                <!-- Mobile hamburger -->
                <button class="btn btn-sm d-lg-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNav">
                    <i class="bi bi-list fs-5"></i>
                </button>
            </div>
        </div>

        <!-- Mobile nav -->
        <div class="collapse d-lg-none" id="mobileNav">
            <div class="py-2 border-top" style="border-color:var(--border)!important;">
                <!-- Mobile search -->
                <form method="GET" action="{{ route('products.index') }}" class="mb-2">
                    <div class="nav-search-wrap">
                        <input type="text" name="search" class="nav-search-input" placeholder="Tìm sản phẩm..." value="{{ request('search') }}">
                        <button class="nav-search-btn" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
                <div class="d-flex flex-column gap-1">
                    <a class="nav-link-item {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house-door me-2"></i>Trang chủ
                    </a>
                    <a class="nav-link-item {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                        <i class="bi bi-grid me-2"></i>Sản phẩm
                    </a>
                    <a class="nav-link-item" href="{{ route('cart.index') }}">
                        <i class="bi bi-bag me-2"></i>Giỏ hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- ===== MAIN CONTENT ===== -->
<main class="page-content">
    <div class="container py-4">
        @if(View::exists('partials.flash'))
            @include('partials.flash')
        @endif
        @yield('content')
    </div>
</main>

<!-- ===== FOOTER ===== -->
<footer class="site-footer">
    <div class="footer-top">
        <div class="container">
            <div class="row g-5">

                <!-- Brand + Newsletter -->
                <div class="col-lg-4">
                    <div class="footer-brand-logo mb-3">
                        <span class="neo">Neo</span><span class="mart">Mart</span>
                    </div>
                    <p class="footer-desc mb-4">
                        Hệ thống siêu thị trực tuyến cung cấp sản phẩm công nghệ chất lượng cao với dịch vụ giao hàng siêu tốc và chăm sóc khách hàng tận tâm.
                    </p>
                    <!-- Newsletter -->
                    <div class="newsletter-wrap mb-4">
                        <div class="small fw-semibold text-white mb-2">
                            <i class="bi bi-envelope-heart me-1" style="color:var(--primary)"></i>
                            Nhận ưu đãi mới nhất
                        </div>
                        <form class="newsletter-form" onsubmit="return false;">
                            <input type="email" class="newsletter-input" placeholder="Email của bạn...">
                            <button class="newsletter-btn" type="submit">Đăng ký</button>
                        </form>
                    </div>
                    <!-- Socials -->
                    <div class="d-flex gap-2">
                        <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-tiktok"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>

                <!-- Links -->
                <div class="col-6 col-lg-2">
                    <div class="footer-heading">Sản phẩm</div>
                    <ul class="footer-links-list">
                        <li><a href="{{ route('products.index', ['category' => 'thiet-bi-di-dong']) }}" class="footer-link">Điện thoại</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'may-tinh-laptop']) }}" class="footer-link">Laptop</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'phu-kien']) }}" class="footer-link">Phụ kiện</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'thiet-bi-deo']) }}" class="footer-link">Đồng hồ & Wearable</a></li>
                        <li><a href="{{ route('products.index') }}" class="footer-link">Tất cả sản phẩm</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-2">
                    <div class="footer-heading">Hỗ trợ</div>
                    <ul class="footer-links-list">
                        <li><a href="#" class="footer-link">Hướng dẫn mua hàng</a></li>
                        <li><a href="#" class="footer-link">Chính sách bảo hành</a></li>
                        <li><a href="#" class="footer-link">Vận chuyển & Đổi hàng</a></li>
                        <li><a href="#" class="footer-link">Hỏi đáp (FAQ)</a></li>
                        <li><a href="#" class="footer-link">Liên hệ chúng tôi</a></li>
                    </ul>
                </div>

                <!-- Contact + App -->
                <div class="col-lg-4">
                    <div class="footer-heading">Liên hệ</div>
                    <div class="footer-contact-item">
                        <i class="bi bi-geo-alt-fill footer-contact-icon"></i>
                        <span>123 Đường Công Nghệ, Quận 1, TP. Hồ Chí Minh</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-telephone-fill footer-contact-icon"></i>
                        <span>(+84) 123 456 789</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-envelope-fill footer-contact-icon"></i>
                        <span>support@neomart.vn</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-clock-fill footer-contact-icon"></i>
                        <span>Hỗ trợ 24/7 – Thứ 2 đến Chủ nhật</span>
                    </div>

                    <div class="mt-4">
                        <div class="small fw-semibold text-white mb-2">Tải ứng dụng:</div>
                        <div class="d-flex gap-2">
                            <a href="#" class="app-badge"><i class="bi bi-apple fs-5"></i><span>App Store</span></a>
                            <a href="#" class="app-badge"><i class="bi bi-google-play fs-5"></i><span>Play Store</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span>© 2026 NeoMart. Thiết kế bởi <strong style="color:var(--primary)">Nhóm A</strong>.</span>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="payment-badge"><i class="bi bi-credit-card me-1"></i>VNPay</span>
                <span class="payment-badge"><i class="bi bi-wallet2 me-1"></i>MoMo</span>
                <span class="payment-badge"><i class="bi bi-bank me-1"></i>Banking</span>
            </div>
            <div class="d-flex gap-3">
                <a href="#">Điều khoản</a>
                <a href="#">Bảo mật</a>
                <a href="#">Cookies</a>
            </div>
        </div>
    </div>
</footer>

<!-- Scroll to Top -->
<button id="scroll-top" title="Lên đầu trang">
    <i class="bi bi-arrow-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Page loader
    window.addEventListener('load', () => {
        setTimeout(() => {
            document.getElementById('page-loader')?.classList.add('hidden');
        }, 300);
    });

    // Scroll to top
    const scrollBtn = document.getElementById('scroll-top');
    window.addEventListener('scroll', () => {
        scrollBtn?.classList.toggle('visible', window.scrollY > 400);
    });
    scrollBtn?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
</script>
@stack('scripts')
</body>
</html>
