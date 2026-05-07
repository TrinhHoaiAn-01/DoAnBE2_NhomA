@extends('layouts.admin', ['title' => 'NeoMart Admin - Danh muc'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4">
        <div>
            <p class="text-uppercase text-secondary small fw-semibold mb-2">Quan tri danh muc</p>
            <h1 class="h2 fw-bold mb-1">Cay danh muc NeoMart</h1>
            <p class="text-secondary mb-0">Tao, sua va an danh muc san pham de cac module khac dung chung.</p>
        </div>
    </div>

    <div class="surface rounded-4 p-3 p-lg-4 mb-4">
        <form class="row g-3 align-items-end">
            <div class="col-lg-6">
                <label class="form-label" for="search">Tim theo ten hoac slug</label>
                <input class="form-control" id="search" name="search" value="{{ $search }}" placeholder="Vi du: thuc-pham">
            </div>
            <div class="col-lg-3">
                <label class="form-label" for="status">Trang thai</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Tat ca</option>
                    <option value="active" @selected($status === 'active')>Dang hien thi</option>
                    <option value="inactive" @selected($status === 'inactive')>Tam an</option>
                </select>
            </div>
            <div class="col-lg-3 d-flex gap-2">
                <button class="btn btn-primary flex-grow-1" type="submit">Loc</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.categories.index') }}">Xoa loc</a>
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
                                <th>Danh muc</th>
                                <th>Ma slug</th>
                                <th>Thu tu</th>
                                <th>Trang thai</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $category->name }}</div>
                                        <div class="small text-secondary">{{ $category->description ?: 'Chua co mo ta.' }}</div>
                                    </td>
                                    <td><code>{{ $category->slug }}</code></td>
                                    <td>{{ $category->sort_order }}</td>
                                    <td>
                                        <span class="badge {{ $category->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                            {{ $category->is_active ? 'Dang hien thi' : 'Tam an' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.categories.index', ['category' => $category->id]) }}">Sua</a>
                                            <form method="post" action="{{ route('admin.categories.destroy', $category) }}">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Xoa</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-secondary py-4">Chua co danh muc nao.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="surface rounded-4 p-4">
                <h2 class="h4 fw-bold mb-3">{{ $editing ? 'Cap nhat danh muc' : 'Tao danh muc moi' }}</h2>
                <form method="post" action="{{ $editing ? route('admin.categories.update', $editing) : route('admin.categories.store') }}">
                    @csrf
                    @if ($editing)
                        @method('put')
                    @endif

                    <div class="mb-3">
                        <label class="form-label" for="name">Ten danh muc</label>
                        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $editing?->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="icon">Icon FontAwesome</label>
                        <input class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon', $editing?->icon ?? 'fa-box') }}">
                        @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="sort_order">Thu tu hien thi</label>
                        <input class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $editing?->sort_order ?? 0) }}">
                        @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="description">Mo ta ngan</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $editing?->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $editing?->is_active ?? true))>
                        <label class="form-check-label" for="is_active">Cho phep hien thi tren giao dien khach hang</label>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-primary" type="submit">{{ $editing ? 'Luu thay doi' : 'Them danh muc' }}</button>
                        @if ($editing)
                            <a class="btn btn-outline-secondary" href="{{ route('admin.categories.index') }}">Huy sua</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
