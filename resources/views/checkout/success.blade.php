@extends('layouts.app', ['title' => 'NeoMart - Dat hang thanh cong'])

@section('content')
    <div class="surface rounded-3 p-4 p-lg-5">
        <div class="row g-4 align-items-start">
            <div class="col-lg-7">
                <p class="text-uppercase text-success small fw-semibold mb-2">Dat hang thanh cong</p>
                <h1 class="h2 fw-bold mb-3">Ma don {{ $order->code }}</h1>
                <p class="text-secondary mb-4">NeoMart da ghi nhan don hang va se xu ly theo trang thai demo.</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="soft-surface rounded-3 p-3">
                            <div class="small text-secondary">Nguoi nhan</div>
                            <div class="fw-semibold">{{ $order->customer_name }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="soft-surface rounded-3 p-3">
                            <div class="small text-secondary">Trang thai</div>
                            <div class="fw-semibold">{{ $order->status }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="soft-surface rounded-3 p-3">
                    <h2 class="h5 fw-bold mb-3">Tong ket</h2>
                    @foreach ($order->items as $item)
                        <div class="d-flex justify-content-between gap-3 mb-2">
                            <span>{{ $item->product_name }} x {{ $item->quantity }}</span>
                            <strong>{{ number_format((float) $item->subtotal, 0, ',', '.') }}d</strong>
                        </div>
                    @endforeach
                    <div class="border-top pt-3 mt-3 d-flex justify-content-between fs-5">
                        <span>Tong cong</span>
                        <strong>{{ number_format((float) $order->total, 0, ',', '.') }}d</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
