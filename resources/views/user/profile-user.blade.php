<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Hồ sơ người dùng
    </title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="{{ asset('assets/site-preferences.css') }}" rel="stylesheet">

    <!-- FONT -->

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
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
            background: #16a34a;
            top: -80px;
            left: -80px;
        }

        .bg2 {
            background: #047857;
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
            background: linear-gradient(180deg, #16a34a, #15803d);
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

        .nav-item:hover {
            transform: translateX(6px);
            background: rgba(255, 255, 255, 0.2);
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

        label {
            font-size: 14px;
            margin-bottom: 6px;
        }

        .form-control {
            height: 35px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.34);
            color: white;
            margin-bottom: 10px;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: #86efac;
            box-shadow: 0 0 0 0.2rem rgba(34, 197, 94, 0.2);
            color: white;
        }

        .form-control:disabled {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.18);
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.55);
            opacity: 1;
        }

        select.form-control,
        input[type="file"].form-control {
            min-height: 44px;
            height: 44px;
            padding-top: 0.55rem;
            padding-bottom: 0.55rem;
            line-height: 1.3;
        }

        /* OPTION COLOR */

        select.form-control option {
            color: black;
            background: white;
        }

        textarea.form-control {
            height: 100px;
        }

        /* BUTTON */

        .btn-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 25px;
        }

        .btn-save {
            padding: 12px 40px;
            border: none;
            border-radius: 30px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-save:hover {
            transform: translateY(-2px);
        }

        /* ALERT */

        .custom-alert {
            border: none;
            border-radius: 16px;
            padding: 14px 18px;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
        }

        .btn-close {
            filter: invert(1);
        }

        /* REMOVE SPINNER */

        .no-spinner::-webkit-outer-spin-button,
        .no-spinner::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .no-spinner {
            -moz-appearance: textfield;
        }

        .fake-disabled {

            background: rgba(255, 255, 255, 0.05) !important;

            border-color: rgba(255, 255, 255, 0.18) !important;

            color: rgba(255, 255, 255, 0.6) !important;

            cursor: default;

            opacity: 0.8;
        }

        .nav-item.active {
            background: rgba(255, 255, 255, 0.24);
            transform: translateX(6px);
        }

        .section-subtitle {
            margin-top: -14px;
            margin-bottom: 24px;
            color: rgba(255, 255, 255, 0.72);
        }

        .filter-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .filter-link {
            padding: 9px 14px;
            border-radius: 999px;
            color: rgba(255, 255, 255, 0.82);
            text-decoration: none;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            font-size: 14px;
        }

        .filter-link.active,
        .filter-link:hover {
            color: #ffffff;
            background: #16a34a;
            border-color: #86efac;
        }

        .timeline {
            display: grid;
            gap: 14px;
        }

        .activity-item {
            display: grid;
            grid-template-columns: 46px 1fr;
            gap: 14px;
            padding: 16px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .activity-icon {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: rgba(22, 163, 74, 0.24);
            color: #93c5fd;
            font-size: 18px;
        }

        .activity-title {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
        }

        .activity-time {
            margin-top: 4px;
            color: rgba(255, 255, 255, 0.64);
            font-size: 13px;
        }

        .activity-description {
            margin: 10px 0 0;
            color: rgba(255, 255, 255, 0.86);
        }

        .meta-grid {
            margin-top: 12px;
            display: grid;
            gap: 8px;
        }

        .meta-line {
            padding: 10px 12px;
            border-radius: 12px;
            background: rgba(15, 23, 42, 0.38);
            color: rgba(255, 255, 255, 0.78);
            font-size: 13px;
            overflow-wrap: anywhere;
        }

        .empty-state {
            padding: 34px 20px;
            border-radius: 18px;
            text-align: center;
            background: rgba(255, 255, 255, 0.08);
            border: 1px dashed rgba(255, 255, 255, 0.2);
        }

        .pager {
            margin-top: 22px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
        }

        .pager a,
        .pager span {
            padding: 10px 15px;
            border-radius: 999px;
            color: #ffffff;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .pager span {
            opacity: 0.45;
        }

        .support-control {
            min-height: 52px;
            padding: 12px 14px;
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        textarea.support-control {
            min-height: 150px;
            resize: vertical;
        }

        select.support-control option {
            color: #111827;
            background: #ffffff;
        }

        .admin-line {
            margin-top: 18px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        html[data-theme="light"] body.profile-page .form-control {
            background: #ffffff !important;
            border-color: #94a3b8 !important;
            color: #0f172a !important;
        }

        html[data-theme="light"] body.profile-page .form-control:focus {
            border-color: #16a34a !important;
            box-shadow: 0 0 0 0.2rem rgba(22, 163, 74, 0.18) !important;
        }

        html[data-theme="light"] body.profile-page .form-control:disabled,
        html[data-theme="light"] body.profile-page .fake-disabled {
            background: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
            color: #64748b !important;
        }

        html[data-theme="light"] body.profile-page .form-control::placeholder {
            color: #64748b !important;
        }

        html[data-theme="dark"] body.profile-page .form-control {
            background: rgba(15, 23, 42, 0.7) !important;
            border-color: rgba(255, 255, 255, 0.34) !important;
            color: #ffffff !important;
        }

        html[data-theme="dark"] body.profile-page .form-control:focus {
            border-color: #86efac !important;
            box-shadow: 0 0 0 0.2rem rgba(34, 197, 94, 0.22) !important;
        }

        html[data-theme="dark"] body.profile-page .form-control:disabled,
        html[data-theme="dark"] body.profile-page .fake-disabled {
            background: rgba(15, 23, 42, 0.45) !important;
            border-color: rgba(255, 255, 255, 0.18) !important;
            color: rgba(255, 255, 255, 0.64) !important;
        }
    </style>

</head>

<body class="standalone-user-page profile-page">

    @php
        $profileSection = $profileSection ?? 'profile';
    @endphp

    <!-- BACKGROUND -->
    <div class="bg bg1"></div>
    <div class="bg bg2"></div>

    <div class="wrapper">

        <div class="card-profile">

            <div class="row g-0">

                <!-- LEFT -->
                <div class="col-lg-4">

                    <div class="left text-center">

                        <!-- AVATAR -->
                        <div class="avatar mb-3">

                            <img src="{{ Auth::user()->avatar_url
    ? asset(Auth::user()->avatar_url)
    : 'https://i.pinimg.com/736x/4d/5e/7c/4d5e7c77bb9bcbcd1b4d6e8c6e0bff6d.jpg' }}">

                        </div>

                        <!-- NAME -->
                        <h2 class="name">
                            {{ Auth::user()->username }} #{{ Auth::user()->id }}
                        </h2>

                        <!-- ROLE -->
                        <div class="role">

                            {{ Auth::user()->role_id == 1
    ? 'Quản trị viên'
    : 'Người dùng' }}

                        </div>

                        <!-- STATUS -->
                        <div class="role mt-1">

                            Trạng thái:

                            {{ Auth::user()->status
    ? 'Đang hoạt động'
    : 'Bị khoá' }}

                        </div>

                        <!-- MENU -->
                        <div class="nav-menu">

                            <a href="/" class="nav-item">

                                <i class="fa fa-home"></i>

                                Trang chủ

                            </a>

                            <a href="{{ route('profile') }}" class="nav-item {{ $profileSection === 'profile' ? 'active' : '' }}">

                                <i class="fa fa-user"></i>

                                Thông tin hồ sơ

                            </a>

                            <a href="{{ route('change.password') }}" class="nav-item">

                                <i class="fa fa-key"></i>

                                Đổi mật khẩu

                            </a>

                            <!-- LOG ACTIVITY -->
                            <a href="{{ route('account.activity.logs') }}" class="nav-item {{ $profileSection === 'activity' ? 'active' : '' }}">

                                <i class="fa fa-clock-rotate-left"></i>

                                Nhật ký hoạt động

                            </a>

                            <!-- SUPPORT -->
                            <a href="{{ route('support.user') }}" class="nav-item {{ $profileSection === 'support' ? 'active' : '' }}">

                                <i class="fa fa-headset"></i>

                                Hỗ trợ người dùng

                            </a>

                            <!-- DELETE -->
                            <button class="nav-item danger" data-bs-toggle="modal" data-bs-target="#deleteModal">

                                <i class="fa fa-trash"></i>

                                Xoá tài khoản

                            </button>

                            <!-- LOGOUT -->
                            <form action="{{ route('logout') }}" method="POST">

                                @csrf

                                <button class="nav-item">

                                    <i class="fa fa-right-from-bracket"></i>

                                    Đăng xuất

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

                <!-- RIGHT -->
                <div class="col-lg-8">

                    <div class="right">

                        @if($profileSection === 'profile')

                        <!-- TITLE -->
                        <div class="title">
                            Thông tin hồ sơ
                        </div>

                        <!-- SUCCESS -->
                        @if(session('success'))

                            <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">

                                {{ session('success') }}

                                <button type="button" class="btn-close" data-bs-dismiss="alert">
                                </button>

                            </div>

                        @endif

                        <!-- ERROR -->
                        @if($errors->any())

                            <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">

                                <ul class="mb-0">

                                    @foreach($errors->all() as $error)

                                        <li>{{ $error }}</li>

                                    @endforeach

                                </ul>

                                <button type="button" class="btn-close" data-bs-dismiss="alert">
                                </button>

                            </div>

                        @endif

                        <!-- FORM -->
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">

                            @csrf

                            <!-- HIDDEN USERNAME -->
                            <input type="hidden" name="username" value="{{ Auth::user()->username }}">

                            <!-- AVATAR -->
                            <label class="form-label">
                                Ảnh đại diện
                            </label>

                            <input type="file" name="avatar" class="form-control">

                            <!-- NAME -->
                            <label class="form-label">
                                Họ và tên
                            </label>

                            <input type="text" name="name" value="{{ Auth::user()->name }}" class="form-control"
                                placeholder="Nhập họ và tên">

                            <!-- USERNAME + ID -->
                            <div class="row">

                                <!-- USERNAME -->
                                <div class="col-md-8">

                                    <label class="form-label">
                                        Tên đăng nhập
                                    </label>

                                    <input type="text" class="form-control" value="{{ Auth::user()->username }}"
                                        disabled>

                                </div>

                                <!-- USER ID -->
                                <div class="col-md-4">

                                    <label class="form-label">
                                        ID người dùng
                                    </label>

                                    <input type="number" name="user_id" class="form-control no-spinner"
                                        value="{{ old('user_id', Auth::user()->id) }}" placeholder="ID">

                                </div>

                            </div>

                            <!-- EMAIL + PHONE -->
                            <div class="row">

                                <!-- EMAIL -->
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Email
                                    </label>

                                    <input type="email" name="email" value="{{ Auth::user()->email }}"
                                        class="form-control fake-disabled" readonly>

                                </div>

                                <!-- PHONE -->
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Số điện thoại
                                    </label>

                                    <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                                        class="form-control" placeholder="Nhập số điện thoại">

                                </div>

                            </div>

                            <!-- GENDER + DATE -->
                            <div class="row">

                                <!-- GENDER -->
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Giới tính
                                    </label>

                                    <select name="gender" class="form-control">

                                        <option value="">
                                            Chọn giới tính
                                        </option>

                                        <option value="male" {{ Auth::user()->gender == 'male'
    ? 'selected'
    : '' }}>
                                            Nam
                                        </option>

                                        <option value="female" {{ Auth::user()->gender == 'female'
    ? 'selected'
    : '' }}>
                                            Nữ
                                        </option>

                                        <option value="other" {{ Auth::user()->gender == 'other'
    ? 'selected'
    : '' }}>
                                            Khác
                                        </option>

                                    </select>

                                </div>

                                <!-- DATE -->
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Ngày sinh
                                    </label>

                                    <input type="date" name="date_of_birth" value="{{ Auth::user()->date_of_birth
    ? \Carbon\Carbon::parse(Auth::user()->date_of_birth)->format('Y-m-d')
    : '' }}" class="form-control">

                                </div>

                            </div>

                            <!-- ADDRESS -->
                            <label class="form-label">
                                Địa chỉ
                            </label>

                            <textarea name="home_address" class="form-control"
                                placeholder="Nhập địa chỉ">{{ old('home_address', Auth::user()->home_address) }}</textarea>

                            <!-- BUTTON CENTER -->
                            <div class="btn-wrapper">

                                <button type="submit" class="btn-save">

                                    Lưu thay đổi

                                </button>

                            </div>

                        </form>

                        @elseif($profileSection === 'activity')

                        <div class="title">
                            Nhật ký hoạt động
                        </div>

                        <p class="section-subtitle">
                            Theo dõi lịch sử đăng nhập, thay đổi hồ sơ và các lần mua hàng của tài khoản.
                        </p>

                        <div class="filter-row" aria-label="Lọc nhật ký hoạt động">

                            <a class="filter-link {{ ($type ?? '') === '' ? 'active' : '' }}"
                                href="{{ route('account.activity.logs') }}">
                                Tất cả
                            </a>

                            @foreach(($typeOptions ?? []) as $value => $label)

                                <a class="filter-link {{ ($type ?? '') === $value ? 'active' : '' }}"
                                    href="{{ route('account.activity.logs', ['type' => $value]) }}">
                                    {{ $label }}
                                </a>

                            @endforeach

                        </div>

                        @if(($logs ?? collect())->isEmpty())

                            <div class="empty-state">
                                <i class="fa fa-clock-rotate-left mb-3 fs-3"></i>
                                <div>Chưa có hoạt động nào được ghi nhận.</div>
                            </div>

                        @else

                            <div class="timeline">

                                @foreach($logs as $log)

                                    @php
                                        $icon = match ($log->type) {
                                            'login' => 'fa-right-to-bracket',
                                            'profile_update' => 'fa-user-pen',
                                            'purchase' => 'fa-bag-shopping',
                                            default => 'fa-circle-info',
                                        };
                                        $changes = $log->metadata['changes'] ?? [];
                                    @endphp

                                    <article class="activity-item">

                                        <div class="activity-icon">
                                            <i class="fa {{ $icon }}"></i>
                                        </div>

                                        <div>

                                            <h3 class="activity-title">
                                                {{ $log->title }}
                                            </h3>

                                            <div class="activity-time">

                                                {{ $log->created_at->format('d/m/Y H:i:s') }}

                                                @if($log->ip_address)
                                                    · IP: {{ $log->ip_address }}
                                                @endif

                                            </div>

                                            @if($log->description)

                                                <p class="activity-description">
                                                    {{ $log->description }}
                                                </p>

                                            @endif

                                            @if($changes !== [])

                                                <div class="meta-grid">

                                                    @foreach($changes as $change)

                                                        <div class="meta-line">
                                                            <strong>{{ $change['label'] ?? $change['field'] ?? 'Trường dữ liệu' }}:</strong>
                                                            {{ $change['old'] ?? 'Trống' }} → {{ $change['new'] ?? 'Trống' }}
                                                        </div>

                                                    @endforeach

                                                </div>

                                            @elseif($log->type === 'purchase' && is_array($log->metadata))

                                                <div class="meta-grid">

                                                    <div class="meta-line">
                                                        <strong>Mã đơn hàng:</strong> {{ $log->metadata['order_code'] ?? 'Không xác định' }}
                                                    </div>

                                                    <div class="meta-line">
                                                        <strong>Tổng tiền:</strong> {{ number_format((float) ($log->metadata['total'] ?? 0), 0, ',', '.') }}đ
                                                    </div>

                                                </div>

                                            @endif

                                        </div>

                                    </article>

                                @endforeach

                            </div>

                            <div class="pager">

                                @if($logs->onFirstPage())
                                    <span>Trang trước</span>
                                @else
                                    <a href="{{ $logs->previousPageUrl() }}">Trang trước</a>
                                @endif

                                @if($logs->hasMorePages())
                                    <a href="{{ $logs->nextPageUrl() }}">Trang sau</a>
                                @else
                                    <span>Trang sau</span>
                                @endif

                            </div>

                        @endif

                        @elseif($profileSection === 'support')

                        <div class="title">
                            Hỗ trợ người dùng
                        </div>

                        <p class="section-subtitle">
                            Gửi thông tin lỗi để admin kiểm tra và phản hồi qua email của bạn.
                        </p>

                        @if(session('success'))

                            <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">

                                {{ session('success') }}

                                <button type="button" class="btn-close" data-bs-dismiss="alert">
                                </button>

                            </div>

                        @endif

                        @if($errors->any())

                            <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">

                                <ul class="mb-0">

                                    @foreach($errors->all() as $error)

                                        <li>{{ $error }}</li>

                                    @endforeach

                                </ul>

                                <button type="button" class="btn-close" data-bs-dismiss="alert">
                                </button>

                            </div>

                        @endif

                        <form method="POST" action="{{ route('support.send') }}">

                            @csrf

                            <div class="mb-3">

                                <label class="form-label" for="support_email">
                                    Email người dùng
                                </label>

                                <input id="support_email" type="email" class="form-control support-control" name="email"
                                    value="{{ old('email', Auth::user()->email) }}" placeholder="email@example.com" required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label" for="issue_type">
                                    Chọn lỗi
                                </label>

                                <select id="issue_type" name="issue_type" class="form-control support-control" required>
                                    <option value="">-- Chọn loại lỗi --</option>
                                    <option value="account" {{ old('issue_type') === 'account' ? 'selected' : '' }}>Lỗi tài khoản</option>
                                    <option value="order" {{ old('issue_type') === 'order' ? 'selected' : '' }}>Lỗi đơn hàng</option>
                                    <option value="payment" {{ old('issue_type') === 'payment' ? 'selected' : '' }}>Lỗi thanh toán</option>
                                    <option value="system" {{ old('issue_type') === 'system' ? 'selected' : '' }}>Lỗi hệ thống</option>
                                    <option value="other" {{ old('issue_type') === 'other' ? 'selected' : '' }}>Lỗi khác</option>
                                </select>

                            </div>

                            <div class="mb-4">

                                <label class="form-label" for="description">
                                    Mô tả lỗi
                                </label>

                                <textarea id="description" name="description" class="form-control support-control"
                                    placeholder="Mô tả lỗi bạn đang gặp..." required>{{ old('description') }}</textarea>

                            </div>

                            <div class="btn-wrapper">

                                <button type="submit" class="btn-save">
                                    <i class="fa-solid fa-paper-plane"></i>
                                    Gửi
                                </button>

                            </div>

                        </form>

                        <div class="admin-line">
                            <i class="fa-solid fa-envelope me-2"></i>
                            Email admin: {{ config('mail.from.address', 'trinhhoaia03@gmail.com') }}
                        </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- DELETE MODAL -->
    <div class="modal fade" id="deleteModal" tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content bg-dark text-white rounded-4">

                <div class="modal-header border-0">

                    <h5>
                        Xác nhận xoá tài khoản
                    </h5>

                </div>

                <div class="modal-body">

                    Bạn có chắc chắn muốn xoá tài khoản không?

                    <br>

                    <span class="text-danger">
                        Hành động này không thể hoàn tác.
                    </span>

                </div>

                <div class="modal-footer border-0">

                    <button class="btn btn-secondary" data-bs-dismiss="modal">

                        Huỷ

                    </button>

                    <form action="{{ route('profile.delete') }}" method="POST">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger">

                            Xoá tài khoản

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <!-- BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('assets/site-preferences.js') }}"></script>
</body>

</html>
