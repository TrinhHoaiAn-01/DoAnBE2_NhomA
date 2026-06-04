<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm đã xem</title>

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

        /* History items list */
        .history-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 18px;
            padding: 15px;
            margin-bottom: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .history-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .history-img {
            width: 90px;
            height: 90px;
            object-fit: contain;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.9);
            padding: 5px;
        }

        .product-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .product-name {
            font-size: 17px;
            font-weight: 600;
            color: white;
            text-decoration: none;
            margin-bottom: 5px;
            transition: color 0.2s;
        }

        .product-name:hover {
            color: #60a5fa;
        }

        .product-cat {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 5px;
        }

        .product-price {
            font-size: 18px;
            font-weight: 700;
            color: #ef4444;
        }

        .action-btns {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: flex-end;
        }

        .btn-add-cart {
            padding: 10px 20px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            transition: transform 0.2s;
        }

        .btn-add-cart:hover {
            transform: translateY(-2px);
            color: white;
        }

        .btn-remove-history {
            padding: 10px 15px;
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 12px;
            transition: all 0.2s;
        }

        .btn-remove-history:hover {
            background: #ef4444;
            color: white;
        }

        .btn-clear-all {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-clear-all:hover {
            background: #ef4444;
            color: white;
        }

        .empty-history {
            text-align: center;
            padding: 40px;
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
                        @if(Route::has('wishlist.index'))
                        <a href="{{ route('wishlist.index') }}" class="nav-item">
                            <i class="fa fa-heart"></i> Sản phẩm yêu thích
                        </a>
                        @endif
                        @if(Route::has('orders.index'))
                        <a href="{{ route('orders.index') }}" class="nav-item">
                            <i class="fa fa-receipt"></i> Lịch sử đặt hàng
                        </a>
                        @endif
                        @if(Route::has('recently-viewed.index'))
                        <a href="{{ route('recently-viewed.index') }}" class="nav-item active">
                            <i class="fa fa-history"></i> Sản phẩm đã xem
                        </a>
                        @endif
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
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="title mb-0">Sản phẩm đã xem</h1>
                        @if($products->count() > 0)
                            <button type="button" class="btn-clear-all" onclick="clearAllHistory()">
                                <i class="fa fa-trash-can"></i> Xoá lịch sử
                            </button>
                        @endif
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; background: rgba(16, 185, 129, 0.2); color: #10b981; border: none;">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: invert(1);"></button>
                        </div>
                    @endif

                    @if($products->count() > 0)
                        <div class="row" id="history-container">
                            @foreach($products as $product)
                                <div class="col-12" id="history-item-{{ $product->id }}">
                                    <div class="history-item d-flex flex-wrap align-items-center">
                                        <div class="col-md-2 text-center text-md-start mb-3 mb-md-0">
                                            <img src="{{ $product->image_url ?: 'https://placehold.co/100?text='.urlencode($product->name) }}" class="history-img" alt="{{ $product->name }}">
                                        </div>
                                        <div class="col-md-6 product-info mb-3 mb-md-0 ps-md-3">
                                            <a href="{{ route('products.show', $product) }}" class="product-name">{{ $product->name }}</a>
                                            <span class="product-cat">{{ $product->category?->name }}</span>
                                            <span class="product-price">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                        </div>
                                        <div class="col-md-4 action-btns">
                                            <form action="{{ route('cart.add', $product) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn-add-cart">
                                                    <i class="fa fa-shopping-cart"></i> Mua ngay
                                                </button>
                                            </form>

                                            <button type="button" class="btn-remove-history" onclick="removeHistoryItem('{{ $product->id }}')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-history">
                            <i class="fa fa-history"></i>
                            <h4>Lịch sử xem trống</h4>
                            <p class="text-muted">Bạn chưa xem sản phẩm nào gần đây.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary mt-3 px-4 py-2" style="border-radius: 12px; background: linear-gradient(135deg, #3b82f6, #2563eb); border: none;">
                                Khám phá ngay
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function removeHistoryItem(productId) {
        if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi lịch sử đã xem?')) {
            return;
        }

        const url = `/recently-viewed/${productId}`;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                _method: 'DELETE'
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const element = document.getElementById(`history-item-${productId}`);
                if (element) {
                    element.remove();
                }

                if (document.querySelectorAll('.history-item').length === 0) {
                    window.location.reload();
                }
            } else {
                alert(data.message || 'Có lỗi xảy ra.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Không thể kết nối đến máy chủ.');
        });
    }

    function clearAllHistory() {
        if (!confirm('Bạn có chắc muốn xóa toàn bộ lịch sử sản phẩm đã xem?')) {
            return;
        }

        const url = '/recently-viewed/clear';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Không thể kết nối đến máy chủ.');
        });
    }
</script>
</body>

</html>
