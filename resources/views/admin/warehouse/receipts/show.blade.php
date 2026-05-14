@extends('layouts.admin', ['title' => 'Chi Tiết Phiếu Nhập Kho'])

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.warehouse.receipts') }}" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i> Trở về</a>
        <div>
            <h1 class="h3 fw-bold mb-0">Phiếu Nhập Kho #{{ $receipt->code }}</h1>
            <p class="text-muted mb-0 small">Ngày lập: {{ $receipt->created_at->format('d/m/Y H:i:s') }}</p>
        </div>
        <div class="ms-auto d-flex gap-2">
            <button onclick="window.print()" class="btn btn-primary shadow-sm"><i class="bi bi-printer me-1"></i> In phiếu</button>
            <span class="badge bg-success px-3 py-2 fs-6 d-flex align-items-center"><i class="bi bi-check-circle me-1"></i> Hoàn tất</span>
        </div>
    </div>

    <style>
        @media print {
            body { background: #fff !important; }
            #sidebar, .top-navbar, .btn, .badge { display: none !important; }
            #content { width: 100% !important; margin: 0 !important; padding: 0 !important; }
            .surface { box-shadow: none !important; border: 1px solid #ddd !important; }
            .col-xl-4, .col-xl-8 { width: 100% !important; }
        }
    </style>

    <div class="row g-4">
        <div class="col-xl-4">
            <div class="surface p-4 h-100">
                <h5 class="fw-bold mb-4">Thông tin chung</h5>
                
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted">Nhà cung cấp:</td>
                            <td class="fw-medium text-end">{{ $receipt->supplier->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Người lập phiếu:</td>
                            <td class="fw-medium text-end">{{ $receipt->user->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tổng thành tiền:</td>
                            <td class="fw-bold text-danger text-end fs-5">{{ number_format($receipt->total_amount, 0, ',', '.') }}đ</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-muted pb-0">Ghi chú:</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="pt-1">
                                <div class="bg-light p-3 rounded text-dark small">
                                    {{ $receipt->note ?: 'Không có ghi chú.' }}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="surface p-4 h-100">
                <h5 class="fw-bold mb-4">Sản phẩm đã nhập ({{ $receipt->items->count() }})</h5>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Sản phẩm</th>
                                <th class="text-end">Giá nhập</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($receipt->items as $index => $item)
                                <tr>
                                    <td class="text-muted">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $item->product->name ?? 'N/A' }}</div>
                                        <div class="small text-muted">{{ $item->product->sku ?? '' }}</div>
                                    </td>
                                    <td class="text-end">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                    <td class="text-center"><span class="badge bg-secondary">{{ $item->quantity }}</span></td>
                                    <td class="text-end fw-medium">{{ number_format($item->subtotal, 0, ',', '.') }}đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="4" class="text-end">Tổng cộng:</th>
                                <th class="text-end text-danger fs-5">{{ number_format($receipt->total_amount, 0, ',', '.') }}đ</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
