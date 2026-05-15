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
                <div class="small text-secondary mb-3">
                    {{ $averageRating ?: 'Chua co' }} sao tu {{ $approvedReviews->count() }} danh gia
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

    <section class="mt-5">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="surface rounded-3 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h4 fw-bold mb-0">Danh gia khach hang</h2>
                        <span class="small text-secondary">{{ $approvedReviews->count() }} danh gia</span>
                    </div>

                    @forelse ($approvedReviews as $review)
                        <div class="border-top py-3">
                            <div class="d-flex justify-content-between gap-3">
                                <div class="fw-semibold">{{ $review->customer_name }}</div>
                                <div class="text-warning fw-bold">{{ $review->rating }}/5</div>
                            </div>
                            @if ($review->title)
                                <div class="small fw-semibold mt-1">{{ $review->title }}</div>
                            @endif
                            <p class="small text-secondary mb-0 mt-1">{{ $review->content }}</p>
                        </div>
                    @empty
                        <div class="text-secondary small">Chua co danh gia duoc duyet cho san pham nay.</div>
                    @endforelse
                </div>
            </div>

            <div class="col-lg-5">
                <div class="surface rounded-3 p-4">
                    <h2 class="h4 fw-bold mb-3">Viet danh gia</h2>
                    <form method="post" action="{{ route('products.reviews.store', $product) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="customer_name">Ten cua ban</label>
                            <input class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name', auth()->user()?->name) }}" required>
                            @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="rating">So sao</label>
                            <select class="form-select @error('rating') is-invalid @enderror" id="rating" name="rating" required>
                                @for ($star = 5; $star >= 1; $star--)
                                    <option value="{{ $star }}" @selected((int) old('rating', 5) === $star)>{{ $star }} sao</option>
                                @endfor
                            </select>
                            @error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="title">Tieu de</label>
                            <input class="form-control" id="title" name="title" value="{{ old('title') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="content">Noi dung</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="4" required>{{ old('content') }}</textarea>
                            @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Gui danh gia</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

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
