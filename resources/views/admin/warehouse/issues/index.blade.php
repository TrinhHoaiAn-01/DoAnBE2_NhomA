@extends('layouts.admin', ['title' => 'NeoMart Admin - Phiếu xuất kho'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4">
        <div>
            <p class="text-uppercase text-secondary small fw-semibold mb-2">Kho vận</p>
            <h1 class="h2 fw-bold mb-1">Phiếu xuất kho</h1>
            <p class="text-secondary mb-0">Quản lý lịch sử xuất hàng ra khỏi kho.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.warehouse.issues.create') }}">
            <i class="bi bi-plus-lg me-1"></i> Tạo phiếu xuất
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
                        <th>Lý do xuất</th>
                        <th>Người tạo</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($issues as $issue)
                    <tr>
                        <td class="fw-bold text-primary">{{ $issue->code }}</td>
                        <td>{{ $issue->reason }}</td>
                        <td>{{ $issue->user->name ?? 'N/A' }}</td>
                        <td>{{ $issue->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.warehouse.issues.show', $issue->id) }}" class="btn btn-sm btn-outline-info">Chi tiết / In</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Chưa có phiếu xuất kho nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $issues->links() }}</div>
    </div>
@endsection
