<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử đơn hàng</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a, #1e293b, #111827);
            color: white;
            overflow-x: hidden;
        }

        .bg {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.4;
        }

        .bg1 {
            background: #2563eb;
            top: -80px;
            left: -80px;
        }

        .bg2 {
            background: #7c3aed;
            bottom: -80px;
            right: -80px;
        }

        .wrapper {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .card-profile {
            width: 100%;
            max-width: 1200px;
            border-radius: 30px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        /* LEFT */
        .left {
            background: linear-gradient(180deg, #2563eb, #1d4ed8);
            padding: 40px 25px;
            color: white;
            height: 100%;
        }

        .avatar img {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid rgba(255, 255, 255, 0.3);
        }

        .name {
            font-size: 22px;
            font-weight: 700;
            margin-top: 10px;
        }

        .role {
            font-size: 14px;
            opacity: 0.9;
        }

        .nav-menu {
            margin-top: 30px;
        }

        .nav-item {
            display: flex;
            gap: 12px;
            align-items: center;
            padding: 14px 16px;
            border-radius: 14px;
            color: white;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.1);
            margin-bottom: 12px;
            transition: 0.3s;
            border: none;
            width: 100%;
        }

        .nav-item:hover, .nav-item.active {
            transform: translateX(6px);
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .danger {
            background: rgba(255, 0, 0, 0.2);
        }

        .danger:hover {
            background: #dc2626;
        }

        /* RIGHT */
        .right {
            padding: 50px;
        }

        .title {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 25px;
        }

        /* Glassmorphic forms and tables */
        .filter-section {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 18px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .form-select, .form-control {
            background: rgba(255, 255, 255, 0.08);
            border: none;
            color: white;
            border-radius: 12px;
            height: 45px;
        }

        .form-select:focus, .form-control:focus {
            background: rgba(255, 255, 255, 0.12);
            color: white;
            box-shadow: none;
        }

        .form-select option {
            color: black;
            background: white;
        }

        .btn-filter {
            height: 45px;
            border-radius: 12px;
            font-weight: 600;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            color: white;
            padding: 0 25px;
        }

        .btn-clear-filter {
            height: 45px;
            border-radius: 12px;
            font-weight: 600;
            border: 1px solid rgba(255,255,255,0.2);
            background: transparent;
            color: white;
            padding: 0 20px;
            transition: all 0.2s;
        }

        .btn-clear-filter:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        /* Table */
        .table-wrap {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            overflow: hidden;
        }

        .table {
            color: white;
            margin-bottom: 0;
        }

        .table th {
            background: rgba(255, 255, 255, 0.08);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: #cbd5e1;
            font-weight: 600;
            padding: 15px 20px;
        }

        .table td {
            padding: 18px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            background: transparent;
            color: #e2e8f0;
        }

        code {
            color: #60a5fa;
            background: rgba(96, 165, 250, 0.1);
            padding: 3px 8px;
            border-radius: 6px;
            font-family: monospace;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: white;
        }

        .empty-history {
            text-align: center;
            padding: 50px 20px;
        }

        .empty-history i {
            font-size: 60px;
            color: #64748b;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

<div class="bg bg1"></div>
<div class="bg bg2"></div>

<div class="wrapper">
    <div class="card-profile">
        <div class="row g-0">
            <!-- LEFT SIDEBAR -->
            <div class="col-lg-4">
                <div class="left text-center">
                    <div class="avatar mb-3">
                        <img src="{{ Auth::user()->avatar_url
                            ? asset(Auth::user()->avatar_url)
                            : 'https://i.pinimg.com/736x/4d/5e/7c/4d5e7c77bb9bcbcd1b4d6e8c6e0bff6d.jpg' }}">
                    </div>

                    <h2 class="name">{{ Auth::user()->username }} #{{ Auth::user()->id }}</h2>
                    <div class="role">{{ Auth::user()->role_id == 1 ? 'Quản trị viên' : 'Người dùng' }}</div>
                    <div class="role mt-1">Trạng thái: {{ Auth::user()->status ? 'Đang hoạt động' : 'Bị khoá' }}</div>

                    <div class="nav-menu">
                        <a href="/" class="nav-item">
                            <i class="fa fa-home"></i> Trang chủ
                        </a>
                        <a href="{{ route('profile') }}" class="nav-item">
                            <i class="fa fa-user"></i> Hồ sơ cá nhân
                        </a>
                        <a href="{{ route('change.password') }}" class="nav-item">
                            <i class="fa fa-key"></i> Đổi mật khẩu
                        </a>
                        <a href="{{ route('wishlist.index') }}" class="nav-item">
                            <i class="fa fa-heart"></i> Sản phẩm yêu thích
                        </a>
                        <a href="{{ route('orders.index') }}" class="nav-item active">
                            <i class="fa fa-receipt"></i> Lịch sử đặt hàng
                        </a>
                        <a href="{{ route('recently-viewed.index') }}" class="nav-item">
                            <i class="fa fa-history"></i> Sản phẩm đã xem
                        </a>
                        <a href="#" class="nav-item">
                            <i class="fa fa-clock-rotate-left"></i> Nhật ký hoạt động
                        </a>
                        <a href="#" class="nav-item">
                            <i class="fa fa-headset"></i> Hỗ trợ người dùng
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="nav-item w-100 text-start">
                                <i class="fa fa-right-from-bracket"></i> Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- RIGHT CONTENT -->
            <div class="col-lg-8">
                <div class="right">
                    <h1 class="title">Lịch sử đặt hàng</h1>

                    @if(session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; background: rgba(16, 185, 129, 0.2); color: #10b981; border: none;">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: invert(1);"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: none;">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: invert(1);"></button>
                        </div>
                    @endif

                    <!-- Filter Form -->
                    <div class="filter-section">
                        <form class="row g-3 align-items-end" method="get" action="{{ route('orders.index') }}">
                            <div class="col-md-7">
                                <label class="form-label small fw-semibold text-secondary" for="status">Lọc theo trạng thái</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Tất cả trạng thái</option>
                                    @foreach ($statusOptions as $value => $label)
                                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5 d-flex gap-2">
                                <button class="btn btn-filter flex-grow-1" type="submit">
                                    <i class="bi bi-funnel me-1"></i> Lọc đơn
                                </button>
                                <a class="btn btn-clear-filter d-flex align-items-center justify-content-center" href="{{ route('orders.index') }}">Xóa lọc</a>
                            </div>
                        </form>
                    </div>

                    <!-- Order Table -->
                    <div class="table-wrap">
                        @if ($orders->count() > 0)
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Mã đơn</th>
                                            <th>Ngày đặt</th>
                                            <th>Sản phẩm</th>
                                            <th>Tổng tiền</th>
                                            <th>Trạng thái</th>
                                            <th class="text-end">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>
                                                    <code>{{ $order->code }}</code>
                                                    <div class="small text-secondary mt-1">{{ $order->payment_method === 'cod' ? 'COD' : 'Online' }}</div>
                                                </td>
                                                <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                                                <td>{{ $order->items_count }} sản phẩm</td>
                                                <td class="fw-bold text-info">{{ number_format((float) $order->total, 0, ',', '.') }}đ</td>
                                                <td>
                                                    <span class="badge-status">{{ $statusOptions[$order->status] ?? $order->status }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-inline-flex gap-2">
                                                        <a class="btn btn-sm btn-primary px-3 py-1" style="border-radius: 8px;" href="{{ route('orders.show', $order) }}">
                                                            Chi tiết
                                                        </a>
                                                        @if (\App\Support\OrderStatus::canBeCancelled($order->status))
                                                            <form method="post" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?');" class="m-0">
                                                                @csrf
                                                                @method('patch')
                                                                <button class="btn btn-sm btn-outline-danger px-3 py-1" style="border-radius: 8px;" type="submit">
                                                                    Hủy
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-history">
                                <i class="fa fa-receipt"></i>
                                <h4>Chưa có đơn hàng nào</h4>
                                <p class="text-muted">Bạn chưa thực hiện giao dịch nào trên NeoMart.</p>
                                <a class="btn btn-primary mt-3 px-4 py-2" style="border-radius: 12px; background: linear-gradient(135deg, #3b82f6, #2563eb); border: none;" href="{{ route('products.index') }}">
                                    Mua sắm ngay
                                </a>
                            </div>
                        @endif
                    </div>

                    @if ($orders->hasPages())
                        <div class="mt-4">{{ $orders->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
