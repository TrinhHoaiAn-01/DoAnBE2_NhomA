@extends('layouts.app', ['title' => 'NeoMart - Lịch sử đơn hàng'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="text-uppercase text-primary small fw-semibold mb-2">Tài khoản</p>
            <h1 class="h2 fw-bold mb-1">Lịch sử đơn hàng</h1>
            <p class="text-secondary mb-0">Theo dõi các đơn đã đặt, trạng thái xử lý và thông tin giao hàng.</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('products.index') }}">
            <i class="bi bi-grid me-1"></i>Tiếp tục mua hàng
        </a>
    </div>

    <div class="surface rounded-3 p-3 p-lg-4 mb-4">
        <form class="row g-3 align-items-end" method="get" action="{{ route('orders.index') }}">
            <div class="col-md-5 col-lg-4">
                <label class="form-label" for="status">Lọc theo trạng thái</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Tất cả đơn hàng</option>
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-auto">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-funnel me-1"></i>Lọc đơn
                </button>
                <a class="btn btn-outline-secondary" href="{{ route('orders.index') }}">Xóa lọc</a>
            </div>
        </form>
    </div>

    <div class="surface rounded-3 p-0 overflow-hidden">
        @if ($orders->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Sản phẩm</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>
                                    <code>{{ $order->code }}</code>
                                    <div class="small text-secondary">{{ $order->payment_method === 'cod' ? 'COD' : 'Thanh toán demo' }}</div>
                                </td>
                                <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                                <td>{{ $order->items_count }} sản phẩm</td>
                                <td class="fw-semibold">{{ number_format((float) $order->total, 0, ',', '.') }}đ</td>
                                <td>
                                    <span class="badge text-bg-light border">{{ $statusOptions[$order->status] ?? $order->status }}</span>
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('orders.show', $order) }}">
                                        Theo dõi
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5 px-3">
                <div class="display-6 text-primary mb-3"><i class="bi bi-receipt"></i></div>
                <h2 class="h5 fw-bold">Chưa có đơn hàng</h2>
                <p class="text-secondary mb-4">Các đơn hàng đã đặt sẽ xuất hiện tại đây để bạn tiện theo dõi.</p>
                <a class="btn btn-primary" href="{{ route('products.index') }}">Xem sản phẩm</a>
            </div>
        @endif
    </div>

    @if ($orders->hasPages())
        <div class="mt-3">{{ $orders->links() }}</div>
    @endif
@endsection
