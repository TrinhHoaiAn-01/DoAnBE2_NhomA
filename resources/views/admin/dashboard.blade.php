@extends('layouts.admin')

@section('title', 'Tổng quan quản trị')

@section('content')
    @php
        $money = fn ($value) => number_format((float) $value, 0, ',', '.') . 'đ';
        $statusColors = [
            'pending' => 'warning',
            'processing' => 'primary',
            'shipping' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
        ];
    @endphp

    <style>
        .dashboard-shell {
            display: grid;
            gap: 24px;
        }

        .metric-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #ffffff;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
            min-height: 132px;
        }

        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .dashboard-panel {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #ffffff;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        }

        .chart-box {
            min-height: 300px;
        }

        .status-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px;
            align-items: center;
        }
    </style>

    <div class="dashboard-shell">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
            <div>
                <h1 class="h3 fw-bold mb-1">Dashboard quản trị tổng quan</h1>
                <p class="text-muted mb-0">Theo dõi nhanh doanh thu, đơn hàng, tồn kho và hoạt động hệ thống.</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                <i class="bi bi-receipt me-1"></i> Quản lý đơn hàng
            </a>
        </div>

        <div class="row g-3">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="metric-card p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <span class="metric-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-cash-coin fs-4"></i>
                        </span>
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold">Doanh thu hôm nay</div>
                            <div class="fs-4 fw-bold">{{ $money($todayRevenue) }}</div>
                        </div>
                    </div>
                    <div class="text-muted small mt-3">Tháng này: {{ $money($monthRevenue) }}</div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="metric-card p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <span class="metric-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-cart-check fs-4"></i>
                        </span>
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold">Tổng đơn hàng</div>
                            <div class="fs-4 fw-bold">{{ number_format($ordersCount) }}</div>
                        </div>
                    </div>
                    <div class="text-muted small mt-3">Hoàn thành: {{ number_format($completedOrdersCount) }}</div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="metric-card p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <span class="metric-icon bg-info bg-opacity-10 text-info">
                            <i class="bi bi-box-seam fs-4"></i>
                        </span>
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold">Sản phẩm</div>
                            <div class="fs-4 fw-bold">{{ number_format($productsCount) }}</div>
                        </div>
                    </div>
                    <div class="text-muted small mt-3">Giá trị kho: {{ $money($stockValue) }}</div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="metric-card p-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <span class="metric-icon bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-exclamation-triangle fs-4"></i>
                        </span>
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold">Cảnh báo kho</div>
                            <div class="fs-4 fw-bold">{{ number_format($lowStockCount) }}</div>
                        </div>
                    </div>
                    <div class="text-muted small mt-3">Hết hàng: {{ number_format($outOfStockCount) }}</div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-xl-8">
                <div class="dashboard-panel p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
                        <div>
                            <h2 class="h5 fw-bold mb-1">Doanh thu 7 ngày gần nhất</h2>
                            <div class="text-muted small">Không tính các đơn hàng đã hủy.</div>
                        </div>
                    </div>
                    <div class="chart-box">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="dashboard-panel p-3 h-100">
                    <h2 class="h5 fw-bold mb-3">Trạng thái đơn hàng</h2>
                    <div class="d-grid gap-3">
                        @foreach($orderStatusStats as $item)
                            @php($color = $statusColors[$item['status']] ?? 'secondary')
                            <div class="status-row">
                                <div>
                                    <div class="fw-semibold">{{ $item['label'] }}</div>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-{{ $color }}" style="width: {{ $ordersCount > 0 ? min(100, ($item['total'] / $ordersCount) * 100) : 0 }}%"></div>
                                    </div>
                                </div>
                                <span class="badge text-bg-{{ $color }}">{{ number_format($item['total']) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-xl-7">
                <div class="dashboard-panel h-100">
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h2 class="h5 fw-bold mb-0">Hoạt động hệ thống gần đây</h2>
                        <a href="{{ route('admin.logs') }}" class="btn btn-sm btn-outline-secondary">Xem tất cả</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <tbody>
                                @forelse($recentLogs as $log)
                                    <tr>
                                        <td class="text-center" style="width: 56px;">
                                            <i class="bi bi-activity text-primary fs-5"></i>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $log->user_name }}</div>
                                            <div class="text-muted small">{{ $log->action }} ({{ $log->target_type }})</div>
                                        </td>
                                        <td class="text-end text-muted small pe-3">{{ $log->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center py-4 text-muted" colspan="3">Chưa có hoạt động nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-5">
                <div class="dashboard-panel h-100">
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h2 class="h5 fw-bold mb-0">Sản phẩm sắp hết hàng</h2>
                        <a href="{{ route('admin.warehouse.inventory') }}" class="btn btn-sm btn-outline-primary">Tới kho</a>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse($lowStockProducts as $product)
                            <div class="list-group-item d-flex justify-content-between align-items-center gap-3 py-3">
                                <div class="text-truncate">
                                    <div class="fw-semibold text-truncate">{{ $product->name }}</div>
                                    <div class="text-muted small">SKU: {{ $product->sku }}</div>
                                </div>
                                <span class="badge {{ $product->stock == 0 ? 'text-bg-danger' : 'text-bg-warning' }}">
                                    Còn {{ number_format($product->stock) }}
                                </span>
                            </div>
                        @empty
                            <div class="list-group-item text-center py-4 text-muted">Mọi sản phẩm đều còn đủ hàng.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const revenueCanvas = document.getElementById('revenueChart');

            if (!revenueCanvas) {
                return;
            }

            new Chart(revenueCanvas, {
                type: 'line',
                data: {
                    labels: @json($dates),
                    datasets: [{
                        label: 'Doanh thu',
                        data: @json($revenues),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.12)',
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#2563eb',
                        fill: true,
                        tension: 0.35
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
                                    const value = context.parsed.y || 0;
                                    return new Intl.NumberFormat('vi-VN', {
                                        style: 'currency',
                                        currency: 'VND'
                                    }).format(value);
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
