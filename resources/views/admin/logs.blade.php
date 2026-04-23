@extends('layouts.admin')

@section('title', 'Phân tích Nhật ký & Báo cáo AI')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
    <h4 class="fw-bold mb-0">Hệ thống Nhật ký thông minh</h4>
    <div class="d-flex gap-2">
        <!-- Bộ lọc sắp xếp -->
        <form action="{{ route('admin.logs') }}" method="GET" class="d-flex gap-2">
            <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Mới nhất trước</option>
                <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Cũ nhất trước</option>
            </select>
        </form>
        
        <!-- Nút Xuất báo cáo -->
        <button onclick="window.print()" class="btn btn-primary btn-sm px-3">
            <i class="bi bi-printer me-1"></i> Xuất báo cáo PDF
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="card-header bg-white py-3 d-print-block">
        <h5 class="mb-0 fw-bold"><i class="bi bi-robot text-primary me-2"></i>Báo cáo Hoạt động & Phân tích Rủi ro AI</h5>
        <small class="text-muted d-print-block">Xuất ngày: {{ now()->format('d/m/Y H:i') }} | Người xuất: {{ Auth::user()->name }}</small>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Thời gian</th>
                        <th>Người thao tác</th>
                        <th>Hành động</th>
                        <th>AI Phân tích</th>
                        <th class="text-end pe-4 d-print-none">Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr class="{{ $log->ai_color == 'danger' ? 'table-danger-subtle' : '' }}">
                        <td class="ps-4 text-muted small">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="fw-bold">
                            <i class="bi bi-person-badge text-secondary me-1"></i> {{ $log->user_name }}
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border rounded-pill px-3">{{ $log->action }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $log->ai_color }} rounded-pill px-3">
                                <i class="bi bi-shield-{{ $log->ai_color == 'success' ? 'check' : ($log->ai_color == 'warning' ? 'exclamation' : 'slash-circle') }} me-1"></i>
                                AI: {{ $log->ai_risk }}
                            </span>
                        </td>
                        <td class="text-end pe-4 d-print-none">
                            <button class="btn btn-sm btn-link text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#logDetail{{ $log->id }}">
                                <i class="bi bi-chevron-down"></i> Chi tiết
                            </button>
                        </td>
                    </tr>
                    <tr class="collapse d-print-none" id="logDetail{{ $log->id }}">
                        <td colspan="5" class="bg-light p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-danger small text-uppercase">Snapshot Trước</h6>
                                    <pre class="bg-white p-3 rounded border small text-wrap shadow-sm"><code>{{ json_encode($log->old_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-success small text-uppercase">Snapshot Sau</h6>
                                    <pre class="bg-white p-3 rounded border small text-wrap shadow-sm"><code>{{ json_encode($log->new_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Chưa có nhật ký hoạt động nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white py-3 d-print-none">
        {{ $logs->appends(['sort' => $sort])->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
    @media print {
        #sidebar, .top-navbar, .d-print-none {
            display: none !important;
        }
        #content {
            margin-left: 0 !important;
            width: 100% !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .table-responsive {
            overflow: visible !important;
        }
        body {
            background-color: white !important;
        }
    }
    .table-danger-subtle {
        background-color: rgba(220, 53, 69, 0.05);
    }
</style>
@endsection
