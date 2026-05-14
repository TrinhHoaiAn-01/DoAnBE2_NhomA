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

        overflow-x:hidden;

        font-family:'Segoe UI',sans-serif;

        background:
            radial-gradient(circle at top left, rgba(37,99,235,0.18), transparent 25%),
            radial-gradient(circle at bottom right, rgba(124,58,237,0.18), transparent 25%),
            linear-gradient(
                135deg,
                #020617,
                #071126,
                #020617
            );

        color:white;

        position:relative;
    }

    /* =========================
        HIDE DEFAULT NAVBAR
    ========================== */

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

        opacity:0.45;
    }

    .bg-light.one{

        width:260px;
        height:260px;

        background:#2563eb;

        top:-80px;
        left:-80px;
    }

    .bg-light.two{

        width:320px;
        height:320px;

        background:#7c3aed;

        right:-120px;
        bottom:-120px;
    }

    /* =========================
        NAVBAR
    ========================== */

    .custom-navbar{

        width:88%;
        max-width:1450px;

        margin:32px auto 0;

        padding:16px 22px;

        border-radius:24px;

        display:flex;

        align-items:center;
        justify-content:space-between;

        background:
            rgba(5,10,28,0.75);

        backdrop-filter:blur(18px);

        border:
            1px solid rgba(96,165,250,0.16);

        box-shadow:
            0 0 25px rgba(37,99,235,0.12);
    }

    .logo{

        font-size:26px;

        font-weight:800;

        letter-spacing:1px;

        color:white;

        text-transform:uppercase;
    }

    /* =========================
        MENU
    ========================== */

    .nav-menu{

        display:flex;

        align-items:center;

        gap:10px;
    }

    .nav-item-custom{

        text-decoration:none;

        color:#dbeafe;

        font-size:14px;
        font-weight:600;

        padding:13px 20px;

        border-radius:16px;

        transition:0.3s;

        position:relative;
    }

    .nav-item-custom:hover{

        color:white;

        background:
            rgba(255,255,255,0.05);
    }

    .nav-item-custom.active{

        color:white;

        background:
            linear-gradient(
                135deg,
                rgba(37,99,235,0.24),
                rgba(124,58,237,0.18)
            );

        border:
            1px solid rgba(96,165,250,0.2);
    }

    /* =========================
        AUTH BUTTON
    ========================== */

    .nav-auth{

        display:flex;

        align-items:center;

        gap:12px;
    }

    .btn-auth{

        text-decoration:none;

        font-size:14px;
        font-weight:700;

        padding:13px 22px;

        border-radius:16px;

        transition:0.3s;
    }

    .btn-login{

        color:white;

        border:
            1px solid rgba(255,255,255,0.08);

        background:
            rgba(255,255,255,0.04);
    }

    .btn-register{

        color:white;

        background:
            linear-gradient(
                135deg,
                #2563eb,
                #7c3aed
            );
    }

    .btn-auth:hover{

        transform:translateY(-2px);

        opacity:0.9;
    }

    /* =========================
        LOGIN WRAPPER
    ========================== */

    .login-wrapper{

        min-height:
            calc(100vh - 130px);

        display:flex;

        justify-content:center;
        align-items:center;

        padding:40px 20px;
    }

    /* =========================
        LOGIN CARD
    ========================== */

    .login-card{

        width:100%;
        max-width:500px;

        padding:42px;

        border-radius:28px;

        background:
            rgba(255,255,255,0.06);

        backdrop-filter:blur(18px);

        border:
            1px solid rgba(255,255,255,0.08);

        box-shadow:
            0 10px 40px rgba(0,0,0,0.4);

        position:relative;

        overflow:hidden;
    }

    .mini-title{

        text-align:center;

        text-transform:uppercase;

        letter-spacing:3px;

        color:#93c5fd;

        font-size:11px;

        margin-bottom:10px;
    }

    .main-title{

        text-align:center;

        font-size:36px;

        font-weight:800;

        margin-bottom:14px;
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

        padding:15px 18px;

        border-radius:16px;

        border:
            1px solid rgba(255,255,255,0.08);

        background:
            rgba(255,255,255,0.05);

        color:white;

        font-size:14px;

        transition:0.3s;
    }

    .form-control::placeholder{
        color:#94a3b8;
    }

    .form-control:focus{

        outline:none;

        border-color:#3b82f6;

        background:
            rgba(255,255,255,0.08);

        box-shadow:
            0 0 0 4px rgba(37,99,235,0.15);
    }

    /* REMEMBER */

    .remember{

        display:flex;

        align-items:center;

        gap:10px;

        margin-bottom:26px;

        color:#cbd5e1;

        font-size:14px;
    }

    /* BUTTON */

    .submit-btn{

        width:100%;

        border:none;

        padding:16px;

        border-radius:16px;

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
            0 10px 25px rgba(124,58,237,0.22);
    }

    .submit-btn:hover{

        transform:translateY(-2px);

        box-shadow:
            0 14px 30px rgba(124,58,237,0.3);
    }

    /* SOCIAL */

    .social-login{

        display:flex;

        flex-direction:column;

        gap:14px;

        margin-top:18px;
    }

    .social-btn{

        width:100%;

        padding:14px;

        border-radius:16px;

        border:
            1px solid rgba(255,255,255,0.08);

        background:
            rgba(255,255,255,0.04);

        color:white;

        font-size:14px;
        font-weight:600;

        cursor:pointer;

        transition:0.3s;
    }

    .social-btn:hover{

        transform:translateY(-2px);

        background:
            rgba(255,255,255,0.08);
    }

    /* LINKS */

    .links{

        text-align:center;

        margin-top:20px;

        font-size:14px;
    }

    .links a{

        color:#93c5fd;

        text-decoration:none;
    }

    .links span{
        color:#cbd5e1;
    }

</style>

<!-- BACKGROUND -->
<div class="bg-light one"></div>
<div class="bg-light two"></div>

<!-- NAVBAR -->
<div class="custom-navbar">

    <!-- LOGO -->
    <div class="logo">
        NEOMART
    </div>

    <!-- MENU -->
    <div class="nav-menu">

        <a href="#" class="nav-item-custom active">
            Trang chủ
        </a>

        <a href="#" class="nav-item-custom">
            Sản phẩm
        </a>

        <a href="#" class="nav-item-custom">
            Giỏ hàng
        </a>

        <a href="#" class="nav-item-custom">
            Danh mục
        </a>

        <a href="#" class="nav-item-custom">
            Liên hệ
        </a>

    </div>


</div>

<!-- LOGIN -->
<div class="login-wrapper">

    <div class="login-card">

        <!-- TITLE -->
        <div class="mini-title">
            Tài khoản người dùng
        </div>

        <h1 class="main-title">
            Đăng nhập
        </h1>

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
                    required
                >

            </div>

            <!-- REMEMBER -->
            <div class="remember">

                <input
                    type="checkbox"
                    name="remember"
                >

                <span>
                    Ghi nhớ đăng nhập
                </span>

            </div>

            <!-- BUTTON -->
            <button
                type="submit"
                class="submit-btn"
            >
                Đăng nhập
            </button>


            <!-- LINKS -->
            <div class="links">

                <a href="{{ route('password.request') }}">
                    Quên mật khẩu?
                </a>

            </div>

            <div class="links">

                <span>
                    Chưa có tài khoản?
                </span>

                <a href="{{ route('register') }}">
                    Đăng ký ngay
                </a>

            </div>

        </form>

    </div>

</div>

@endsection