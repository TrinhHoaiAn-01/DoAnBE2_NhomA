<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Hỗ trợ người dùng
    </title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {

            background:
                linear-gradient(135deg,
                    #0f172a,
                    #111827,
                    #1e293b);

            min-height: 100vh;

            color: white;
        }

        .support-container {

            max-width: 900px;

            margin: 50px auto;

            padding: 20px;
        }

        .support-card {

            background:
                rgba(255, 255, 255, 0.08);

            backdrop-filter: blur(15px);

            border-radius: 25px;

            padding: 35px;

            border:
                1px solid rgba(255, 255, 255, 0.1);

            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .title {

            font-size: 32px;

            font-weight: 700;

            text-align: center;

            margin-bottom: 10px;
        }

        .subtitle {

            text-align: center;

            color: #cbd5e1;

            margin-bottom: 30px;

            font-size: 15px;
        }

        .form-label {

            margin-bottom: 8px;

            font-weight: 500;
        }

        .form-control,
        .form-select {

            background:
                rgba(255, 255, 255, 0.08);

            border:
                1px solid rgba(255, 255, 255, 0.1);

            color: white;

            border-radius: 15px;

            padding: 14px;
        }

        .form-control::placeholder {

            color: #cbd5e1;
        }

        .form-control:focus,
        .form-select:focus {

            background:
                rgba(255, 255, 255, 0.1);

            color: white;

            box-shadow: none;

            border-color: #60a5fa;
        }

        textarea {

            resize: none;
        }

        .form-select option {

            color: black;
        }

        .btn-support {

            width: 100%;

            padding: 14px;

            border: none;

            border-radius: 15px;

            background:
                linear-gradient(135deg,
                    #3b82f6,
                    #2563eb);

            color: white;

            font-size: 18px;

            font-weight: 600;

            transition: 0.3s;
        }

        .btn-support:hover {

            transform: translateY(-2px);

            opacity: 0.9;
        }

        .support-info {

            margin-top: 40px;

            display: grid;

            grid-template-columns:
                repeat(auto-fit, minmax(220px, 1fr));

            gap: 20px;
        }

        .info-box {

            background:
                rgba(255, 255, 255, 0.06);

            border-radius: 20px;

            padding: 20px;

            text-align: center;

            border:
                1px solid rgba(255, 255, 255, 0.08);
        }

        .info-box i {

            font-size: 35px;

            margin-bottom: 15px;

            color: #60a5fa;
        }

        .info-box h5 {

            margin-bottom: 10px;
        }

        .info-box p {

            color: #cbd5e1;

            font-size: 14px;
        }

        .alert {

            border-radius: 15px;
        }
    </style>

</head>

<body>

    <div class="container support-container">

        <div class="support-card">

            <!-- TITLE -->
            <div class="title">

                <i class="fa-solid fa-headset"></i>

                Trung tâm hỗ trợ

            </div>

            <!-- SUBTITLE -->
            <div class="subtitle">

                Nếu bạn gặp vấn đề với hệ thống hoặc tài khoản,
                hãy gửi yêu cầu hỗ trợ cho admin.

            </div>

            <!-- SUCCESS -->
            @if(session('success'))

                <div class="alert alert-success">

                    {{ session('success') }}

                </div>

            @endif

            <!-- ERROR -->
            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>

                                {{ $error }}

                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <!-- FORM -->
            <form method="POST" action="{{ route('support.send') }}">

                @csrf

                <!-- NAME -->
                <div class="mb-3">

                    <label class="form-label">

                        Họ và tên

                    </label>

                    <input type="text" class="form-control" name="name" placeholder="Nhập họ và tên"
                        value="{{ old('name', Auth::user()->name ?? '') }}">

                </div>

                <!-- EMAIL -->
                <div class="mb-3">

                    <label class="form-label">

                        Email

                    </label>

                    <input type="email" class="form-control" name="email" placeholder="example@gmail.com"
                        value="{{ old('email', Auth::user()->email ?? '') }}">

                </div>

                <!-- TYPE -->
                <div class="mb-3">

                    <label class="form-label">

                        Loại hỗ trợ

                    </label>

                    <select name="type" class="form-select">

                        <option value="">
                            -- Chọn loại hỗ trợ --
                        </option>

                        <option value="account">

                            Lỗi tài khoản

                        </option>

                        <option value="payment">

                            Thanh toán

                        </option>

                        <option value="system">

                            Lỗi hệ thống

                        </option>

                        <option value="other">

                            Khác

                        </option>

                    </select>

                </div>

                <!-- MESSAGE -->
                <div class="mb-4">

                    <label class="form-label">

                        Nội dung hỗ trợ

                    </label>

                    <textarea name="message" rows="6" class="form-control"
                        placeholder="Mô tả vấn đề của bạn...">{{ old('message') }}</textarea>

                </div>

                <!-- BUTTON -->
                <button type="submit" class="btn-support">

                    <i class="fa-solid fa-paper-plane"></i>

                    Gửi hỗ trợ

                </button>

            </form>

            <!-- INFO -->
            <div class="support-info">

                <!-- BOX 1 -->
                <div class="info-box">

                    <i class="fa-solid fa-clock"></i>

                    <h5>

                        Phản hồi

                    </h5>

                    <p>

                        Trong vòng 24 giờ

                    </p>

                </div>

                <!-- BOX 2 -->
                <div class="info-box">

                    <i class="fa-solid fa-envelope"></i>

                    <h5>

                        Email hỗ trợ

                    </h5>

                    <p>

                        admin@gmail.com

                    </p>

                </div>

                <!-- BOX 3 -->
                <div class="info-box">

                    <i class="fa-solid fa-shield"></i>

                    <h5>

                        Bảo mật

                    </h5>

                    <p>

                        Dữ liệu được bảo vệ an toàn

                    </p>

                </div>

            </div>

        </div>

    </div>

</body>

</html>