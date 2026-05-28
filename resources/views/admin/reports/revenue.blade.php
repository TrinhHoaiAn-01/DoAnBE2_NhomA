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

        .report-chart {
            min-height: 340px;
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
                    <div class="text-muted small mt-1">Chờ thanh toán: {{ number_format($pendingPaymentCount ?? 0) }}</div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="report-card p-3">
                    <div class="text-muted small text-uppercase fw-semibold">Giá trị trung bình</div>
                    <div class="fs-4 fw-bold mt-2">{{ $money($averageOrderValue ?? 0) }}</div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="report-card p-3">
                    <div class="text-muted small text-uppercase fw-semibold">Doanh thu hoàn thành</div>
                    <div class="fs-4 fw-bold mt-2">{{ $money($completedRevenue ?? 0) }}</div>
                </div>
            </div>
        </div>

        <div class="report-panel p-4">
            <form class="row g-3 align-items-end" method="GET" action="{{ route('admin.reports.revenue') }}">
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold" for="from_date">Từ ngày</label>
                    <input class="form-control" type="date" id="from_date" name="from_date" value="{{ $fromDate->format('Y-m-d') }}">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold" for="to_date">Đến ngày</label>
                    <input class="form-control" type="date" id="to_date" name="to_date" value="{{ $toDate->format('Y-m-d') }}">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold" for="group_by">Gom dữ liệu</label>
                    <select class="form-select" id="group_by" name="group_by">
                        <option value="day" @selected($groupBy === 'day')>Theo ngày</option>
                        <option value="month" @selected($groupBy === 'month')>Theo tháng</option>
                        <option value="year" @selected($groupBy === 'year')>Theo năm</option>
                    </select>
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-funnel me-1"></i> Lọc
                    </button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.reports.revenue') }}">Đặt lại</a>
                </div>
            </form>
        </div>

        <div class="report-panel p-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h2 class="h5 fw-bold mb-1">Biểu đồ doanh thu</h2>
                    <div class="text-muted small">{{ $fromDate->format('d/m/Y') }} - {{ $toDate->format('d/m/Y') }}</div>
                </div>
            </div>
            <div class="report-chart">
                <canvas id="revenueReportChart"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('revenueReportChart');

            if (!canvas) {
                return;
            }

            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: @json($revenueLabels),
                    datasets: [{
                        label: 'Doanh thu',
                        data: @json($revenueData),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.68)',
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
