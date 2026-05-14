@extends('layouts.app', ['title' => 'NeoMart - '.$product->name])

@section('content')
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="surface rounded-3 overflow-hidden">
                <img class="w-100" src="{{ $product->image_url }}" alt="{{ $product->name }}" style="aspect-ratio: 4 / 3; object-fit: cover">
            </div>
        </div>
        <div class="col-lg-6">
            <div class="surface rounded-3 p-4 h-100">
                <div class="small text-secondary mb-2">{{ $product->category?->name }}{{ $product->brand ? ' - '.$product->brand : '' }}</div>
                <h1 class="h2 fw-bold mb-3">{{ $product->name }}</h1>
                <div class="d-flex align-items-end gap-3 mb-3">
                    <div class="display-6 fw-bold text-primary">{{ number_format((float) $product->price, 0, ',', '.') }}d</div>
                    @if ($product->original_price)
                        <div class="h5 text-secondary text-decoration-line-through mb-2">{{ number_format((float) $product->original_price, 0, ',', '.') }}d</div>
                    @endif
                </div>
                <p class="text-secondary">{{ $product->description ?: 'San pham dang duoc cap nhat mo ta.' }}</p>
                <div class="mb-4">
                    <span class="badge {{ $product->stock > 0 ? 'text-bg-success' : 'text-bg-secondary' }}">
                        {{ $product->stock > 0 ? 'Con '.$product->stock.' san pham' : 'Het hang' }}
                    </span>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <form class="d-flex gap-2" method="post" action="{{ route('cart.add', $product) }}">
                        @csrf
                        <input class="form-control" type="number" name="quantity" value="1" min="1" max="{{ max($product->stock, 1) }}" style="max-width: 120px">
                        <button class="btn btn-primary" type="submit" @disabled($product->stock <= 0)>Them vao gio</button>
                    </form>
                    <form method="post" action="{{ route('cart.buy-now', $product) }}">
                        @csrf
                        <button class="btn btn-outline-primary" type="submit" @disabled($product->stock <= 0)>Mua ngay</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($relatedProducts->isNotEmpty())
        <section class="mt-5">
            <h2 class="h4 fw-bold mb-3">San pham cung danh muc</h2>
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">
                @foreach ($relatedProducts as $relatedProduct)
                    <div class="col">
                        <a class="surface rounded-3 d-block text-decoration-none text-dark overflow-hidden h-100" href="{{ route('products.show', $relatedProduct) }}">
                            <img class="product-thumb" src="{{ $relatedProduct->image_url }}" alt="{{ $relatedProduct->name }}">
                            <div class="p-3">
                                <div class="fw-semibold">{{ $relatedProduct->name }}</div>
                                <div class="text-primary fw-bold">{{ number_format((float) $relatedProduct->price, 0, ',', '.') }}d</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
@endsection
