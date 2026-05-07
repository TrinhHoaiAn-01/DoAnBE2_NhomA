@extends('layouts.app', ['title' => 'Dang nhap NeoMart'])

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6 col-xl-5">

        <div class="surface rounded-4 p-4 p-lg-5">

            <p class="text-uppercase small text-secondary fw-semibold mb-2">
                Tai khoan nguoi dung
            </p>

            <h1 class="h2 fw-bold mb-3">
                Dang nhap
            </h1>

            <p class="text-secondary mb-4">
                Su dung tai khoan da dang ky de tiep tuc mua hang va quan ly du lieu ca nhan.
            </p>

            {{-- SUCCESS --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ERROR --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <!-- EMAIL -->
                <div class="mb-3">
                    <label class="form-label" for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror"
                        required
                        autofocus
                    >

                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- PASSWORD -->
                <div class="mb-3">
                    <label class="form-label" for="password">
                        Mat khau
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required
                    >

                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- REMEMBER -->
                <div class="form-check mb-4">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="remember"
                        name="remember"
                        value="1"
                        {{ old('remember') ? 'checked' : '' }}
                    >

                    <label class="form-check-label" for="remember">
                        Ghi nho dang nhap
                    </label>
                </div>

                <!-- BUTTON -->
                <button class="btn btn-primary w-100" type="submit">
                    Dang nhap
                </button>

                <!-- FORGOT -->
                <div class="text-center mt-3">
                    <a href="{{ route('password.request') }}"
                       class="text-decoration-none">
                        Quen mat khau?
                    </a>
                </div>

                <!-- REGISTER -->
                <div class="text-center mt-3">
                    <span class="text-secondary">
                        Chua co tai khoan?
                    </span>

                    <a href="{{ route('register') }}"
                       class="fw-semibold text-decoration-none">
                        Dang ky ngay
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection