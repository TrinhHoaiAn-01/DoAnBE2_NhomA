@extends('layouts.admin')

@section('title', 'Thống kê cơ bản')

@section('content')
    @php
        $money = fn ($value) => number_format((float) $value, 0, ',', '.') . 'đ';
        $paymentLabels = [
            'cod' => 'COD',
            'bank_transfer' => 'Chuyển khoản',
            'e_wallet' => 'Ví điện tử',
        ];
    @endphp

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

        .report-chart {
            min-height: 320px;
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
            <form class="row g-3 align-items-end" method="GET" action="{{ route('admin.statistics') }}">
                <div class="col-12">
                    <div class="btn-group" role="group" aria-label="Bộ lọc nhanh">
                        @foreach([7 => '7 ngày', 30 => '30 ngày', 90 => '90 ngày'] as $days => $label)
                            <a class="btn {{ $period === $days ? 'btn-primary' : 'btn-outline-primary' }}"
                               href="{{ route('admin.statistics', ['period' => $days]) }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold" for="from_date">Từ ngày</label>
                    <input class="form-control" type="date" id="from_date" name="from_date" value="{{ $fromDate->format('Y-m-d') }}">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold" for="to_date">Đến ngày</label>
                    <input class="form-control" type="date" id="to_date" name="to_date" value="{{ $toDate->format('Y-m-d') }}">
                </div>
                <div class="col-12 col-md-4 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-funnel me-1"></i> Lọc dữ liệu
                    </button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.statistics') }}">Đặt lại</a>
                </div>
            </form>
        </div>

        <div class="row g-3">
            <div class="col-12 col-md-4">
                <div class="report-metric p-3">
                    <div class="text-muted small text-uppercase fw-semibold">Tổng doanh thu</div>
                    <div class="fs-4 fw-bold mt-2">{{ $money($totalRevenue) }}</div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="report-metric p-3">
                    <div class="text-muted small text-uppercase fw-semibold">Số đơn hợp lệ</div>
                    <div class="fs-4 fw-bold mt-2">{{ number_format($ordersCount) }}</div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="report-metric p-3">
                    <div class="text-muted small text-uppercase fw-semibold">Giá trị trung bình</div>
                    <div class="fs-4 fw-bold mt-2">{{ $money($averageOrderValue) }}</div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="report-metric p-3">
                    <div class="text-muted small text-uppercase fw-semibold">Đơn đã thanh toán</div>
                    <div class="fs-4 fw-bold mt-2">{{ number_format($paidOrdersCount) }}</div>
                    <div class="text-muted small mt-1">Chờ thanh toán: {{ number_format($pendingPaymentCount) }}</div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="report-metric p-3">
                    <div class="text-muted small text-uppercase fw-semibold">Doanh thu hoàn thành</div>
                    <div class="fs-4 fw-bold mt-2">{{ $money($completedRevenue) }}</div>
                </div>
            </div>
        </div>

        <div class="report-panel p-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h2 class="h5 fw-bold mb-1">Doanh thu theo ngày</h2>
                    <div class="text-muted small">{{ $fromDate->format('d/m/Y') }} - {{ $toDate->format('d/m/Y') }}</div>
                </div>
            </div>
            <div class="report-chart">
                <canvas id="dailyRevenueChart"></canvas>
            </div>
        </div>

        <div class="report-panel">
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
                                <td class="text-center py-4 text-muted" colspan="4">Chưa có dữ liệu sản phẩm trong khoảng này.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="report-panel">
            <div class="p-3 border-bottom">
                <h2 class="h5 fw-bold mb-0">Phương thức thanh toán</h2>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Phương thức</th>
                            <th class="text-end">Số đơn</th>
                            <th class="text-end">Doanh thu</th>
                            <th class="text-end">Tỷ trọng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paymentStats as $payment)
                            <tr>
                                <td class="fw-semibold">{{ $paymentLabels[$payment->payment_method] ?? ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                <td class="text-end">{{ number_format($payment->total_orders) }}</td>
                                <td class="text-end fw-semibold">{{ $money($payment->total_revenue) }}</td>
                                <td class="text-end">
                                    {{ $ordersCount > 0 ? number_format(($payment->total_orders / $ordersCount) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center py-4 text-muted" colspan="4">Chưa có dữ liệu thanh toán.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const revenueCanvas = document.getElementById('dailyRevenueChart');

            if (!revenueCanvas) {
                return;
            }

            new Chart(revenueCanvas, {
                type: 'bar',
                data: {
                    labels: @json($dailyLabels),
                    datasets: [{
                        label: 'Doanh thu',
                        data: @json($dailyRevenue),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.65)',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return new Intl.NumberFormat('vi-VN', {
                                        style: 'currency',
                                        currency: 'VND'
                                    }).format(context.parsed.y || 0);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
