<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f5f5f5, #eaeaea);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            width: 100%;
            max-width: 380px;
            background: #fff;
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
        }

        .login-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .login-title {
            font-weight: 600;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 8px;
        }

        .btn-primary {
            border-radius: 8px;
            font-weight: 500;
        }

        .forgot {
            font-size: 14px;
            color: #666;
            text-decoration: none;
        }

        .forgot:hover {
            color: #0d6efd;
        }
    </style>
</head>

<body>

<div class="login-box">

    <h4 class="text-center login-title">Đăng nhập</h4>

    {{-- SUCCESS --}}
    @if (session('success'))
        <div class="alert alert-success py-2">
            {{ session('success') }}
        </div>
    @endif

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="alert alert-danger py-2">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- EMAIL -->
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email"
                   name="email"
                   class="form-control"
                   value="{{ old('email') }}"
                   placeholder="Nhập email..."
                   required>
        </div>

        <!-- PASSWORD -->
        <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password"
                   name="password"
                   class="form-control"
                   placeholder="Nhập mật khẩu..."
                   required>
        </div>

        <!-- REMEMBER -->
        <div class="mb-3 form-check">
            <input type="checkbox"
                   class="form-check-input"
                   name="remember"
                   id="remember"
                   {{ old('remember') ? 'checked' : '' }}>

            <label class="form-check-label" for="remember">
                Ghi nhớ đăng nhập
            </label>
        </div>

        <!-- BUTTON -->
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary">
                Đăng nhập
            </button>
        </div>

        <!-- FORGOT -->
        <div class="text-center">
            <a href="{{ route('password.request') }}" class="forgot">
                Quên mật khẩu?
            </a>
        </div>

        <!-- REGISTER -->
        <div class="text-center mt-3">
            <span style="font-size: 14px; color: #666;">
                Chưa có tài khoản?
            </span>
            <a href="{{ route('register.form') }}"
               class="fw-semibold text-primary text-decoration-none">
                Đăng ký ngay
            </a>
        </div>

    </form>

</div>

</body>
</html>