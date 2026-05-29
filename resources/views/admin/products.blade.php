@extends('layouts.admin', ['title' => 'NeoMart Admin - Sản phẩm'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4">
        <div>
            <p class="text-uppercase text-secondary small fw-semibold mb-2">Quản trị sản phẩm</p>
            <h1 class="h2 fw-bold mb-1">Danh sách sản phẩm</h1>
            <p class="text-secondary mb-0">Quản lý sản phẩm và liên kết với danh mục để phục vụ mua hàng.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="soft-surface rounded-4 p-3">
                <div class="small text-secondary">Tổng sản phẩm</div>
                <div class="fs-4 fw-bold">{{ $productCount }}</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="soft-surface rounded-4 p-3">
                <div class="small text-secondary">Tồn kho thấp</div>
                <div class="fs-4 fw-bold">{{ $lowStockCount }}</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="soft-surface rounded-4 p-3">
                <div class="small text-secondary">Đang mở bán</div>
                <div class="fs-4 fw-bold">{{ $activeProductCount }}</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="soft-surface rounded-4 p-3">
                <div class="small text-secondary">Tạm ẩn</div>
                <div class="fs-4 fw-bold">{{ $hiddenProductCount }}</div>
            </div>
        </div>
    </div>

    <div class="surface rounded-4 p-3 p-lg-4 mb-4">
        <form class="row g-3 align-items-end">
            <div class="col-lg-4">
                <label class="form-label" for="search">Tìm sản phẩm</label>
                <input class="form-control" id="search" name="search" value="{{ $search }}" placeholder="Tên, SKU hoặc thương hiệu">
            </div>
            <div class="col-lg-3">
                <label class="form-label" for="category_id">Danh mục</label>
                <select class="form-select" id="category_id" name="category_id">
                    <option value="">Tất cả danh mục</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected($categoryId === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <label class="form-label" for="stock_status">Tồn kho</label>
                <select class="form-select" id="stock_status" name="stock_status">
                    <option value="">Tất cả</option>
                    <option value="low" @selected($stockStatus === 'low')>Sắp hết</option>
                    <option value="out" @selected($stockStatus === 'out')>Hết hàng</option>
                </select>
            </div>
            <div class="col-lg-3 d-flex gap-2">
                <button class="btn btn-primary flex-grow-1" type="submit">Tìm</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.products.index') }}">Bỏ lọc</a>
            </div>
        </form>
    </div>

    <div class="row g-4">
        <div class="col-xl-7">
            <div class="surface rounded-4 p-4 h-100">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Danh mục</th>
                                <th>Giá</th>
                                <th>Tồn kho</th>
                                <th>Trạng thái</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img class="rounded border" src="{{ $product->image_url ?: 'https://placehold.co/96x96?text='.urlencode($product->name) }}" alt="{{ $product->name }}" width="56" height="56" style="object-fit: cover">
                                            <div>
                                                <div class="fw-semibold">{{ $product->name }}</div>
                                                <div class="small text-secondary">{{ $product->sku }}{{ $product->brand ? ' - '.$product->brand : '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $product->category?->name }}</td>
                                    <td>{{ number_format((float) $product->price, 0, ',', '.') }}đ</td>
                                    <td>
                                        <span class="badge {{ $product->stock > 10 ? 'text-bg-success' : ($product->stock > 0 ? 'text-bg-warning' : 'text-bg-danger') }}">
                                            {{ $product->stock }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $product->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                            {{ $product->is_active ? 'Đang bán' : 'Tạm ẩn' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.products.index', ['product' => $product->id]) }}">Sửa</a>
                                            <form method="post" action="{{ route('admin.products.destroy', $product) }}">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-secondary py-4">Chưa có sản phẩm nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="surface rounded-4 p-4">
                <h2 class="h4 fw-bold mb-3">{{ $editing ? 'Cập nhật sản phẩm' : 'Thêm sản phẩm' }}</h2>
                <form method="post" action="{{ $editing ? route('admin.products.update', $editing) : route('admin.products.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if ($editing)
                        @method('put')
                        <input type="hidden" name="_record_updated_at" value="{{ $editing->updated_at?->getTimestamp() }}">
                    @endif

                    <div class="mb-3">
                        <label class="form-label" for="category_id">Danh mục</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="">Chọn danh mục</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) old('category_id', $editing?->category_id) === (string) $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label" for="name">Tên sản phẩm</label>
                            <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $editing?->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="sku">SKU</label>
                            <input class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', $editing?->sku) }}" required>
                            @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label" for="brand">Thương hiệu</label>
                            <input class="form-control @error('brand') is-invalid @enderror" id="brand" name="brand" value="{{ old('brand', $editing?->brand) }}">
                            @error('brand')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="image_url">Ảnh URL</label>
                            <input class="form-control @error('image_url') is-invalid @enderror" id="image_url" name="image_url" value="{{ old('image_url', $editing?->image_url) }}">
                            @error('image_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label" for="product_image">Tải ảnh sản phẩm</label>
                        <input class="form-control @error('product_image') is-invalid @enderror" id="product_image" name="product_image" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                        <div class="form-text">Nếu tải ảnh mới, hệ thống sẽ tự cập nhật link ảnh cho sản phẩm.</div>
                        @error('product_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    @if ($editing?->image_url)
                        <div class="mt-3">
                            <div class="small text-secondary mb-2">Ảnh hiện tại</div>
                            <img class="rounded border" src="{{ $editing->image_url }}" alt="{{ $editing->name }}" width="120" height="120" style="object-fit: cover">
                        </div>
                    @endif

                    <div class="row g-3 mt-1">
                        <div class="col-md-4">
                            <label class="form-label" for="price">Giá bán</label>
                            <input class="form-control @error('price') is-invalid @enderror" id="price" name="price" type="number" min="0" step="1000" value="{{ old('price', $editing?->price) }}" required>
                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="original_price">Giá gốc</label>
                            <input class="form-control @error('original_price') is-invalid @enderror" id="original_price" name="original_price" type="number" min="0" step="1000" value="{{ old('original_price', $editing?->original_price) }}">
                            @error('original_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="stock">Tồn kho</label>
                            <input class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" type="number" min="0" value="{{ old('stock', $editing?->stock ?? 0) }}" required>
                            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label" for="description">Mô tả</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $editing?->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-check form-switch my-4">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $editing?->is_active ?? true))>
                        <label class="form-check-label" for="is_active">Cho phép bán trên giao diện khách hàng</label>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-primary" type="submit">{{ $editing ? 'Lưu thay đổi' : 'Thêm sản phẩm' }}</button>
                        @if ($editing)
                            <a class="btn btn-outline-secondary" href="{{ route('admin.products.index') }}">Hủy sửa</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
