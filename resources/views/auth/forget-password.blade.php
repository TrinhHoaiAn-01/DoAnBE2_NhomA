<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>

    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f5f5f5, #eaeaea);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .box {
            width: 100%;
            max-width: 420px;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .title {
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-control {
            border-radius: 8px;
        }

        .btn-primary {
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <div class="box">

        <h4 class="title">Quên mật khẩu</h4>

        {{-- SUCCESS --}}
        @if(session('success'))
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

        <form method="POST" action="{{ route('password.update.fake') }}">
            @csrf

            <!-- EMAIL -->
            <div class="mb-3">
                <label>Email</label>
                <input type="email"
                       name="email"
                       class="form-control"
                       required>
            </div>

            <!-- NEW PASSWORD -->
            <div class="mb-3">
                <label>Mật khẩu mới</label>
                <input type="password"
                       name="password"
                       class="form-control"
                       required>
            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="mb-3">
                <label>Xác nhận mật khẩu</label>
                <input type="password"
                       name="password_confirmation"
                       class="form-control"
                       required>
            </div>

            <button class="btn btn-primary w-100">
                Đổi mật khẩu
            </button>

        </form>

    </div>

</body>

</html>