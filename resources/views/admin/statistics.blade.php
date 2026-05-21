@extends('layouts.admin')

@section('title', 'Thống kê cơ bản')

@section('content')
    <style>
        .report-panel {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #ffffff;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        }

        .report-metric {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #ffffff;
            min-height: 120px;
        }
    </style>

    <div class="d-grid gap-4">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
            <div>
                <h1 class="h3 fw-bold mb-1">Thống kê cơ bản</h1>
                <p class="text-muted mb-0">Tổng hợp nhanh tình hình bán hàng, thanh toán và sản phẩm.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Tổng quan
            </a>
        </div>

        <div class="report-panel p-4">
            <h2 class="h5 fw-bold mb-2">Báo cáo đang được chuẩn bị</h2>
            <p class="text-muted mb-0">Khu vực này sẽ hiển thị các chỉ số thống kê theo khoảng thời gian.</p>
        </div>
    </div>
@endsection
