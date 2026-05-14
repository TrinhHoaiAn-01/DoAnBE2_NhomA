@extends('layouts.admin', ['title' => 'NeoMart Admin - Khuyáº¿n mÃ£i'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4">
        <div>
            <p class="text-uppercase text-secondary small fw-semibold mb-2">Khuyáº¿n mÃ£i</p>
            <h1 class="h2 fw-bold mb-1">MÃ£ giáº£m giÃ¡</h1>
            <p class="text-secondary mb-0">Táº¡o mÃ£ khuyáº¿n mÃ£i cho Ä‘Æ¡n hÃ ng vÃ  kiá»ƒm soÃ¡t thá»i gian sá»­ dá»¥ng.</p>
        </div>
    </div>

    <div class="surface rounded-4 p-3 p-lg-4 mb-4">
        <form class="row g-3 align-items-end">
            <div class="col-lg-9">
                <label class="form-label" for="search">TÃ¬m mÃ£ hoáº·c tÃªn chÆ°Æ¡ng trÃ¬nh</label>
                <input class="form-control" id="search" name="search" value="{{ $search }}" placeholder="VÃ­ dá»¥: SALE20">
            </div>
            <div class="col-lg-3 d-flex gap-2">
                <button class="btn btn-primary flex-grow-1" type="submit">TÃ¬m</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.promotions.index') }}">Bá» lá»c</a>
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
                                <th>MÃ£</th>
                                <th>Giáº£m</th>
                                <th>Äiá»u kiá»‡n</th>
                                <th>LÆ°á»£t dÃ¹ng</th>
                                <th>Tráº¡ng thÃ¡i</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($promotions as $promotion)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $promotion->code }}</div>
                                        <div class="small text-secondary">{{ $promotion->name }}</div>
                                    </td>
                                    <td>
                                        {{ $promotion->discount_type === 'percent' ? $promotion->discount_value.'%' : number_format((float) $promotion->discount_value, 0, ',', '.').'Ä‘' }}
                                    </td>
                                    <td>Tá»« {{ number_format((float) $promotion->minimum_order, 0, ',', '.') }}Ä‘</td>
                                    <td>{{ $promotion->used_count }}{{ $promotion->usage_limit ? ' / '.$promotion->usage_limit : '' }}</td>
                                    <td>
                                        <span class="badge {{ $promotion->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                            {{ $promotion->is_active ? 'Äang báº­t' : 'Táº¡m táº¯t' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.promotions.index', ['promotion' => $promotion->id]) }}">Sá»­a</a>
                                            <form method="post" action="{{ route('admin.promotions.destroy', $promotion) }}">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-sm btn-outline-danger" type="submit">XÃ³a</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-secondary py-4">ChÆ°a cÃ³ mÃ£ giáº£m giÃ¡ nÃ o.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $promotions->links() }}</div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="surface rounded-4 p-4">
                <h2 class="h4 fw-bold mb-3">{{ $editing ? 'Cáº­p nháº­t mÃ£' : 'Táº¡o mÃ£ má»›i' }}</h2>
                <form method="post" action="{{ $editing ? route('admin.promotions.update', $editing) : route('admin.promotions.store') }}">
                    @csrf
                    @if ($editing)
                        @method('put')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label" for="code">MÃ£</label>
                            <input class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $editing?->code) }}" required>
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-7">
                            <label class="form-label" for="name">TÃªn chÆ°Æ¡ng trÃ¬nh</label>
                            <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $editing?->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label" for="discount_type">Loáº¡i giáº£m</label>
                            <select class="form-select" id="discount_type" name="discount_type">
                                <option value="fixed" @selected(old('discount_type', $editing?->discount_type ?? 'fixed') === 'fixed')>Sá»‘ tiá»n</option>
                                <option value="percent" @selected(old('discount_type', $editing?->discount_type) === 'percent')>Pháº§n trÄƒm</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="discount_value">GiÃ¡ trá»‹</label>
                            <input class="form-control @error('discount_value') is-invalid @enderror" id="discount_value" name="discount_value" type="number" min="0" step="1000" value="{{ old('discount_value', $editing?->discount_value) }}" required>
                            @error('discount_value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label" for="minimum_order">ÄÆ¡n tá»‘i thiá»ƒu</label>
                            <input class="form-control" id="minimum_order" name="minimum_order" type="number" min="0" step="1000" value="{{ old('minimum_order', $editing?->minimum_order ?? 0) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="usage_limit">Giá»›i háº¡n lÆ°á»£t dÃ¹ng</label>
                            <input class="form-control" id="usage_limit" name="usage_limit" type="number" min="1" value="{{ old('usage_limit', $editing?->usage_limit) }}">
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label" for="starts_at">Báº¯t Ä‘áº§u</label>
                            <input class="form-control" id="starts_at" name="starts_at" type="datetime-local" value="{{ old('starts_at', $editing?->starts_at?->format('Y-m-d\TH:i')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="ends_at">Káº¿t thÃºc</label>
                            <input class="form-control" id="ends_at" name="ends_at" type="datetime-local" value="{{ old('ends_at', $editing?->ends_at?->format('Y-m-d\TH:i')) }}">
                        </div>
                    </div>

                    <div class="form-check form-switch my-4">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $editing?->is_active ?? true))>
                        <label class="form-check-label" for="is_active">Cho phÃ©p Ã¡p dá»¥ng mÃ£</label>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-primary" type="submit">{{ $editing ? 'LÆ°u thay Ä‘á»•i' : 'ThÃªm mÃ£' }}</button>
                        @if ($editing)
                            <a class="btn btn-outline-secondary" href="{{ route('admin.promotions.index') }}">Há»§y sá»­a</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

