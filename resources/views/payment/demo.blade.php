@extends('layouts.app', ['title' => 'NeoMart - Thanh toan demo'])

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="surface rounded-3 p-4 p-lg-5">
                <p class="text-uppercase text-primary small fw-semibold mb-2">Thanh toan demo</p>
                <h1 class="h2 fw-bold mb-3">Don hang {{ $order->code }}</h1>
                <p class="text-secondary mb-4">
                    Day la man hinh mo phong thanh toan cho phuong thuc {{ $methodLabels[$order->payment_method] ?? $order->payment_method }}.
                </p>

                <div class="soft-surface rounded-3 p-3 mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tong thanh toan</span>
                        <strong class="fs-5">{{ number_format((float) $order->total, 0, ',', '.') }}d</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Trang thai hien tai</span>
                        <span>{{ $order->payment_status }}</span>
                    </div>
                </div>

                @if (! empty($paymentGuides[$order->payment_method]))
                    <div class="alert alert-info mb-4">
                        <div class="fw-semibold mb-2">Hướng dẫn thanh toán</div>
                        <ul class="mb-0">
                            @foreach ($paymentGuides[$order->payment_method] as $guide)
                                <li>{{ $guide }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="vstack gap-2 mb-4">
                    @foreach ($order->items as $item)
                        <div class="d-flex justify-content-between gap-3">
                            <span>{{ $item->product_name }} x {{ $item->quantity }}</span>
                            <strong>{{ number_format((float) $item->subtotal, 0, ',', '.') }}d</strong>
                        </div>
                    @endforeach
                </div>

                <form method="post" action="{{ route('payment.confirm', $order) }}">
                    @csrf
                    <button class="btn btn-primary w-100" type="submit">Xac nhan thanh toan demo</button>
                </form>
            </div>
        </div>
    </div>
@endsection
