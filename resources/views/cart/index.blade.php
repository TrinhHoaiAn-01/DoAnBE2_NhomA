@extends('layouts.app', ['title' => 'NeoMart - Gio hang'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="text-uppercase text-primary small fw-semibold mb-2">Mua hang</p>
            <h1 class="h2 fw-bold mb-1">Gio hang</h1>
            <p class="text-secondary mb-0">Kiem tra san pham va so luong truoc khi dat hang.</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('products.index') }}">Tiep tuc mua hang</a>
    </div>

    <div class="surface rounded-3 p-3 p-lg-4">
        @if (count($items) > 0)
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>San pham</th>
                            <th>Don gia</th>
                            <th>So luong</th>
                            <th>Tam tinh</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img class="rounded border" src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}" width="64" height="64" style="object-fit: cover">
                                        <div>
                                            <div class="fw-semibold">{{ $item['product']->name }}</div>
                                            <div class="small text-secondary">{{ $item['product']->category?->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format((float) $item['product']->price, 0, ',', '.') }}d</td>
                                <td style="width: 160px">
                                    <form class="d-flex gap-2" method="post" action="{{ route('cart.update', $item['product']) }}">
                                        @csrf
                                        @method('patch')
                                        <input class="form-control form-control-sm" type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ max($item['product']->stock, 1) }}">
                                        <button class="btn btn-sm btn-outline-primary" type="submit">Luu</button>
                                    </form>
                                </td>
                                <td class="fw-semibold">{{ number_format($item['subtotal'], 0, ',', '.') }}d</td>
                                <td class="text-end">
                                    <form method="post" action="{{ route('cart.remove', $item['product']) }}">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Xoa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                <div class="soft-surface rounded-3 p-3" style="min-width: 280px">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tam tinh</span>
                        <strong>{{ number_format($total, 0, ',', '.') }}d</strong>
                    </div>
                    <a class="btn btn-primary w-100" href="#">Dat hang</a>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div class="h5 fw-bold">Gio hang dang trong</div>
                <p class="text-secondary">Chon san pham tren cua hang de bat dau mua hang.</p>
                <a class="btn btn-primary" href="{{ route('products.index') }}">Xem san pham</a>
            </div>
        @endif
    </div>
@endsection
