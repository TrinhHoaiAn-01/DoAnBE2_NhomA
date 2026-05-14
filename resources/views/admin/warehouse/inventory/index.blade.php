@extends('layouts.admin', ['title' => 'NeoMart Admin - Tồn kho'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4">
        <div>
            <p class="text-uppercase text-secondary small fw-semibold mb-2">Kho vận</p>
            <h1 class="h2 fw-bold mb-1">Tồn kho & Lô hàng</h1>
            <p class="text-secondary mb-0">Giám sát số lượng sản phẩm còn lại trong kho.</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="soft-surface p-4 d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">
                    <i class="bi bi-boxes fs-3"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium text-uppercase">Tổng sản phẩm trong kho</div>
                    <h3 class="mb-0 fw-bold">{{ number_format($totalStock, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="soft-surface p-4 d-flex align-items-center border-warning border-start border-4">
                <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3 me-3">
                    <i class="bi bi-exclamation-triangle fs-3"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium text-uppercase">Sản phẩm sắp hết (< 10)</div>
                    <h3 class="mb-0 fw-bold text-warning">{{ $lowStockCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="soft-surface p-4 d-flex align-items-center border-danger border-start border-4">
                <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-3 me-3">
                    <i class="bi bi-x-circle fs-3"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium text-uppercase">Sản phẩm hết hàng</div>
                    <h3 class="mb-0 fw-bold text-danger">{{ $outOfStockCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="surface p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Mã SKU</th>
                        <th>Giá bán</th>
                        <th>Tồn kho hiện tại</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded" width="48" height="48" style="object-fit: cover">
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                </div>
                            </td>
                            <td><code>{{ $product->sku }}</code></td>
                            <td>{{ number_format($product->price, 0, ',', '.') }}đ</td>
                            <td>
                                @if ($product->stock == 0)
                                    <span class="badge bg-danger rounded-pill px-3 py-2">Hết hàng (0)</span>
                                @elseif ($product->stock <= 10)
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Sắp hết ({{ $product->stock }})</span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-medium"><i class="bi bi-check-circle me-1"></i> Còn {{ $product->stock }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.products.index', ['search' => $product->sku]) }}" class="btn btn-sm btn-outline-secondary">Tới SP</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Không có sản phẩm nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $products->links() }}</div>
    </div>
@endsection
