@extends('layouts.app', [
    'title' => 'Quên mật khẩu',
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
        WRAPPER
    ========================== */

    .forgot-wrapper{

        min-height:100vh;

        display:flex;

        justify-content:center;
        align-items:center;

        padding:40px 20px;
    }

    /* =========================
        CARD
    ========================== */

    .forgot-card{

        width:100%;
        max-width:520px;

        padding:45px;

        border-radius:32px;

        background:
            rgba(255,255,255,0.06);

        backdrop-filter:blur(18px);

        border:
            1px solid rgba(255,255,255,0.08);

        box-shadow:
            0 10px 40px rgba(0,0,0,0.45);

        position:relative;

        overflow:hidden;

        margin-top:-50px;
    }

    .forgot-card::before{

        content:'';

        position:absolute;

        width:240px;
        height:240px;

        border-radius:50%;

        background:
            rgba(255,255,255,0.03);

        top:-120px;
        right:-120px;
    }

    /* =========================
        TEXT
    ========================== */

    .main-title{

        text-align:center;

        font-size:38px;

        font-weight:800;

        margin-bottom:14px;
    }

    .sub-title{

        text-align:center;

        color:#94a3b8;

        font-size:14px;

        line-height:1.7;

        margin-bottom:34px;
    }

    /* =========================
        ALERT
    ========================== */

    .alert{

        border:none;

        border-radius:18px;

        padding:14px 16px;

        margin-bottom:22px;

        font-size:14px;
    }

    .alert-success{

        background:
            rgba(34,197,94,0.15);

        color:#bbf7d0;
    }

    .alert-danger{

        background:
            rgba(239,68,68,0.15);

        color:#fecaca;
    }

    /* =========================
        FORM
    ========================== */

    .form-group{
        margin-bottom:24px;
    }

    .form-label{

        display:block;

        margin-bottom:10px;

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

        caret-color:white;

        outline:none;
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
    }

    /* =========================
        CAPTCHA BOX
    ========================== */

    .captcha-box{

        width:100%;

        height:80px;

        border-radius:20px;

        border:
            1px dashed rgba(255,255,255,0.14);

        background:
            rgba(255,255,255,0.04);

        display:flex;

        align-items:center;
        justify-content:center;

        color:#94a3b8;

        font-size:14px;

        text-align:center;

        padding:20px;
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
        LINKS
    ========================== */

    .links{

        text-align:center;

        margin-top:24px;

        font-size:14px;
    }

    .links span{
        color:#cbd5e1;
    }

    .links a{

        color:#93c5fd;

        text-decoration:none;

        font-weight:600;
    }

    .links a:hover{
        color:white;
    }

    /* =========================
        RESPONSIVE
    ========================== */

    @media(max-width:768px){

        .forgot-card{

            padding:32px 24px;
        }

        .main-title{
            font-size:30px;
        }
    }

</style>

<!-- BACKGROUND -->
<div class="bg-light one"></div>
<div class="bg-light two"></div>

<!-- FORGOT PASSWORD -->
<div class="forgot-wrapper">

    <div class="forgot-card">

        <!-- TITLE -->
        <h1 class="main-title">
            Quên mật khẩu
        </h1>

        <p class="sub-title">
            Nhập email để xác minh tài khoản và tiếp tục khôi phục mật khẩu
        </p>

        {{-- SUCCESS --}}
        @if(session('success'))

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

        <!-- FORM -->
        <form
            method="POST"
            action="#"
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

            <!-- CAPTCHA -->
            <div class="form-group">

                <label class="form-label">
                    CAPTCHA
                </label>

                <div class="captcha-box">

                    Khu vực hiển thị CAPTCHA API
                    <br>
                    (Google reCAPTCHA / Cloudflare Turnstile)

                </div>

            </div>

            <!-- BUTTON -->
            <button class="submit-btn">

                Xác minh tài khoản

            </button>

            <!-- LINKS -->
            <div class="links">

                <span>
                    Nhớ mật khẩu rồi?
                </span>

                <a href="{{ route('login') }}">
                    Đăng nhập
                </a>

            </div>

        </form>

    </div>

</div>

@endsection