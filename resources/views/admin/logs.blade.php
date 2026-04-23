@extends('layouts.admin')

@section('title', 'Nhật ký Hoạt động Hệ thống')

@section('content')
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Lịch sử Thao tác (System Logs)</h5>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Thời gian</th>
                        <th>Người thao tác</th>
                        <th>Hành động</th>
                        <th>Đối tượng</th>
                        <th class="text-end pe-4">Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="ps-4 text-muted small">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="fw-bold text-primary">
                            <i class="bi bi-person-badge me-1"></i> {{ $log->user_name }}
                        </td>
                        <td>
                            <span class="badge bg-info text-dark rounded-pill">{{ $log->action }}</span>
                        </td>
                        <td class="text-muted">{{ $log->target_type }}</td>
                        <td class="text-end pe-4">
                            <!-- Nút mở chi tiết -->
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#logDetail{{ $log->id }}">
                                <i class="bi bi-eye"></i> Xem
                            </button>
                        </td>
                    </tr>
                    <!-- Hàng chứa chi tiết Dữ liệu cũ - mới (ẩn mặc định) -->
                    <tr class="collapse" id="logDetail{{ $log->id }}">
                        <td colspan="5" class="bg-light p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-danger"><i class="bi bi-clock-history"></i> Dữ liệu cũ</h6>
                                    <pre class="bg-white p-3 rounded border small text-wrap"><code>{{ json_encode($log->old_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-success"><i class="bi bi-check2-circle"></i> Dữ liệu mới</h6>
                                    <pre class="bg-white p-3 rounded border small text-wrap"><code>{{ json_encode($log->new_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
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
    <div class="card-footer bg-white py-3">
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
