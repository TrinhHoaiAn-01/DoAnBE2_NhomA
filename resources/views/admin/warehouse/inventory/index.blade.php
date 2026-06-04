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
                                <a href="{{ route('admin.warehouse.inventory.history', $product->id) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-clock-history"></i> Thẻ kho</a>
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

    <!-- KHU VỰC CẢNH BÁO LÔ HÀNG SẮP HẾT HẠN (THÊM CHO TASK 45) -->
    @if(isset($expiringBatches) && $expiringBatches->count() > 0)
    <div class="mt-5">
        <h4 class="fw-bold mb-3 text-danger"><i class="bi bi-calendar-x"></i> Cảnh báo Hạn Sử Dụng (Dưới 30 ngày)</h4>
        <div class="surface rounded-4 p-4 border border-danger border-opacity-50">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Mã lô (Batch)</th>
                            <th>Hạn sử dụng</th>
                            <th>Số lượng nhập lúc đầu</th>
                            <th>Thuộc Phiếu nhập</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expiringBatches as $batch)
                            @php
                                $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($batch->expires_at), false);
                            @endphp
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $batch->product->name ?? 'N/A' }}</div>
                                    <small class="text-muted">SKU: {{ $batch->product->sku ?? 'N/A' }}</small>
                                </td>
                                <td><span class="badge bg-secondary">{{ $batch->batch_code ?? 'Không có' }}</span></td>
                                <td class="fw-bold text-danger">{{ \Carbon\Carbon::parse($batch->expires_at)->format('d/m/Y') }}</td>
                                <td>{{ $batch->quantity }}</td>
                                <td><a href="{{ route('admin.warehouse.receipts.show', $batch->receipt->id) }}">{{ $batch->receipt->code }}</a></td>
                                <td>
                                    @if($daysLeft < 0)
                                        <span class="badge bg-danger">Đã hết hạn (Quá {{ abs($daysLeft) }} ngày)</span>
                                    @elseif($daysLeft == 0)
                                        <span class="badge bg-danger">Hết hạn hôm nay</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Còn {{ $daysLeft }} ngày</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
@endsection
