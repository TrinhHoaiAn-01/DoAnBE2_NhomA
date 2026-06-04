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

        overflow:hidden;

        font-family:'Segoe UI',sans-serif;

        background:
            radial-gradient(circle at top left, rgba(37,99,235,0.20), transparent 25%),
            radial-gradient(circle at bottom right, rgba(124,58,237,0.20), transparent 25%),
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

        background:#2563eb;

        top:-80px;
        left:-80px;
    }

    .bg-light.two{

        width:340px;
        height:340px;

        background:#7c3aed;

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

        border-color:#3b82f6;

        background:
            rgba(255,255,255,0.08);

        box-shadow:
            0 0 0 4px rgba(37,99,235,0.15);

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

    .captcha-wrapper{

        width:100%;

        min-height:78px;

        border-radius:18px;

        border:
            1px dashed rgba(255,255,255,0.12);

        background:
            rgba(255,255,255,0.04);

        display:flex;

        align-items:center;
        justify-content:center;

        padding:20px;

        color:#94a3b8;

        font-size:14px;

        text-align:center;
    }

    /* =========================
        ERROR
    ========================== */

    .invalid-feedback{

        color:#fca5a5;

        font-size:13px;

        margin-top:8px;
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
                #2563eb,
                #7c3aed
            );

        box-shadow:
            0 12px 30px rgba(124,58,237,0.22);
    }

    .submit-btn:hover{

        transform:translateY(-3px);

        box-shadow:
            0 18px 35px rgba(124,58,237,0.32);
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

                <label class="form-label">
                    CAPTCHA
                </label>

                <div class="captcha-wrapper">

                    Khu vực tích hợp Google reCAPTCHA
                    <br>
                    hoặc Cloudflare Turnstile

                </div>

                <!-- Ví dụ API -->
                <!--
                <div
                    class="g-recaptcha"
                    data-sitekey="YOUR_SITE_KEY">
                </div>
                -->

            </div>

            <!-- BUTTON -->
            <button
                type="submit"
                class="submit-btn"
            >
                Tạo tài khoản
            </button>

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

@endsection