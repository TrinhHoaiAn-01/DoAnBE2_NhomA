@extends('layouts.app', ['title' => 'Dang ky NeoMart'])

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">
            <div class="surface rounded-4 p-4 p-lg-5">
                <p class="text-uppercase small text-secondary fw-semibold mb-2">Tai khoan nguoi dung</p>
                <h1 class="h2 fw-bold mb-3">Dang ky tai khoan moi</h1>
                <p class="text-secondary mb-4">Tao tai khoan de luu thong tin ca nhan, gio hang va lich su mua sam trong cac dot tiep theo.</p>

                <form method="post" action="{{ route('register.submit') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="name">Ho ten</label>
                            <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="phone">So dien thoai</label>
                            <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="email">Email</label>
                            <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="password">Mat khau</label>
                            <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="password_confirmation">Nhap lai mat khau</label>
                            <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" required>
                        </div>
                    </div>

                    <button class="btn btn-primary w-100 mt-4" type="submit">Tao tai khoan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
