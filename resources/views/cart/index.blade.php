@extends('layouts.app', ['title' => 'NeoMart - Gio hang'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-5 p-4 bg-white rounded-4 shadow-sm">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('welcome') }}" class="text-decoration-none">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Giỏ hàng</li>
                </ol>
            </nav>
            <h1 class="h2 fw-black mb-1">Giỏ hàng của bạn</h1>
            <p class="text-muted mb-0">Kiểm tra lại các sản phẩm và số lượng trước khi tiến hành thanh toán.</p>
        </div>
        <a class="btn btn-premium btn-premium-outline px-4 py-2" href="{{ route('product.list') }}">
            <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
        </a>
    </div>

    <div class="bg-white rounded-4 shadow-sm overflow-hidden mb-5">
        @if (count($items) > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 border-0">Sản phẩm</th>
                            <th class="py-3 border-0">Đơn giá</th>
                            <th class="py-3 border-0">Số lượng</th>
                            <th class="py-3 border-0">Tạm tính</th>
                            <th class="pe-4 py-3 border-0"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3 py-2">
                                        <div class="bg-light p-2 rounded-3">
                                            <img src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}" width="80" height="80" style="object-fit: contain">
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $item['product']->name }}</div>
                                            <div class="small text-muted">{{ $item['product']->category?->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-medium">{{ number_format((float) $item['product']->price, 0, ',', '.') }}đ</td>
                                <td style="width: 180px">
                                    <form class="d-flex gap-2" method="post" action="{{ route('cart.update', $item['product']) }}">
                                        @csrf
                                        @method('patch')
                                        <div class="input-group input-group-sm" style="width: 120px">
                                            <input class="form-control text-center fw-bold" type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ max($item['product']->stock, 1) }}">
                                            <button class="btn btn-primary" type="submit" title="Cập nhật"><i class="bi bi-arrow-clockwise"></i></button>
                                        </div>
                                    </form>
                                </td>
                                <td class="fw-bold text-primary">{{ number_format($item['subtotal'], 0, ',', '.') }}đ</td>
                                <td class="pe-4 text-end">
                                    <form method="post" action="{{ route('cart.remove', $item['product']) }}">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-outline-danger btn-sm rounded-pill px-3" type="submit">
                                            <i class="bi bi-trash me-1"></i> Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-light d-flex justify-content-end">
                <div class="bg-white rounded-4 p-4 shadow-sm" style="min-width: 350px">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tạm tính</span>
                        <span class="fw-bold">{{ number_format($total, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 pb-2 border-bottom">
                        <span class="text-muted">Phí vận chuyển</span>
                        <span class="text-success fw-medium">Miễn phí</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="h5 mb-0 fw-black">Tổng cộng</span>
                        <span class="h5 mb-0 fw-black text-primary">{{ number_format($total, 0, ',', '.') }}đ</span>
                    </div>
                    <a class="btn btn-premium btn-premium-primary w-100 py-3" href="{{ route('checkout.index') }}">
                        Tiến hành đặt hàng <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div class="bg-light d-inline-flex p-4 rounded-circle mb-4">
                    <i class="bi bi-cart-x fs-1 text-muted"></i>
                </div>
                <h4 class="fw-bold text-dark">Giỏ hàng của bạn đang trống</h4>
                <p class="text-muted mb-4">Hãy quay lại cửa hàng để chọn cho mình những sản phẩm ưng ý nhất.</p>
                <a class="btn btn-premium btn-premium-primary px-5" href="{{ route('product.list') }}">
                    Xem sản phẩm ngay
                </a>
            </div>
        @endif
    </div>
@endsection
