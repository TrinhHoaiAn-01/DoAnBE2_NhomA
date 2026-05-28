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

        <div class="product-report-panel">
            <div class="p-3 border-bottom">
                <h2 class="h5 fw-bold mb-0">Sản phẩm bán chạy</h2>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>SKU</th>
                            <th class="text-end">Số lượng bán</th>
                            <th class="text-end">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bestSellingProducts as $product)
                            <tr>
                                <td class="fw-semibold">{{ $product->product_name }}</td>
                                <td class="text-muted">{{ $product->sku ?: 'Không có SKU' }}</td>
                                <td class="text-end">{{ number_format($product->sold_quantity) }}</td>
                                <td class="text-end fw-semibold">{{ $money($product->sold_revenue) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center py-4 text-muted" colspan="4">Chưa có dữ liệu bán hàng.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
