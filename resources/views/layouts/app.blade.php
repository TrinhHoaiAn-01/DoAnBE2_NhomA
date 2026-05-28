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
            --primary:        #008848;
            --primary-dark:   #006b38;
            --primary-light:  #e6f3eb;
            --accent:         #ffb416;
            --accent-light:   #fff5e0;
            --danger:         #d0021b;
            --success:        #10b981;
            --warning:        #f59e0b;
            --surface:        #ffffff;
            --surface-2:      #f1f1f1;
            --surface-3:      #e5e5e5;
            --border:         #ddd;
            --text-primary:   #0f172a;
            --text-secondary: #475569;
            --text-muted:     #94a3b8;
            --shadow-sm:      none;
            --shadow-md:      none;
            --shadow-lg:      none;
            --radius-sm:      0px;
            --radius-md:      0px;
            --radius-lg:      0px;
            --radius-xl:      0px;
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
            background: var(--primary);
            border-bottom: 4px solid var(--accent);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .nav-inner { padding: 0; }

        /* Brand */
        .brand-logo {
            font-weight: 900;
            font-size: 1.6rem;
            letter-spacing: -0.5px;
            text-decoration: none;
            line-height: 1;
            white-space: nowrap;
            padding: 1rem 0;
            text-transform: uppercase;
        }
        .brand-logo .neo { color: #fff; }
        .brand-logo .mart { color: var(--accent); }

        /* Search bar */
        .nav-search-form {
            flex: 1;
            max-width: 500px;
            margin: 0 2rem;
        }
        .nav-search-wrap {
            display: flex;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            height: 40px;
        }
        .nav-search-input {
            flex: 1;
            border: none;
            padding: 0 1rem;
            outline: none;
            font-size: 0.9rem;
            color: #000;
        }
        .nav-search-btn {
            border: none;
            color: #333;
            padding: 0 0.8rem;
            cursor: pointer;
            font-size: 1rem;
            transition: color 0.2s;
            border-radius: 0;
        }
        .nav-search-btn:hover { color: var(--primary); }

        /* Nav links */
        .nav-links { display: flex; align-items: stretch; height: 100%; margin-left: 2rem; }
        .nav-link-item {
            color: #fff;
            font-size: 0.9rem;
            font-weight: 700;
            padding: 1.2rem 1rem;
            text-decoration: none;
            white-space: nowrap;
            display: flex; align-items: center;
            border-bottom: 4px solid transparent;
            opacity: 0.9;
        }
        .nav-link-item:hover { opacity: 1; background: rgba(0,0,0,0.1); }
        .nav-link-item.active { opacity: 1; border-bottom-color: var(--accent); background: rgba(0,0,0,0.15); }

        /* Cart icon */
        .cart-btn {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px; height: 40px;
            color: #fff;
            text-decoration: none;
            font-size: 1.15rem;
        }
        .cart-btn:hover { background: rgba(0,0,0,0.1); }
        .cart-badge {
            position: absolute;
            top: 4px; right: 2px;
            background: var(--accent);
            color: #000;
            font-size: 0.65rem;
            font-weight: 800;
            width: 18px; height: 18px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            line-height: 1;
        }

        /* User dropdown */
        .user-btn {
            display: flex; align-items: center; gap: 0.5rem;
            background: rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 0.25rem 0.75rem 0.25rem 0.25rem;
            cursor: pointer;
            color: #fff;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .user-btn:hover { background: rgba(0,0,0,0.3); }
        .user-avatar {
            width: 28px; height: 28px;
            border-radius: 6px;
            background: var(--accent);
            display: flex; align-items: center; justify-content: center;
            color: #000; font-size: 0.75rem; font-weight: 800;
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
            background: var(--primary);
            color: rgba(255,255,255,0.85);
            margin-top: 4rem;
        }
        .footer-top {
            padding: 3.5rem 0 3.5rem;
        }
        .footer-brand-logo {
            font-weight: 900;
            font-size: 1.75rem;
            letter-spacing: -0.5px;
            line-height: 1;
        }
        .footer-brand-logo .neo { color: #fff; }
        .footer-brand-logo .mart { color: #fbbf24; }
        .footer-desc {
            font-size: 0.875rem;
            line-height: 1.7;
            color: rgba(255,255,255,0.9);
            max-width: 300px;
        }
        .footer-heading {
            color: #fff;
            font-weight: 700;
            font-size: 1.05rem;
            margin-bottom: 1.25rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .footer-link {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-size: 0.9rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .footer-link:hover { color: #fff; padding-left: 4px; }
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

            <!-- Search bar BHX style -->
            <form class="nav-search-form d-none d-md-flex mx-auto" method="GET" action="{{ route('products.index') }}">
                <div class="nav-search-wrap w-100">
                    <input
                        type="text"
                        name="search"
                        class="nav-search-input"
                        placeholder="Bạn tìm gì..."
                        value="{{ request('search') }}"
                        autocomplete="off"
                    >
                    <button class="nav-search-btn" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            <!-- Right actions -->
            <div class="d-flex align-items-center gap-2 ms-auto flex-shrink-0 h-100">

                <!-- Cart -->
                <a href="{{ route('cart.index') }}" class="cart-btn me-2" title="Giỏ hàng">
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
                        <button class="user-btn dropdown-toggle p-0" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false"
                                style="display:flex;align-items:center;gap:0.5rem;background:transparent !important;border:none !important;border-radius:0;padding:0.35rem 0.75rem 0.35rem 0.35rem !important;">
                            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                            <span class="d-none d-lg-inline small fw-semibold text-white" style="max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                {{ auth()->user()->name }}
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end rounded-0 border-dark">
                            <li class="px-3 py-2">
                                <div class="fw-bold text-dark small">{{ auth()->user()->name }}</div>
                                <div class="text-muted" style="font-size:0.75rem;">{{ auth()->user()->email }}</div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.index') }}">
                                    Hồ sơ cá nhân
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    Quản trị
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="post" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">
                                        Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a class="nav-link-item px-2 border-0" href="{{ route('login') }}" style="font-size:0.8rem;text-transform:none;">
                        Đăng nhập
                    </a>
                    <a class="btn btn-sm px-3 fw-semibold" href="{{ route('register') }}"
                       style="background:var(--primary);color:#fff;border-radius:0;">
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
            <div class="row g-5 justify-content-between">
                <!-- Brand -->
                <div class="col-lg-4">
                    <div class="footer-brand-logo mb-3">
                        <span class="neo">Neo</span><span class="mart">Mart</span>
                    </div>
                    <p class="footer-desc mb-4">
                        Hệ thống siêu thị trực tuyến cung cấp sản phẩm công nghệ chất lượng cao với dịch vụ giao hàng siêu tốc và chăm sóc khách hàng tận tâm.
                    </p>
                </div>

                <!-- Hỗ trợ -->
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="footer-heading">Hỗ trợ</div>
                    <ul class="footer-links-list">
                        <li><a href="#" class="footer-link" data-bs-toggle="modal" data-bs-target="#modal-buying-guide">Hướng dẫn mua hàng</a></li>
                        <li><a href="#" class="footer-link" data-bs-toggle="modal" data-bs-target="#modal-warranty">Chính sách bảo hành</a></li>
                        <li><a href="#" class="footer-link" data-bs-toggle="modal" data-bs-target="#modal-shipping">Vận chuyển & Đổi hàng</a></li>
                        <li><a href="#" class="footer-link" data-bs-toggle="modal" data-bs-target="#modal-faq">Hỏi đáp (FAQ)</a></li>
                        <li><a href="#" class="footer-link" data-bs-toggle="modal" data-bs-target="#modal-contact">Liên hệ chúng tôi</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="col-12 col-md-4 col-lg-4">
                    <div class="footer-heading">Liên hệ</div>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex gap-2 align-items-start">
                            <i class="bi bi-geo-alt-fill text-warning mt-1"></i>
                            <span>123 Đường Công Nghệ, Quận 1, TP. Hồ Chí Minh</span>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <i class="bi bi-telephone-fill text-warning"></i>
                            <span>(+84) 123 456 789</span>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <i class="bi bi-envelope-fill text-warning"></i>
                            <span>support@neomart.vn</span>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <i class="bi bi-clock-fill text-warning"></i>
                            <span>Hỗ trợ 24/7 – Thứ 2 đến Chủ nhật</span>
                        </div>
                    </div>
                </div>
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
<!-- Modals for Footer Links -->
<style>
    .custom-modal .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        overflow: hidden;
    }
    .custom-modal .modal-header {
        background: linear-gradient(135deg, var(--primary) 0%, #047857 100%);
        color: white;
        border-bottom: none;
        padding: 1.25rem 1.5rem;
    }
    .custom-modal .modal-title {
        font-weight: 800;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .custom-modal .modal-body {
        padding: 1.5rem;
        font-size: 0.95rem;
        line-height: 1.6;
    }
    .custom-modal .modal-body h6 {
        font-weight: 700;
        color: var(--primary-dark, #047857);
        margin-top: 1rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .custom-modal .modal-body h6:first-child {
        margin-top: 0;
    }
    .custom-modal .modal-body p, .custom-modal .modal-body ul {
        color: #475569;
        margin-bottom: 0;
    }
    .custom-modal .modal-body ul {
        padding-left: 1.25rem;
    }
    .custom-modal .modal-body li {
        margin-bottom: 0.35rem;
    }
    .custom-modal .modal-footer {
        border-top: 1px solid #f1f5f9;
        padding: 1rem 1.5rem;
    }
    .custom-modal .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
        opacity: 0.8;
    }
    .custom-modal .btn-close:hover {
        opacity: 1;
    }
    .custom-modal .btn-secondary {
        background: #f1f5f9;
        color: #475569;
        border: none;
        font-weight: 600;
        border-radius: 8px;
        padding: 0.5rem 1.25rem;
        transition: all 0.2s;
    }
    .custom-modal .btn-secondary:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
</style>

<div class="modal fade custom-modal" id="modal-buying-guide" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-cart-check"></i> Hướng dẫn mua hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6><i class="bi bi-search text-warning"></i> 1. Tìm kiếm sản phẩm</h6>
                <p class="mb-3">Quý khách có thể tìm kiếm sản phẩm theo danh mục hoặc sử dụng thanh tìm kiếm ở phía trên trang web.</p>
                <h6><i class="bi bi-bag-plus text-warning"></i> 2. Thêm vào giỏ hàng</h6>
                <p class="mb-3">Khi tìm được sản phẩm ưng ý, bấm "CHỌN MUA" để thêm sản phẩm vào giỏ hàng của bạn.</p>
                <h6><i class="bi bi-credit-card text-warning"></i> 3. Thanh toán</h6>
                <p>Tiến hành tới trang Giỏ hàng, điền thông tin giao hàng và chọn phương thức thanh toán phù hợp. Chúng tôi hỗ trợ nhiều hình thức thanh toán an toàn.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đã hiểu</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade custom-modal" id="modal-warranty" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-shield-check"></i> Chính sách bảo hành</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6><i class="bi bi-patch-check-fill text-primary"></i> Bảo hành chính hãng</h6>
                <p class="mb-3">Tất cả các sản phẩm mua tại NeoMart đều được bảo hành chính hãng theo tiêu chuẩn của nhà sản xuất.</p>
                <h6><i class="bi bi-card-checklist text-primary"></i> Điều kiện bảo hành</h6>
                <ul>
                    <li>Sản phẩm còn trong thời hạn bảo hành.</li>
                    <li>Sản phẩm không bị rơi vỡ, vào nước, hoặc can thiệp phần cứng.</li>
                    <li>Tem bảo hành còn nguyên vẹn.</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đã hiểu</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade custom-modal" id="modal-shipping" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-truck"></i> Vận chuyển & Đổi hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6><i class="bi bi-lightning-fill text-warning"></i> Giao hàng siêu tốc</h6>
                <p class="mb-3">NeoMart cam kết giao hàng nhanh trong vòng <strong>2 giờ</strong> đối với khu vực nội thành TP.HCM và Hà Nội. Các tỉnh thành khác sẽ nhận được hàng từ 1-3 ngày làm việc.</p>
                <h6><i class="bi bi-arrow-left-right text-success"></i> Đổi trả dễ dàng</h6>
                <p>Quý khách có thể đổi trả sản phẩm trong vòng <strong>30 ngày</strong> nếu phát hiện lỗi từ nhà sản xuất. Sản phẩm đổi trả phải còn nguyên hộp và phụ kiện đi kèm.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đã hiểu</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade custom-modal" id="modal-faq" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-question-circle"></i> Hỏi đáp (FAQ)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>NeoMart bán hàng chính hãng không?</h6>
                <p class="mb-3">Chắc chắn rồi! <strong>100%</strong> sản phẩm tại NeoMart đều là hàng chính hãng, có nguồn gốc xuất xứ rõ ràng.</p>
                <h6>Làm sao để tôi theo dõi đơn hàng?</h6>
                <p class="mb-3">Bạn có thể theo dõi tiến trình giao hàng bằng cách đăng nhập vào tài khoản và truy cập phần <strong>"Quản lý đơn hàng"</strong>.</p>
                <h6>Có xuất hóa đơn VAT không?</h6>
                <p>NeoMart cung cấp đầy đủ hóa đơn VAT điện tử cho tất cả các đơn hàng khi quý khách có yêu cầu.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đã hiểu</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade custom-modal" id="modal-contact" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-headset"></i> Liên hệ chúng tôi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center gap-3 mb-3 p-3 bg-light rounded-3">
                    <i class="bi bi-geo-alt-fill fs-3 text-danger"></i>
                    <div>
                        <div class="fw-bold text-dark">Địa chỉ trực tiếp</div>
                        <div class="text-muted small">123 Đường Công Nghệ, Quận 1, TP. Hồ Chí Minh</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 mb-3 p-3 bg-light rounded-3">
                    <i class="bi bi-telephone-fill fs-3 text-success"></i>
                    <div>
                        <div class="fw-bold text-dark">Hotline hỗ trợ 24/7</div>
                        <div class="text-muted small">(+84) 123 456 789</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 mb-3 p-3 bg-light rounded-3">
                    <i class="bi bi-envelope-fill fs-3 text-primary"></i>
                    <div>
                        <div class="fw-bold text-dark">Email liên hệ</div>
                        <div class="text-muted small">support@neomart.vn</div>
                    </div>
                </div>
                <p class="text-center mt-4 fw-semibold text-success">
                    Chúng tôi luôn sẵn lòng lắng nghe và hỗ trợ quý khách mọi lúc, mọi nơi!
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

@stack('scripts')
</body>
</html>
