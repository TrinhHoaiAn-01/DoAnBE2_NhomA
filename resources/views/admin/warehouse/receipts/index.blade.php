@extends('layouts.admin', ['title' => 'NeoMart Admin - Quản lý nhập xuất kho'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4">
        <div>
            <p class="text-uppercase text-secondary small fw-semibold mb-2">Kho vận</p>
            <h1 class="h2 fw-bold mb-1">Phiếu nhập / xuất kho</h1>
            <p class="text-secondary mb-0">Quản lý lịch sử nhập hàng từ nhà cung cấp.</p>
        </div>
        <div>
            <a href="{{ route('admin.warehouse.receipts.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Tạo phiếu nhập kho
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="surface p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mã Phiếu</th>
                        <th>Nhà cung cấp</th>
                        <th>Người lập</th>
                        <th>Tổng tiền</th>
                        <th>Ngày lập</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($receipts as $receipt)
                        <tr>
                            <td><span class="badge bg-light text-dark border px-2 py-1">{{ $receipt->code }}</span></td>
                            <td class="fw-medium">{{ $receipt->supplier->name ?? 'N/A' }}</td>
                            <td>{{ $receipt->user->name ?? 'N/A' }}</td>
                            <td class="fw-bold text-danger">{{ number_format($receipt->total_amount, 0, ',', '.') }}đ</td>
                            <td>{{ $receipt->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.warehouse.receipts.show', $receipt->id) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Chưa có phiếu nhập kho nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $receipts->links() }}</div>
    </div>
@endsection
