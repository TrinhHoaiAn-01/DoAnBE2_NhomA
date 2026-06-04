@extends('layouts.admin', ['title' => 'Chi tiết phiếu kiểm kê'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Phiếu kiểm kê: {{ $check->code }}</h1>
            <p class="text-secondary mb-0">Ngày tạo: {{ $check->created_at->format('d/m/Y H:i') }} | Người tạo: {{ $check->user->name }}</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('admin.warehouse.checks') }}">Quay lại</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Thông tin chung</h5>
            <p class="mb-0"><strong>Ghi chú:</strong> {{ $check->note ?: 'Không có ghi chú' }}</p>
            <p class="mb-0"><strong>Trạng thái:</strong> Đã hoàn tất và cân bằng hệ thống.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="card-title mb-0 fw-bold">Chi tiết sản phẩm có chênh lệch</h5>
        </div>
        <div class="card-body p-0">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Sản phẩm</th>
                        <th class="text-center">Tồn cũ hệ thống</th>
                        <th class="text-center">Tồn thực tế</th>
                        <th class="text-center">Chênh lệch</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($check->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</td>
                        <td class="text-center text-muted">{{ $item->old_stock }}</td>
                        <td class="text-center fw-bold">{{ $item->actual_stock }}</td>
                        <td class="text-center">
                            @if($item->difference > 0)
                                <span class="badge bg-success">+{{ $item->difference }}</span>
                            @else
                                <span class="badge bg-danger">{{ $item->difference }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Phiếu này không ghi nhận sản phẩm nào bị chênh lệch. (Kho khớp 100%)</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
