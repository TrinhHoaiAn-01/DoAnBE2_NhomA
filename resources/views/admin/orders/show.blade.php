@extends('layouts.admin', ['title' => 'NeoMart Admin - Chi tiet don hang'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4">
        <div>
            <p class="text-uppercase text-secondary small fw-semibold mb-2">Chi tiet don hang</p>
            <h1 class="h2 fw-bold mb-1">{{ $order->code }}</h1>
            <p class="text-secondary mb-0">Tao luc {{ $order->created_at?->format('d/m/Y H:i') }}</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('admin.orders.index') }}">Quay lai</a>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="surface rounded-4 p-4">
                <h2 class="h5 fw-bold mb-3">San pham</h2>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>San pham</th>
                                <th>SKU</th>
                                <th>Don gia</th>
                                <th>SL</th>
                                <th>Tam tinh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td><code>{{ $item->sku }}</code></td>
                                    <td>{{ number_format((float) $item->price, 0, ',', '.') }}d</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="fw-semibold">{{ number_format((float) $item->subtotal, 0, ',', '.') }}d</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="border-top mt-3 pt-3 ms-auto" style="max-width: 320px">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tam tinh</span>
                        <strong>{{ number_format((float) $order->subtotal, 0, ',', '.') }}d</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phi giao hang</span>
                        <strong>{{ number_format((float) $order->shipping_fee, 0, ',', '.') }}d</strong>
                    </div>
                    <div class="d-flex justify-content-between fs-5">
                        <span>Tong cong</span>
                        <strong>{{ number_format((float) $order->total, 0, ',', '.') }}d</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="surface rounded-4 p-4 mb-4">
                <h2 class="h5 fw-bold mb-3">Khach hang</h2>
                <div class="vstack gap-2 text-secondary">
                    <div><strong class="text-dark">{{ $order->customer_name }}</strong></div>
                    <div>{{ $order->customer_phone }}</div>
                    <div>{{ $order->customer_email ?: 'Chua co email' }}</div>
                    <div>{{ $order->shipping_address }}</div>
                    <div>Van chuyen: {{ $shippingDistrictLabel }} - {{ $shippingServiceLabel }}</div>
                    @if ($order->note)
                        <div>Ghi chu: {{ $order->note }}</div>
                    @endif
                </div>
            </div>

            <div class="surface rounded-4 p-4">
                <h2 class="h5 fw-bold mb-3">Cap nhat trang thai</h2>
                <form method="post" action="{{ route('admin.orders.update', $order) }}">
                    @csrf
                    @method('patch')
                    <div class="mb-3">
                        <label class="form-label" for="status">Trang thai</label>
                        <select class="form-select" id="status" name="status">
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Luu trang thai</button>
                </form>
            </div>
        </div>
    </div>
@endsection
