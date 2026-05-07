@extends('layouts.app', ['title' => 'Dang ky NeoMart'])

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7 col-xl-6">

        <div class="surface rounded-4 p-4 p-lg-5">

            <p class="text-uppercase small text-secondary fw-semibold mb-2">
                Tai khoan nguoi dung
            </p>

            <h1 class="h2 fw-bold mb-3">
                Dang ky tai khoan moi
            </h1>

            <p class="text-secondary mb-4">
                Tao tai khoan de luu thong tin ca nhan, gio hang va lich su mua sam.
            </p>

            <form method="POST" action="{{ route('register.submit') }}">
                @csrf

                <div class="row g-3">

                    <!-- NAME -->
                    <div class="col-md-6">
                        <label class="form-label" for="name">
                            Ho ten
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            required
                        >

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- PHONE -->
                    <div class="col-md-6">
                        <label class="form-label" for="phone">
                            So dien thoai
                        </label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            class="form-control @error('phone') is-invalid @enderror"
                        >

                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- EMAIL -->
                    <div class="col-12">
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
                        >

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- PASSWORD -->
                    <div class="col-md-6">
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

                    <!-- CONFIRM PASSWORD -->
                    <div class="col-md-6">
                        <label class="form-label" for="password_confirmation">
                            Nhap lai mat khau
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-control"
                            required
                        >
                    </div>

                    <!-- ROLE -->
                    <div class="col-12">
                        <label class="form-label" for="role_id">
                            Vai tro
                        </label>

                        <select
                            id="role_id"
                            name="role_id"
                            class="form-select @error('role_id') is-invalid @enderror"
                        >
                            <option value="1" {{ old('role_id') == 1 ? 'selected' : '' }}>
                                Admin
                            </option>

                            <option value="2" {{ old('role_id', 2) == 2 ? 'selected' : '' }}>
                                User
                            </option>
                        </select>

                        @error('role_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <button class="btn btn-primary w-100 mt-4" type="submit">
                    Tao tai khoan
                </button>

            </form>

        </div>
    </div>
</div>

@endsection