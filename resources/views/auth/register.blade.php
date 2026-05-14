@extends('layouts.app', ['title' => 'Đăng ký NeoMart'])

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
        REGISTER WRAPPER
    ========================== */

    .register-wrapper{

        min-height:
            calc(100vh - 130px);

        display:flex;

        justify-content:center;
        align-items:center;

        padding:40px 20px;
    }

    /* =========================
        REGISTER CARD
    ========================== */

    .register-card{

        width:100%;
        max-width:760px;

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

        margin-bottom:12px;
    }

    .sub-title{

        text-align:center;

        color:#cbd5e1;

        font-size:14px;

        margin-bottom:34px;
    }

    /* =========================
        FORM
    ========================== */

    .form-grid{

        display:grid;

        grid-template-columns:
            repeat(2,1fr);

        gap:20px;
    }

    .full-width{
        grid-column:1/-1;
    }

    .form-group{
        margin-bottom:4px;
    }

    .form-label{

        display:block;

        margin-bottom:9px;

        color:#f8fafc;

        font-size:14px;

        font-weight:600;
    }

    .form-control,
    .form-select{

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

    .form-select option{
        background:#0f172a;
    }

    .form-control::placeholder{
        color:#94a3b8;
    }

    .form-control:focus,
    .form-select:focus{

        outline:none;

        border-color:#3b82f6;

        background:
            rgba(255,255,255,0.08);

        box-shadow:
            0 0 0 4px rgba(37,99,235,0.15);
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

        padding:16px;

        border-radius:16px;

        color:white;

        font-size:15px;
        font-weight:700;

        cursor:pointer;

        margin-top:28px;

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

    /* =========================
        LINKS
    ========================== */

    .links{

        text-align:center;

        margin-top:22px;

        font-size:14px;
    }

    .links span{
        color:#cbd5e1;
    }

    .links a{

        color:#93c5fd;

        text-decoration:none;
    }

    /* =========================
        RESPONSIVE
    ========================== */

    @media(max-width:768px){

        .custom-navbar{

            flex-direction:column;

            gap:18px;
        }

        .nav-menu{
            flex-wrap:wrap;
            justify-content:center;
        }

        .register-card{
            padding:32px 22px;
        }

        .form-grid{
            grid-template-columns:1fr;
        }

        .main-title{
            font-size:30px;
        }
    }

</style>

<!-- BACKGROUND -->
<div class="bg-light one"></div>
<div class="bg-light two"></div>

<!-- NAVBAR -->
<div class="custom-navbar">

    <div class="logo">
        NEOMART
    </div>

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

<!-- REGISTER -->
<div class="register-wrapper">

    <div class="register-card">

        <div class="mini-title">
            Tài khoản người dùng
        </div>

        <h1 class="main-title">
            Đăng ký tài khoản
        </h1>

        <p class="sub-title">
            Tạo tài khoản để mua sắm và quản lý thông tin cá nhân
        </p>

        <!-- FORM -->
        <form
            method="POST"
            action="{{ route('register.submit') }}"
        >

            @csrf

            <div class="form-grid">

                <!-- NAME -->
                <div class="form-group">

                    <label class="form-label">
                        Họ tên
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="Nhập họ tên..."
                        required
                    >

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <!-- PHONE -->
                <div class="form-group">

                    <label class="form-label">
                        Số điện thoại
                    </label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        class="form-control @error('phone') is-invalid @enderror"
                        placeholder="Nhập số điện thoại..."
                    >

                    @error('phone')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <!-- EMAIL -->
                <div class="form-group full-width">

                    <label class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="Nhập email..."
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
                        required
                    >

                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <!-- CONFIRM -->
                <div class="form-group">

                    <label class="form-label">
                        Nhập lại mật khẩu
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Nhập lại mật khẩu..."
                        required
                    >

                </div>

                <!-- ROLE -->
                <div class="form-group full-width">

                    <label class="form-label">
                        Vai trò
                    </label>

                    <select
                        name="role_id"
                        class="form-select @error('role_id') is-invalid @enderror"
                    >

                        <option value="1">
                            Admin
                        </option>

                        <option value="2" selected>
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

            <!-- BUTTON -->
            <button
                type="submit"
                class="submit-btn"
            >
                Tạo tài khoản
            </button>

            <!-- LINKS -->
            <div class="links">

                <span>
                    Đã có tài khoản?
                </span>

                <a href="{{ route('login') }}">
                    Đăng nhập ngay
                </a>

            </div>

        </form>

    </div>

</div>

@endsection