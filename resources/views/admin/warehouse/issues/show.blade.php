@extends('layouts.admin', ['title' => 'Chi tiết Phiếu Xuất'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4 d-print-none">
        <div>
            <h1 class="h2 fw-bold mb-1">Phiếu xuất kho: {{ $issue->code }}</h1>
            <p class="text-secondary mb-0">Ngày tạo: {{ $issue->created_at->format('d/m/Y H:i') }} | Người tạo: {{ $issue->user->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer"></i> In Phiếu</button>
            <a class="btn btn-secondary" href="{{ route('admin.warehouse.issues') }}">Quay lại</a>
        </div>
    </div>

    <!-- Printable Area -->
    <div class="card border-0 shadow-sm rounded-0 p-4 p-md-5 print-area">
        <div class="text-center mb-5">
            <h2 class="fw-bold">PHIẾU XUẤT KHO</h2>
            <p class="mb-0">Mã phiếu: <strong>{{ $issue->code }}</strong></p>
            <p class="mb-0">Ngày lập: {{ $issue->created_at->format('d/m/Y') }}</p>
        </div>

        <div class="row mb-4">
            <div class="col-sm-6">
                <strong>Lý do xuất:</strong> {{ $issue->reason }}<br>
                <strong>Ghi chú:</strong> {{ $issue->note ?? 'Không có' }}
            </div>
            <div class="col-sm-6 text-sm-end">
                <strong>Người lập phiếu:</strong> {{ $issue->user->name }}<br>
                <strong>Trạng thái:</strong> Đã hoàn tất
            </div>
        </div>

        <table class="table table-bordered mb-5">
            <thead class="table-light">
                <tr>
                    <th class="text-center" width="50">STT</th>
                    <th>Tên sản phẩm</th>
                    <th class="text-center">Số lượng</th>
                </tr>
            </thead>
            <tbody>
                @foreach($issue->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</td>
                    <td class="text-center fw-bold">{{ $item->quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row mt-5 pt-4 text-center">
            <div class="col-4">
                <strong>Người nhận hàng</strong>
                <p class="small text-muted mt-1">(Ký, ghi rõ họ tên)</p>
                <div style="height: 100px;"></div>
            </div>
            <div class="col-4">
                <strong>Thủ kho</strong>
                <p class="small text-muted mt-1">(Ký, ghi rõ họ tên)</p>
                <div style="height: 100px;"></div>
            </div>
            <div class="col-4">
                <strong>Giám đốc</strong>
                <p class="small text-muted mt-1">(Ký, ghi rõ họ tên)</p>
                <div style="height: 100px;"></div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .print-area, .print-area * {
            visibility: visible;
        }
        .print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush
