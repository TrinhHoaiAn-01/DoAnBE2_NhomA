@extends('layouts.app', [
    'title' => 'Đăng ký NeoMart',
    'hideNavbar' => true
])

@section('content')

<style>

    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
    }

    body{

        min-height:100vh;

        overflow-x:hidden;
        overflow-y:auto;

        font-family:system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;

        background:
            radial-gradient(circle at top left, rgba(22,163,74,0.20), transparent 25%),
            radial-gradient(circle at bottom right, rgba(4,120,87,0.20), transparent 25%),
            linear-gradient(
                135deg,
                #020617,
                #071126,
                #020617
            );

        color:white;

        position:relative;
    }

    .navbar:first-of-type{
        display:none !important;
    }

    /* =========================
        BACKGROUND LIGHT
    ========================== */

    .bg-light{

        position:fixed;

        border-radius:50%;

        filter:blur(120px);

        z-index:-1;

        opacity:0.4;
    }

    .bg-light.one{

        width:260px;
        height:260px;

        background:#16a34a;

        top:-80px;
        left:-80px;
    }

    .bg-light.two{

        width:340px;
        height:340px;

        background:#047857;

        right:-120px;
        bottom:-120px;
    }

    /* =========================
        REGISTER WRAPPER
    ========================== */

    .register-wrapper{

        min-height:100vh;

        display:flex;

        justify-content:center;
        align-items:flex-start;

        padding:120px 20px 30px;
    }

    /* =========================
        REGISTER CARD
    ========================== */

    .register-card{

        width:100%;
        max-width:560px;

        padding:45px;

        border-radius:34px;

        background:
            rgba(255,255,255,0.06);

        backdrop-filter:blur(18px);

        border:
            1px solid rgba(255,255,255,0.08);

        box-shadow:
            0 10px 40px rgba(0,0,0,0.45);

        position:relative;

        overflow:hidden;

        margin-top:-110px;
    }

    /* =========================
        TITLE
    ========================== */

    .main-title{

        text-align:center;

        font-size:34px;

        font-weight:800;

        margin-bottom:10px;
    }

    .sub-title{

        text-align:center;

        color:#94a3b8;

        font-size:14px;

        margin-bottom:35px;
    }

    /* =========================
        FORM
    ========================== */

    .form-group{
        margin-bottom:22px;
    }

    .form-label{

        display:block;

        margin-bottom:9px;

        color:#f8fafc;

        font-size:14px;

        font-weight:600;
    }

    .form-control{

        width:100%;

        padding:17px 18px;

        border-radius:18px;

        border:
            1px solid rgba(255,255,255,0.08);

        background:
            rgba(255,255,255,0.05);

        color:#ffffff !important;

        font-size:14px;

        transition:0.3s;

        caret-color:#ffffff;

        outline:none;

        appearance:none;

        -webkit-appearance:none;
    }

    .form-control::placeholder{
        color:#94a3b8;
    }

    .form-control:focus{

        border-color:#22c55e;

        background:
            rgba(255,255,255,0.08);

        box-shadow:
            0 0 0 4px rgba(22,163,74,0.15);

        color:#ffffff !important;
    }

    input,
    textarea{

        color:#ffffff !important;
    }

    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus{

        -webkit-text-fill-color:#ffffff !important;

        box-shadow:
            0 0 0px 1000px #0f172a inset !important;

        transition:
            background-color 9999s ease-in-out 0s;
    }

    /* =========================
        CAPTCHA
    ========================== */

    .recaptcha-wrapper{

        display:flex;

        justify-content:center;

        min-height:78px;

        overflow:hidden;
    }

    /* =========================
        ERROR
    ========================== */

    .invalid-feedback{

        color:#fca5a5;

        font-size:13px;

        margin-top:8px;
    }

    .alert-success{

        padding:14px 16px;

        margin-bottom:22px;

        border-radius:16px;

        color:#bbf7d0;

        background:rgba(34,197,94,0.12);

        border:1px solid rgba(34,197,94,0.22);

        font-size:14px;
    }

    .otp-panel{

        padding:18px;

        margin-bottom:22px;

        border-radius:20px;

        background:rgba(59,130,246,0.10);

        border:1px solid rgba(147,197,253,0.18);

        color:#cbd5e1;

        font-size:14px;

        line-height:1.6;
    }

    .otp-email{

        color:#ffffff;

        font-weight:700;

        word-break:break-word;
    }

    .otp-actions{

        display:flex;

        justify-content:center;

        gap:14px;

        margin-top:18px;

        flex-wrap:wrap;
    }

    .link-btn{

        border:0;

        background:transparent;

        color:#93c5fd;

        font-weight:600;

        cursor:pointer;

        padding:0;
    }

    .link-btn:hover{
        color:#ffffff;
    }

    /* =========================
        BUTTON
    ========================== */

    .submit-btn{

        width:100%;

        border:none;

        padding:17px;

        border-radius:20px;

        color:white;

        font-size:15px;
        font-weight:700;

        cursor:pointer;

        margin-top:10px;

        transition:0.35s;

        background:
            linear-gradient(
                135deg,
                #16a34a,
                #047857
            );

        box-shadow:
            0 12px 30px rgba(4,120,87,0.22);
    }

    .submit-btn:hover{

        transform:translateY(-3px);

        box-shadow:
            0 18px 35px rgba(4,120,87,0.32);
    }

    /* =========================
        LOGIN LINK
    ========================== */

    .login-link{

        text-align:center;

        margin-top:28px;

        font-size:14px;

        color:#cbd5e1;
    }

    .login-link a{

        color:#93c5fd;

        text-decoration:none;

        font-weight:600;

        transition:0.3s;
    }

    .login-link a:hover{
        color:white;
    }

    /* =========================
        RESPONSIVE
    ========================== */

    @media(max-width:768px){

        .register-wrapper{
            padding-top:70px;
        }

        .register-card{

            padding:32px 24px;
        }

        .main-title{
            font-size:28px;
        }
    }

    @media(max-width:380px){

        .recaptcha-wrapper .g-recaptcha{
            transform:scale(0.86);
            transform-origin:center top;
        }
    }

</style>

<!-- BACKGROUND -->
<div class="bg-light one"></div>
<div class="bg-light two"></div>

<!-- REGISTER -->
<div class="register-wrapper">

    <div class="register-card">

        <!-- TITLE -->
        <h1 class="main-title">
            Đăng ký tài khoản
        </h1>

        <p class="sub-title">
            Tạo tài khoản để tiếp tục mua sắm cùng NeoMart
        </p>

        <!-- FORM -->
        <form
            method="POST"
            action="{{ route('register.submit') }}"
        >

            @csrf

            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($pendingRegistration ?? false)

                <div class="otp-panel">
                    Mã OTP đã được gửi tới
                    <span class="otp-email">{{ $pendingRegistration['email'] }}</span>.
                    Mã có hiệu lực trong 3 phút và có thể đối chiếu bằng Google Authenticator.
                </div>

                <div class="form-group">

                    <label class="form-label">
                        Mã OTP
                    </label>

                    <input
                        type="text"
                        name="registration_otp"
                        value="{{ old('registration_otp') }}"
                        class="form-control @error('registration_otp') is-invalid @enderror"
                        placeholder="Nhập mã OTP 6 số..."
                        inputmode="numeric"
                        maxlength="6"
                        autocomplete="one-time-code"
                        required
                        autofocus
                    >

                    @error('registration_otp')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <button
                    type="submit"
                    class="submit-btn"
                >
                    Xác nhận OTP
                </button>

                <div class="otp-actions">
                    <button
                        type="submit"
                        class="link-btn"
                        formmethod="POST"
                        formaction="{{ route('register.otp.resend') }}"
                        formnovalidate
                    >
                        Gửi lại mã OTP
                    </button>

                    <button
                        type="submit"
                        class="link-btn"
                        formmethod="POST"
                        formaction="{{ route('register.otp.cancel') }}"
                        formnovalidate
                    >
                        Đăng ký lại
                    </button>
                </div>

            @else

            <!-- ACCOUNT NAME -->
            <div class="form-group">

                <label class="form-label">
                    Tên tài khoản
                </label>

                <input
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    class="form-control @error('username') is-invalid @enderror"
                    placeholder="Nhập tên tài khoản..."
                    autocomplete="off"
                    required
                >

                @error('username')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <!-- EMAIL -->
            <div class="form-group">

                <label class="form-label">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="Nhập email..."
                    autocomplete="off"
                    required
                >

                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <!-- PASSWORD -->
            <div class="form-group">

                <label class="form-label">
                    Mật khẩu
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Nhập mật khẩu..."
                    autocomplete="off"
                    required
                >

                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="form-group">

                <label class="form-label">
                    Nhập lại mật khẩu
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    class="form-control"
                    placeholder="Nhập lại mật khẩu..."
                    autocomplete="off"
                    required
                >

            </div>

            <!-- CAPTCHA -->
            <div class="form-group">

                <div class="recaptcha-wrapper">

                    <div
                        class="g-recaptcha"
                        data-sitekey="{{ config('services.recaptcha.site_key') }}"
                    ></div>

                </div>

                @error('g-recaptcha-response')
                    <div class="invalid-feedback d-block text-center">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <!-- BUTTON -->
            <button
                type="submit"
                class="submit-btn"
            >
                Tạo tài khoản
            </button>

            @endif

            <!-- LOGIN -->
            <div class="login-link">

                Đã có tài khoản?

                <a href="{{ route('login') }}">
                    Đăng nhập ngay
                </a>

            </div>

        </form>

    </div>

</div>

@push('scripts')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush

@endsection
