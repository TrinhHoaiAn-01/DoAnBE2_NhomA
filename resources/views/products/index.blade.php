@extends('layouts.app', ['title' => 'NeoMart - San pham'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="text-uppercase text-primary small fw-semibold mb-2">Cua hang</p>
            <h1 class="h2 fw-bold mb-1">Danh sach san pham</h1>
            <p class="text-secondary mb-0">Chon san pham de them vao gio hang hoac mua nhanh.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('cart.index') }}">
            <i class="fa-solid fa-cart-shopping me-1"></i> Xem gio hang
        </a>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">
        @forelse ($products as $product)
            <div class="col">
                <div class="surface rounded-3 h-100 overflow-hidden">
                    <img class="product-thumb" src="{{ $product->image_url }}" alt="{{ $product->name }}">
                    <div class="p-3">
                        <div class="small text-secondary mb-1">{{ $product->category?->name }}</div>
                        <h2 class="h6 fw-bold mb-2">{{ $product->name }}</h2>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="fw-bold text-primary">{{ number_format((float) $product->price, 0, ',', '.') }}d</div>
                                @if ($product->original_price)
                                    <div class="small text-secondary text-decoration-line-through">{{ number_format((float) $product->original_price, 0, ',', '.') }}d</div>
                                @endif
                            </div>
                            <span class="badge {{ $product->stock > 0 ? 'text-bg-success' : 'text-bg-secondary' }}">
                                {{ $product->stock > 0 ? 'Con hang' : 'Het hang' }}
                            </span>
                        </div>
                        <div class="d-flex gap-2">
                            <a class="btn btn-outline-secondary btn-sm flex-grow-1" href="{{ route('products.show', $product) }}">Chi tiet</a>
                            <form method="post" action="{{ route('cart.add', $product) }}">
                                @csrf
                                <button class="btn btn-primary btn-sm" type="submit" @disabled($product->stock <= 0)>
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="surface rounded-3 p-4 text-center text-secondary">Chua co san pham dang ban.</div>
            </div>
        @endforelse
    </div>
@endsection
