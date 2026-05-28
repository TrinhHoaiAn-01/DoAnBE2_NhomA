@extends('layouts.admin')

@section('title', 'Báo cáo doanh thu')

@section('content')
    @php
        $money = fn ($value) => number_format((float) $value, 0, ',', '.') . 'đ';
    @endphp

    <style>
        .report-shell {
            display: grid;
            gap: 24px;
        }

        .report-panel {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #ffffff;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        }

        .report-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #ffffff;
            min-height: 120px;
        }
    </style>

    <div class="report-shell">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
            <div>
                <h1 class="h3 fw-bold mb-1">Báo cáo doanh thu</h1>
                <p class="text-muted mb-0">Theo dõi doanh thu theo ngày, tháng và năm.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Tổng quan
            </a>
        </div>

        <div class="row g-3">
            <div class="col-12 col-md-4">
                <div class="report-card p-3">
                    <div class="text-muted small text-uppercase fw-semibold">Tổng doanh thu</div>
                    <div class="fs-4 fw-bold mt-2">{{ $money($totalRevenue ?? 0) }}</div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="report-card p-3">
                    <div class="text-muted small text-uppercase fw-semibold">Số đơn hợp lệ</div>
                    <div class="fs-4 fw-bold mt-2">{{ number_format($ordersCount ?? 0) }}</div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="report-card p-3">
                    <div class="text-muted small text-uppercase fw-semibold">Đơn đã thanh toán</div>
                    <div class="fs-4 fw-bold mt-2">{{ number_format($paidOrdersCount ?? 0) }}</div>
                </div>
            </div>
        </div>

        <div class="report-panel p-4">
            <div class="text-muted">Dữ liệu báo cáo sẽ được nạp sau khi kết nối route quản trị.</div>
        </div>
    </div>
@endsection
