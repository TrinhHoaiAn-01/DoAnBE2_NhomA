@extends('layouts.admin', ['title' => 'NeoMart Admin - Don hang'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4">
        <div>
            <p class="text-uppercase text-secondary small fw-semibold mb-2">Quan tri don hang</p>
            <h1 class="h2 fw-bold mb-1">Danh sach don hang</h1>
            <p class="text-secondary mb-0">Theo doi don moi, cap nhat xu ly va trang thai giao hang.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="soft-surface rounded-4 p-3">
                <div class="small text-secondary">Cho xu ly</div>
                <div class="fs-4 fw-bold">{{ $pendingCount }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="soft-surface rounded-4 p-3">
                <div class="small text-secondary">Dang xu ly</div>
                <div class="fs-4 fw-bold">{{ $processingCount }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="soft-surface rounded-4 p-3">
                <div class="small text-secondary">Hoan tat</div>
                <div class="fs-4 fw-bold">{{ $completedCount }}</div>
            </div>
        </div>
    </div>

    <div class="surface rounded-4 p-3 p-lg-4 mb-4">
        <form class="row g-3 align-items-end">
            <div class="col-lg-7">
                <label class="form-label" for="search">Tim don hang</label>
                <input class="form-control" id="search" name="search" value="{{ $search }}" placeholder="Ma don, ten khach hoac so dien thoai">
            </div>
            <div class="col-lg-3">
                <label class="form-label" for="status">Trang thai</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Tat ca</option>
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 d-flex gap-2">
                <button class="btn btn-primary flex-grow-1" type="submit">Loc</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.orders.index') }}">Xoa</a>
            </div>
        </form>
    </div>

    <div class="surface rounded-4 p-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Ma don</th>
                        <th>Khach hang</th>
                        <th>San pham</th>
                        <th>Tong tien</th>
                        <th>Trang thai</th>
                        <th>Ngay tao</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td><code>{{ $order->code }}</code></td>
                            <td>
                                <div class="fw-semibold">{{ $order->customer_name }}</div>
                                <div class="small text-secondary">{{ $order->customer_phone }}</div>
                            </td>
                            <td>{{ $order->items_count }}</td>
                            <td class="fw-semibold">{{ number_format((float) $order->total, 0, ',', '.') }}d</td>
                            <td>{{ $statusOptions[$order->status] ?? $order->status }}</td>
                            <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.orders.show', $order) }}">Chi tiet</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-secondary py-4">Chua co don hang nao.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $orders->links() }}</div>
    </div>
@endsection
