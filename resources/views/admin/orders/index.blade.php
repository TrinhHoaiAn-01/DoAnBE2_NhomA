@extends('layouts.admin', ['title' => 'NeoMart Admin - Đơn hàng'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4">
        <div>
            <p class="text-uppercase text-secondary small fw-semibold mb-2">Quản trị đơn hàng</p>
            <h1 class="h2 fw-bold mb-1">Danh sách đơn hàng</h1>
            <p class="text-secondary mb-0">Theo dõi đơn mới, cập nhật xử lý và trạng thái giao hàng.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="soft-surface rounded-4 p-3">
                <div class="small text-secondary">Chờ xử lý</div>
                <div class="fs-4 fw-bold">{{ $pendingCount }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="soft-surface rounded-4 p-3">
                <div class="small text-secondary">Đang xử lý</div>
                <div class="fs-4 fw-bold">{{ $processingCount }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="soft-surface rounded-4 p-3">
                <div class="small text-secondary">Hoàn tất</div>
                <div class="fs-4 fw-bold">{{ $completedCount }}</div>
            </div>
        </div>
    </div>

    <div class="surface rounded-4 p-3 p-lg-4 mb-4">
        <form class="row g-3 align-items-end">
            <div class="col-lg-7">
                <label class="form-label" for="search">Tìm đơn hàng</label>
                <input class="form-control" id="search" name="search" value="{{ $search }}" placeholder="Mã đơn, tên khách hoặc số điện thoại">
            </div>
            <div class="col-lg-3">
                <label class="form-label" for="status">Trạng thái</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Tất cả</option>
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 d-flex gap-2">
                <button class="btn btn-primary flex-grow-1" type="submit">Lọc</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.orders.index') }}">Xóa</a>
            </div>
        </form>
    </div>

    <div class="surface rounded-4 p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Sản phẩm</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td><code>{{ $order->code }}</code></td>
                            <td>
                                <div class="fw-semibold">{{ $order->customer_name }}</div>
                                <div class="small text-secondary">{{ $order->customer_phone }}</div>
                            </td>
                            <td>{{ $order->items_count }}</td>
                            <td class="fw-semibold">{{ number_format((float) $order->total, 0, ',', '.') }}d</td>
                            <td>{{ $statusOptions[$order->status] ?? $order->status }}</td>
                            <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.orders.show', $order) }}">Chi tiết</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-secondary py-4">Chưa có đơn hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $orders->links() }}</div>
    </div>
@endsection
