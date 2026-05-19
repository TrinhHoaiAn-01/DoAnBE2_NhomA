@extends('layouts.app', ['title' => 'Đăng nhập NeoMart'])

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
        LOGIN WRAPPER
    ========================== */

    .login-wrapper{

        min-height:100vh;

        display:flex;

        justify-content:center;
        align-items:center;

        padding:30px 20px;
    }

    /* =========================
        LOGIN CARD
    ========================== */

    .login-card{

        width:100%;
        max-width:540px;

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
		
		margin-top: -50px;
    }

    /* =========================
        LOGIN IMAGE
    ========================== */

    .login-image{

        width:100%;

        height:230px;

        display:flex;

        justify-content:center;
        align-items:center;

        overflow:hidden;
		
		margin-top:-30px;
		
        margin-bottom:-20px;
    }

    .login-image img{

        width:340px;

        max-width:100%;

        height:auto;

        object-fit:contain;

        display:block;

        filter:
            drop-shadow(
                0 20px 45px rgba(37,99,235,0.35)
            );

        transition:0.3s;
    }

    .login-image img:hover{

        transform:scale(1.03);
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
        REMEMBER
    ========================== */

    .remember{

        display:flex;

        align-items:center;
        justify-content:space-between;

        margin-bottom:24px;

        color:#cbd5e1;

        font-size:14px;
    }

    .remember-left{

        display:flex;

        align-items:center;

        gap:10px;
    }

    .remember-left input{

        accent-color:#2563eb;
    }

    .remember a{

        color:#93c5fd;

        text-decoration:none;

        transition:0.3s;
    }

    .remember a:hover{
        color:white;
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
        DIVIDER
    ========================== */

    .divider{

        display:flex;

        align-items:center;

        gap:14px;

        margin:30px 0;
    }

    .divider::before,
    .divider::after{

        content:'';

        flex:1;

        height:1px;

        background:
            rgba(255,255,255,0.08);
    }

    .divider span{

        color:#94a3b8;

        font-size:13px;
    }

    /* =========================
        SOCIAL LOGIN
    ========================== */

    .social-login{

        display:flex;

        flex-direction:column;

        gap:14px;
    }

    .social-btn{

        width:100%;

        display:flex;

        align-items:center;
        justify-content:center;

        gap:14px;

        padding:16px;

        border-radius:20px;

        text-decoration:none;

        color:white;

        font-size:14px;
        font-weight:600;

        transition:0.35s;

        border:
            1px solid rgba(255,255,255,0.08);

        background:
            rgba(255,255,255,0.04);
    }

    .social-btn:hover{

        transform:translateY(-2px);

        background:
            rgba(255,255,255,0.08);

        border-color:
            rgba(96,165,250,0.18);
    }

    .social-btn img{

        width:22px;
        height:22px;
    }

    .social-github img{

        background:white;

        border-radius:50%;
    }

    /* =========================
        REGISTER
    ========================== */

    .register-link{

        text-align:center;

        margin-top:28px;

        font-size:14px;

        color:#cbd5e1;
    }

    .register-link a{

        color:#93c5fd;

        text-decoration:none;

        font-weight:600;

        transition:0.3s;
    }

    .register-link a:hover{
        color:white;
    }

</style>

<!-- BACKGROUND -->
<div class="bg-light one"></div>
<div class="bg-light two"></div>

<!-- LOGIN -->
<div class="login-wrapper">

    <div class="login-card">

        <!-- LOGO -->
        <div class="login-image">

            <img
                src="{{ asset('images/logo.png') }}"
                alt="NeoMart Logo"
            >

        </div>

        <!-- FORM -->
        <form
            action="{{ route('login.submit') }}"
            method="POST"
        >

            @csrf

            <!-- EMAIL -->
            <div class="form-group">

                <label class="form-label">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Nhập email..."
                    autocomplete="off"
                    required
                >

            </div>

            <!-- PASSWORD -->
            <div class="form-group">

                <label class="form-label">
                    Mật khẩu
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Nhập mật khẩu..."
                    autocomplete="off"
                    required
                >

            </div>

            <!-- REMEMBER -->
            <div class="remember">

                <div class="remember-left">

                    <input
                        type="checkbox"
                        name="remember"
                    >

                    <span>
                        Ghi nhớ đăng nhập
                    </span>

                </div>

                <a href="{{ route('password.request') }}">
                    Quên mật khẩu?
                </a>

            </div>

            <!-- LOGIN BUTTON -->
            <button
                type="submit"
                class="submit-btn"
            >
                Đăng nhập
            </button>

            <!-- DIVIDER -->
            <div class="divider">

                <span>
                    Hoặc tiếp tục với
                </span>

            </div>

            <!-- SOCIAL -->
            <div class="social-login">

                <!-- GOOGLE -->
                <a
                    href="#"
                    class="social-btn"
                >

                    <img
                        src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg"
                        alt="Google"
                    >

                    <span>
                        Đăng nhập với Google
                    </span>

                </a>

                <!-- GITHUB -->
                <a
                    href="#"
                    class="social-btn social-github"
                >

                    <img
                        src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/github/github-original.svg"
                        alt="Github"
                    >

                    <span>
                        Đăng nhập với Github
                    </span>

                </a>

            </div>

            <!-- REGISTER -->
            <div class="register-link">

                Chưa có tài khoản?

                <a href="{{ route('register') }}">
                    Đăng ký ngay
                </a>

            </div>

        </form>

    </div>

</div>

@endsection