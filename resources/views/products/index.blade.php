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

    <div class="row g-4">
        <!-- Filter Sidebar -->
        <div class="col-lg-3">
            <div class="surface rounded-3 p-4 sticky-top" style="top: 2rem;">
                <h3 class="h5 fw-bold mb-4">Bo loc san pham</h3>
                
                <form action="{{ route('products.index') }}" method="GET">
                    <!-- Search -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">Tim kiem</label>
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Ten san pham..." value="{{ request('search') }}">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">Danh muc</label>
                        <select name="category" class="form-select" onchange="this.form.submit()">
                            <option value="">Tat ca danh muc</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Brand -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">Thuong hieu</label>
                        <select name="brand" class="form-select" onchange="this.form.submit()">
                            <option value="">Tat ca thuong hieu</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                                    {{ $brand }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">Khoang gia (d)</label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="{{ request('min_price') }}">
                            <span>-</span>
                            <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="{{ request('max_price') }}">
                        </div>
                    </div>

                    <!-- Promotion -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="on_sale" id="onSale" value="1" {{ request('on_sale') ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label small fw-bold text-secondary" for="onSale">Dang khuyen mai</label>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Ap dung</button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Xoa loc</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Product List -->
        <div class="col-lg-9">
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                @forelse ($products as $product)
                    <div class="col">
                        <div class="surface rounded-3 h-100 overflow-hidden position-relative">
                            @if($product->original_price > $product->price)
                                <span class="badge bg-danger position-absolute top-0 start-0 m-3 z-1">Giam gia</span>
                            @endif
                            <img class="product-thumb" src="{{ $product->image_url }}" alt="{{ $product->name }}">
                            <div class="p-3">
                                <div class="small text-secondary mb-1">{{ $product->category?->name }}</div>
                                <h2 class="h6 fw-bold mb-2">{{ $product->name }}</h2>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <div class="fw-bold text-primary">{{ number_format((float) $product->price, 0, ',', '.') }}d</div>
                                        @if ($product->original_price > $product->price)
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
                                    <form method="post" action="{{ route('cart.buy-now', $product) }}">
                                        @csrf
                                        <button class="btn btn-outline-primary btn-sm" type="submit" @disabled($product->stock <= 0)>Mua</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="surface rounded-3 p-5 text-center text-secondary">
                            <i class="fa-solid fa-box-open fa-3x mb-3 opacity-25"></i>
                            <p>Khong tim thay san pham nao phu hop voi yeu cau cua ban.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary">Xem tat ca san pham</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-5 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
