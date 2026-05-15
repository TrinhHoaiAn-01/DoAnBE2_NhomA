@extends('layouts.admin', ['title' => 'Kiểm kê kho'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4">
        <div>
            <p class="text-uppercase text-secondary small fw-semibold mb-2">Kho vận</p>
            <h1 class="h2 fw-bold mb-1">Kiểm kê kho</h1>
            <p class="text-secondary mb-0">Lịch sử các lần kiểm kê, đối soát thực tế.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.warehouse.checks.create') }}">
            <i class="bi bi-ui-checks me-1"></i> Tạo phiếu kiểm kê mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="surface rounded-4 p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mã phiếu</th>
                        <th>Người kiểm kê</th>
                        <th>Ghi chú</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($checks as $check)
                    <tr>
                        <td class="fw-bold text-primary">{{ $check->code }}</td>
                        <td>{{ $check->user->name ?? 'N/A' }}</td>
                        <td>{{ $check->note }}</td>
                        <td>{{ $check->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.warehouse.checks.show', $check->id) }}" class="btn btn-sm btn-outline-info">Chi tiết</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Chưa có đợt kiểm kê nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $checks->links() }}</div>
    </div>
@endsection
