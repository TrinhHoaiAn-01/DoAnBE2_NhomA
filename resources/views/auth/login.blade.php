@extends('layouts.app', ['title' => 'Dang nhap NeoMart'])

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6 col-xl-5">
            <div class="surface rounded-4 p-4 p-lg-5">
                <p class="text-uppercase small text-secondary fw-semibold mb-2">Tai khoan nguoi dung</p>
                <h1 class="h2 fw-bold mb-3">Dang nhap</h1>
                <p class="text-secondary mb-4">Su dung tai khoan da dang ky de tiep tuc mua hang va quan ly du lieu ca nhan.</p>

                <form method="post" action="{{ route('login.submit') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">Mat khau</label>
                        <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1">
                        <label class="form-check-label" for="remember">Ghi nho dang nhap</label>
                    </div>

                    <button class="btn btn-primary w-100" type="submit">Dang nhap</button>
                </form>
            </div>
        </div>
    </div>
@endsection
