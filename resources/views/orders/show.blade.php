<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng {{ $order->code }}</title>

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

        /* Section Cards */
        .detail-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 18px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            border-left: 4px solid #3b82f6;
            padding-left: 10px;
        }

        /* Tracking Steps */
        .tracking-step {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 14px;
            padding: 15px;
            height: 100%;
        }

        .icon-circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .done-step { background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); }
        .active-step { background: rgba(59, 130, 246, 0.2); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.3); }
        .pending-step { background: rgba(255, 255, 255, 0.08); color: #94a3b8; border: 1px solid rgba(255, 255, 255, 0.1); }
        .danger-step { background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }

        /* Items */
        .order-product-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .order-product-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .order-product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
            background: white;
            padding: 2px;
        }

        .item-info {
            flex-grow: 1;
            padding-left: 15px;
        }

        /* Buttons */
        .btn-cancel-order {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-cancel-order:hover {
            background: #ef4444;
            color: white;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.08);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
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
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                        <div>
                            <span class="badge-status mb-2 d-inline-block">{{ $statusOptions[$order->status] ?? $order->status }}</span>
                            <h1 class="title mb-0">Đơn hàng: {{ $order->code }}</h1>
                            <p class="text-muted small mt-1 mb-0">Đặt lúc {{ $order->created_at?->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            @if ($canCancel)
                                <form method="post" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?');" class="m-0">
                                    @csrf
                                    @method('patch')
                                    <button class="btn-cancel-order" type="submit">
                                        <i class="fa fa-xmark me-1"></i> Hủy đơn hàng
                                    </button>
                                </form>
                            @endif
                            <a class="btn-back" href="{{ route('orders.index') }}">
                                <i class="fa fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>

                    <!-- SUCCESS / ERROR ALERTS -->
                    @if(session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; background: rgba(16, 185, 129, 0.2); color: #10b981; border: none;">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: invert(1);"></button>
                        </div>
                    @endif

                    <div class="row g-4">
                        <!-- Tracking -->
                        <div class="col-12">
                            <div class="detail-card">
                                <h2 class="section-title">Tiến trình đơn hàng</h2>
                                <div class="row g-3">
                                    @foreach ($trackingSteps as $step)
                                        @php
                                            $isDone = $step['state'] === 'done';
                                            $isActive = $step['state'] === 'active';
                                            $isDanger = $step['state'] === 'danger';
                                            $stepClass = $isDanger ? 'danger-step' : ($isDone ? 'done-step' : ($isActive ? 'active-step' : 'pending-step'));
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="tracking-step d-flex gap-3 align-items-center">
                                                <div class="icon-circle {{ $stepClass }}">
                                                    <i class="fa {{ $step['icon'] === 'bi-cart' ? 'fa-shopping-cart' : ($step['icon'] === 'bi-check-circle' ? 'fa-check-circle' : ($step['icon'] === 'bi-truck' ? 'fa-truck' : ($step['icon'] === 'bi-house' ? 'fa-home' : 'fa-info-circle'))) }}"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $step['label'] }}</div>
                                                    <div class="small text-secondary">{{ $step['description'] }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Products List -->
                        <div class="col-12">
                            <div class="detail-card">
                                <h2 class="section-title">Sản phẩm đã đặt</h2>
                                <div class="order-products">
                                    @foreach ($order->items as $item)
                                        <div class="order-product-item">
                                            <img class="order-product-img"
                                                src="{{ $item->product?->image_url ?: 'https://placehold.co/96x96?text=NeoMart' }}"
                                                alt="{{ $item->product_name }}">
                                            <div class="item-info">
                                                <div class="fw-semibold">{{ $item->product_name }}</div>
                                                <div class="small text-muted">SKU: {{ $item->sku ?: 'N/A' }} &nbsp;·&nbsp; SL: {{ $item->quantity }}</div>
                                            </div>
                                            <div class="fw-bold text-end">
                                                {{ number_format((float) $item->subtotal, 0, ',', '.') }}đ
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Shipping and Payment Details -->
                        <div class="col-md-6">
                            <div class="detail-card h-100">
                                <h2 class="section-title">Thông tin giao nhận</h2>
                                <table class="table table-borderless small" style="--bs-table-bg: transparent;">
                                    <tbody>
                                        <tr>
                                            <td class="text-secondary py-1 ps-0" style="width: 35%;">Người nhận:</td>
                                            <td class="fw-semibold py-1 pe-0">{{ $order->customer_name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary py-1 ps-0">Điện thoại:</td>
                                            <td class="fw-semibold py-1 pe-0">{{ $order->customer_phone }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary py-1 ps-0">Địa chỉ:</td>
                                            <td class="py-1 pe-0">{{ $order->shipping_address }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary py-1 ps-0">Vận chuyển:</td>
                                            <td class="py-1 pe-0">{{ $shippingDistrictLabel }} - {{ $shippingServiceLabel }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary py-1 ps-0">Lịch giao:</td>
                                            <td class="py-1 pe-0">{{ $order->delivery_date?->format('d/m/Y') ?: 'Chưa chọn' }} - {{ $deliveryTimeSlotLabel }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-card h-100">
                                <h2 class="section-title">Thanh toán</h2>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Tạm tính:</span>
                                    <span>{{ number_format((float) $order->subtotal, 0, ',', '.') }}đ</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Phí giao hàng:</span>
                                    <span>{{ number_format((float) $order->shipping_fee, 0, ',', '.') }}đ</span>
                                </div>
                                @if ((float) $order->discount_total > 0)
                                    <div class="d-flex justify-content-between mb-2 text-success">
                                        <span>Giảm giá:</span>
                                        <span>-{{ number_format((float) $order->discount_total, 0, ',', '.') }}đ</span>
                                    </div>
                                @endif
                                <hr style="border-color: rgba(255,255,255,0.1);">
                                <div class="d-flex justify-content-between fs-5 fw-bold text-info">
                                    <span>Tổng cộng:</span>
                                    <span>{{ number_format((float) $order->total, 0, ',', '.') }}đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
