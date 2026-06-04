<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hỗ trợ người dùng</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            letter-spacing: 0;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background: #0f172a;
            color: #ffffff;
            overflow-x: hidden;
        }

        .wrapper {
            min-height: 100vh;
            padding: 40px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .support-shell {
            width: 100%;
            max-width: 1180px;
            overflow: hidden;
            border-radius: 24px;
            background: #111827;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.45);
        }

        .sidebar {
            height: 100%;
            padding: 34px 24px;
            background: linear-gradient(180deg, #2563eb, #1d4ed8);
            text-align: center;
        }

        .avatar {
            width: 112px;
            height: 112px;
            margin: 0 auto 14px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid rgba(255, 255, 255, 0.28);
        }

        .user-name {
            margin: 0;
            font-size: 21px;
            font-weight: 700;
        }

        .user-role {
            margin-top: 5px;
            font-size: 14px;
            opacity: 0.9;
        }

        .nav-menu {
            margin-top: 30px;
            display: grid;
            gap: 12px;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 13px 15px;
            border: 0;
            border-radius: 14px;
            color: #ffffff;
            text-align: left;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.1);
            transition: 0.25s ease;
        }

        .nav-item-link:hover,
        .nav-item-link.active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.22);
            transform: translateX(4px);
        }

        .content {
            min-height: 620px;
            padding: 42px;
            background: #111827;
        }

        .page-title {
            margin: 0;
            font-size: 30px;
            font-weight: 700;
        }

        .page-subtitle {
            margin: 8px 0 0;
            max-width: 650px;
            color: rgba(255, 255, 255, 0.72);
        }

        .form-panel {
            margin-top: 28px;
            padding: 26px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.11);
        }

        .form-label {
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        .form-control,
        .form-select {
            min-height: 52px;
            border-radius: 14px;
            color: #ffffff;
            background: rgba(15, 23, 42, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.14);
        }

        .form-control:focus,
        .form-select:focus {
            color: #ffffff;
            background: rgba(15, 23, 42, 0.9);
            border-color: #60a5fa;
            box-shadow: 0 0 0 0.2rem rgba(96, 165, 250, 0.16);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.54);
        }

        .form-select option {
            color: #111827;
            background: #ffffff;
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .btn-support {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 52px;
            padding: 0 28px;
            border: 0;
            border-radius: 14px;
            color: #ffffff;
            font-weight: 700;
            background: #2563eb;
            transition: 0.25s ease;
        }

        .btn-support:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }

        .admin-line {
            margin-top: 18px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .alert {
            border: 0;
            border-radius: 14px;
        }

        .alert-success {
            color: #bbf7d0;
            background: rgba(34, 197, 94, 0.16);
        }

        .alert-danger {
            color: #fecaca;
            background: rgba(239, 68, 68, 0.16);
        }

        .btn-close {
            filter: invert(1);
        }

        @media (max-width: 991px) {
            .wrapper {
                align-items: flex-start;
                padding: 24px 14px;
            }

            .content {
                min-height: auto;
                padding: 30px 22px;
            }
        }

        @media (max-width: 575px) {
            .page-title {
                font-size: 24px;
            }

            .form-panel {
                padding: 18px;
            }

            .btn-support {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="support-shell">
            <div class="row g-0">
                <div class="col-lg-4">
                    <aside class="sidebar">
                        <img class="avatar" src="{{ Auth::user()->avatar_url ? asset(Auth::user()->avatar_url) : 'https://i.pinimg.com/736x/4d/5e/7c/4d5e7c77bb9bcbcd1b4d6e8c6e0bff6d.jpg' }}" alt="Ảnh đại diện">

                        <h1 class="user-name">{{ Auth::user()->username }} #{{ Auth::user()->id }}</h1>
                        <div class="user-role">
                            {{ Auth::user()->role_id == 5 ? 'Quản trị viên' : 'Người dùng' }}
                        </div>

                        <nav class="nav-menu">
                            <a href="{{ route('profile') }}" class="nav-item-link">
                                <i class="fa fa-user"></i>
                                Hồ sơ cá nhân
                            </a>

                            <a href="{{ route('orders.index') }}" class="nav-item-link">
                                <i class="fa fa-bag-shopping"></i>
                                Lịch sử mua hàng
                            </a>

                            <a href="{{ route('account.activity.logs') }}" class="nav-item-link">
                                <i class="fa fa-clock-rotate-left"></i>
                                Nhật ký hoạt động
                            </a>

                            <a href="{{ route('support.user') }}" class="nav-item-link active">
                                <i class="fa fa-headset"></i>
                                Hỗ trợ người dùng
                            </a>
                        </nav>
                    </aside>
                </div>

                <div class="col-lg-8">
                    <main class="content">
                        <h2 class="page-title">Hỗ trợ người dùng</h2>
                        <p class="page-subtitle">Gửi thông tin lỗi để admin kiểm tra và phản hồi qua email của bạn.</p>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                            </div>
                        @endif

                        <section class="form-panel">
                            <form method="POST" action="{{ route('support.send') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email người dùng</label>
                                    <input
                                        id="email"
                                        type="email"
                                        class="form-control"
                                        name="email"
                                        value="{{ old('email', Auth::user()->email) }}"
                                        placeholder="email@example.com"
                                        required
                                    >
                                </div>

                                <div class="mb-3">
                                    <label for="issue_type" class="form-label">Chọn lỗi</label>
                                    <select id="issue_type" name="issue_type" class="form-select" required>
                                        <option value="">-- Chọn loại lỗi --</option>
                                        <option value="account" {{ old('issue_type') === 'account' ? 'selected' : '' }}>Lỗi tài khoản</option>
                                        <option value="order" {{ old('issue_type') === 'order' ? 'selected' : '' }}>Lỗi đơn hàng</option>
                                        <option value="payment" {{ old('issue_type') === 'payment' ? 'selected' : '' }}>Lỗi thanh toán</option>
                                        <option value="system" {{ old('issue_type') === 'system' ? 'selected' : '' }}>Lỗi hệ thống</option>
                                        <option value="other" {{ old('issue_type') === 'other' ? 'selected' : '' }}>Lỗi khác</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="description" class="form-label">Mô tả lỗi</label>
                                    <textarea
                                        id="description"
                                        name="description"
                                        class="form-control"
                                        placeholder="Mô tả lỗi bạn đang gặp..."
                                        required
                                    >{{ old('description') }}</textarea>
                                </div>

                                <button type="submit" class="btn-support">
                                    <i class="fa-solid fa-paper-plane"></i>
                                    Gửi
                                </button>
                            </form>

                            <div class="admin-line">
                                <i class="fa-solid fa-envelope me-2"></i>
                                Email admin: {{ config('mail.from.address', 'trinhhoaia03@gmail.com') }}
                            </div>
                        </section>
                    </main>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
