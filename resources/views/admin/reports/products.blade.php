@extends('layouts.admin')

@section('title', 'Báo cáo sản phẩm')

@section('content')
    @php
        $money = fn ($value) => number_format((float) $value, 0, ',', '.') . 'đ';
    @endphp

    <style>
        .product-report-shell {
            display: grid;
            gap: 24px;
        }

        .product-report-panel {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #ffffff;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        }
    </style>

    <div class="product-report-shell">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
            <div>
                <h1 class="h3 fw-bold mb-1">Báo cáo sản phẩm bán chạy / bán chậm</h1>
                <p class="text-muted mb-0">Theo dõi hiệu quả bán hàng của từng sản phẩm.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Tổng quan
            </a>
        </div>

        <div class="product-report-panel p-4">
            <div class="text-muted">Dữ liệu sản phẩm sẽ được nạp sau khi kết nối controller.</div>
        </div>
    </div>
@endsection
