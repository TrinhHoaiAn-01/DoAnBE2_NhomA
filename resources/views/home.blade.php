@extends('layouts.app', ['title' => 'NeoMart'])

@section('content')
    <section class="hero-panel rounded-4 p-4 p-lg-5 mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <p class="text-uppercase small fw-semibold text-primary mb-2">NeoMart Demo</p>
                <h1 class="display-6 fw-bold mb-3">Nen tang Laravel cho cua hang ban le hien dai</h1>
                <p class="text-secondary mb-4">
                    Ban hien tai dang o dot mo phong tien do xay dung. Nhom bat dau tu quan tri danh muc de tao nen du lieu cho cac man hinh san pham va mua hang.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <div class="soft-surface rounded-4 px-3 py-2">
                        <div class="small text-secondary">Danh muc dang hien thi</div>
                        <div class="fs-4 fw-bold">{{ $activeCategoryCount }}</div>
                    </div>
                    <div class="soft-surface rounded-4 px-3 py-2">
                        <div class="small text-secondary">Tong danh muc</div>
                        <div class="fs-4 fw-bold">{{ $categories->count() }}</div>
                    </div>
                    <div class="soft-surface rounded-4 px-3 py-2">
                        <div class="small text-secondary">San pham duoc mo ban</div>
                        <div class="fs-4 fw-bold">{{ $featuredProductCount }}</div>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-primary" href="{{ route('admin.categories.index') }}">Mo quan tri danh muc</a>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.products.index') }}">Mo quan tri san pham</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="surface rounded-4 p-4 h-100">
                    <h2 class="h5 fw-bold mb-3">Danh muc hien co</h2>
                    <div class="row row-cols-2 g-3">
                        @forelse ($categories as $category)
                            <div class="col">
                                <div class="soft-surface rounded-4 p-3 h-100">
                                    <div class="icon-chip mb-2"><i class="fa-solid {{ $category->icon }}"></i></div>
                                    <div class="fw-semibold">{{ $category->name }}</div>
                                    <div class="small text-secondary">Thu tu {{ $category->sort_order }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="soft-surface rounded-4 p-3">Chua co danh muc nao.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
