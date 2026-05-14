@extends('layouts.admin')

@section('title', 'Tổng quan hệ thống')

@section('content')
    <div class="row g-4 mb-4">
        <!-- Card 1 -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm rounded-3">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3 d-flex align-items-center justify-content-center"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-people fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase">Khách hàng</p>
                        <h3 class="mb-0 fw-bold">{{ number_format($usersCount) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm rounded-3">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-3 p-3 me-3 d-flex align-items-center justify-content-center"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-box-seam fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase">Sản phẩm</p>
                        <h3 class="mb-0 fw-bold">{{ number_format($productsCount) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm rounded-3">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3 d-flex align-items-center justify-content-center"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-exclamation-triangle fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase">Sắp hết hàng</p>
                        <h3 class="mb-0 fw-bold">{{ number_format($lowStockCount) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm rounded-3">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 text-info rounded-3 p-3 me-3 d-flex align-items-center justify-content-center"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-cart fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1 small fw-bold text-uppercase">Đơn chờ xử lý</p>
                        <h3 class="mb-0 fw-bold">{{ number_format($pendingOrdersCount) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ doanh thu -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">Doanh thu 7 ngày gần nhất</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Cảnh báo hệ thống -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">Hoạt động hệ thống gần đây</h5>
                    <a href="{{ route('admin.logs') }}" class="btn btn-sm btn-outline-secondary">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <tbody>
                                @forelse($recentLogs as $log)
                                <tr>
                                    <td class="text-center" style="width: 50px;">
                                        <i class="bi bi-activity text-primary fs-5"></i>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $log->user_name }}</div>
                                        <div class="text-muted small">{{ $log->action }} ({{ $log->target_type }})</div>
                                    </td>
                                    <td class="text-end text-muted small pe-4">{{ $log->created_at->diffForHumans() }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Chưa có hoạt động nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tồn kho thấp -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">Sản phẩm sắp hết</h5>
                    <a href="{{ route('admin.warehouse.inventory') }}" class="btn btn-sm btn-outline-primary">Tới Kho</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($lowStockProducts as $prod)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div>
                                <h6 class="mb-0 text-truncate" style="max-width: 200px;">{{ $prod->name }}</h6>
                                <small class="text-muted">SKU: {{ $prod->sku }}</small>
                            </div>
                            <span class="badge {{ $prod->stock == 0 ? 'bg-danger' : 'bg-warning text-dark' }} rounded-pill">Chỉ còn {{ $prod->stock }}</span>
                        </li>
                        @empty
                        <li class="list-group-item text-center py-4 text-muted">
                            Mọi sản phẩm đều còn đủ hàng.
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dates) !!},
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: {!! json_encode($revenues) !!},
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#0d6efd',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
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