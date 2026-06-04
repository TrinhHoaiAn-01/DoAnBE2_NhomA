@extends('layouts.admin', ['title' => 'Lịch sử kho: ' . $product->name])

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Lịch sử kho: {{ $product->name }}</h1>
            <p class="text-secondary mb-0">SKU: {{ $product->sku }} | Tồn hiện tại: <strong>{{ $product->stock }}</strong></p>
        </div>
        <a href="{{ route('admin.warehouse.inventory') }}" class="btn btn-outline-secondary">Quay lại</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Thời gian</th>
                            <th>Loại</th>
                            <th>Số lượng</th>
                            <th>Tham chiếu</th>
                            <th>Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($product->stockHistories as $history)
                        <tr>
                            <td>{{ $history->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>
                                @if($history->type == 'in')
                                    <span class="badge bg-success">Nhập (+)</span>
                                @else
                                    <span class="badge bg-danger">Xuất (-)</span>
                                @endif
                            </td>
                            <td class="fw-bold {{ $history->type == 'in' ? 'text-success' : 'text-danger' }}">
                                {{ $history->type == 'in' ? '+' : '-' }}{{ $history->quantity }}
                            </td>
                            <td>
                                @if($history->reference_type == 'receipt')
                                    <span class="badge bg-info text-dark">Phiếu Nhập: {{ $history->reference_code }}</span>
                                @elseif($history->reference_type == 'issue')
                                    <span class="badge bg-warning text-dark">Phiếu Xuất: {{ $history->reference_code }}</span>
                                @elseif($history->reference_type == 'check')
                                    <span class="badge bg-secondary">Kiểm Kê: {{ $history->reference_code }}</span>
                                @else
                                    {{ $history->reference_code }}
                                @endif
                            </td>
                            <td>{{ $history->note }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-secondary">Chưa có lịch sử biến động nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
