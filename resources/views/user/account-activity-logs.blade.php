<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhật ký hoạt động tài khoản</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="{{ asset('assets/site-preferences.css') }}" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            letter-spacing: 0;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #0f172a, #1e293b, #111827);
            color: #ffffff;
            overflow-x: hidden;
        }

        .bg {
            position: fixed;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.35;
            pointer-events: none;
        }

        .bg1 {
            top: -80px;
            left: -80px;
            background: #16a34a;
        }

        .bg2 {
            right: -80px;
            bottom: -80px;
            background: #047857;
        }

        .wrapper {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            padding: 40px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .activity-shell {
            width: 100%;
            max-width: 1180px;
            border-radius: 26px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(18px);
        }

        .sidebar {
            height: 100%;
            padding: 34px 24px;
            background: linear-gradient(180deg, #16a34a, #15803d);
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
            border-radius: 14px;
            color: #ffffff;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.1);
            border: 0;
            text-align: left;
            transition: 0.25s ease;
        }

        .nav-item-link:hover,
        .nav-item-link.active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(4px);
        }

        .content {
            padding: 38px;
        }

        .page-title {
            margin: 0;
            font-size: 29px;
            font-weight: 700;
        }

        .page-subtitle {
            margin: 8px 0 0;
            color: rgba(255, 255, 255, 0.72);
        }

        .filter-row {
            margin-top: 24px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
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
            margin-top: 28px;
            display: grid;
            gap: 14px;
        }

        .activity-item {
            display: grid;
            grid-template-columns: 48px 1fr;
            gap: 14px;
            padding: 18px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .activity-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: rgba(22, 163, 74, 0.24);
            color: #93c5fd;
            font-size: 19px;
        }

        .activity-title {
            margin: 0;
            font-size: 17px;
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
            margin-top: 28px;
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

        @media (max-width: 991px) {
            .content {
                padding: 28px 22px;
            }
        }

        @media (max-width: 575px) {
            .wrapper {
                padding: 20px 12px;
                align-items: flex-start;
            }

            .page-title {
                font-size: 24px;
            }

            .activity-item {
                grid-template-columns: 40px 1fr;
                padding: 14px;
            }

            .activity-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
        }
    </style>
</head>

<body class="standalone-user-page activity-page">
    <div class="bg bg1"></div>
    <div class="bg bg2"></div>

    <div class="wrapper">
        <div class="activity-shell">
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

                            <a href="{{ route('account.activity.logs') }}" class="nav-item-link active">
                                <i class="fa fa-clock-rotate-left"></i>
                                Nhật ký hoạt động
                            </a>

                            <a href="{{ route('support.user') }}" class="nav-item-link">
                                <i class="fa fa-headset"></i>
                                Hỗ trợ người dùng
                            </a>
                        </nav>
                    </aside>
                </div>

                <div class="col-lg-8">
                    <main class="content">
                        <div>
                            <h2 class="page-title">Nhật ký hoạt động tài khoản</h2>
                            <p class="page-subtitle">Theo dõi lịch sử đăng nhập, thay đổi hồ sơ và các lần mua hàng của tài khoản.</p>
                        </div>

                        <div class="filter-row" aria-label="Lọc nhật ký hoạt động">
                            <a class="filter-link {{ $type === '' ? 'active' : '' }}" href="{{ route('account.activity.logs') }}">
                                Tất cả
                            </a>

                            @foreach($typeOptions as $value => $label)
                                <a class="filter-link {{ $type === $value ? 'active' : '' }}" href="{{ route('account.activity.logs', ['type' => $value]) }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>

                        @if($logs->isEmpty())
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
                                            <h3 class="activity-title">{{ $log->title }}</h3>
                                            <div class="activity-time">
                                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                                                @if($log->ip_address)
                                                    · IP: {{ $log->ip_address }}
                                                @endif
                                            </div>

                                            @if($log->description)
                                                <p class="activity-description">{{ $log->description }}</p>
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
                    </main>
                </div>
            </div>
        </div>
    </div>
<script src="{{ asset('assets/site-preferences.js') }}"></script>
</body>

</html>
