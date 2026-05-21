<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Hồ sơ người dùng
    </title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins', sans-serif;
        }

        body{
            min-height:100vh;
            background:linear-gradient(135deg,#0f172a,#1e293b,#111827);
            color:white;
            overflow-x:hidden;
        }

        .bg{
            position:absolute;
            width:300px;
            height:300px;
            border-radius:50%;
            filter:blur(100px);
            opacity:0.4;
        }

        .bg1{
            background:#2563eb;
            top:-80px;
            left:-80px;
        }

        .bg2{
            background:#7c3aed;
            bottom:-80px;
            right:-80px;
        }

        .wrapper{
            position:relative;
            z-index:10;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:40px 20px;
        }

        .card-profile{
            width:100%;
            max-width:1200px;
            border-radius:30px;
            overflow:hidden;
            background:rgba(255,255,255,0.06);
            backdrop-filter:blur(18px);
            border:1px solid rgba(255,255,255,0.1);
            box-shadow:0 20px 60px rgba(0,0,0,0.5);
        }

        /* LEFT */

        .left{
            background:linear-gradient(180deg,#2563eb,#1d4ed8);
            padding:40px 25px;
            color:white;
            height:100%;
        }

        .avatar img{
            width:140px;
            height:140px;
            border-radius:50%;
            object-fit:cover;
            border:5px solid rgba(255,255,255,0.3);
        }

        .name{
            font-size:22px;
            font-weight:700;
            margin-top:10px;
        }

        .role{
            font-size:14px;
            opacity:0.9;
        }

        .nav-menu{
            margin-top:30px;
        }

        .nav-item{
            display:flex;
            gap:12px;
            align-items:center;
            padding:14px 16px;
            border-radius:14px;
            color:white;
            text-decoration:none;
            background:rgba(255,255,255,0.1);
            margin-bottom:12px;
            transition:0.3s;
            border:none;
            width:100%;
        }

        .nav-item:hover{
            transform:translateX(6px);
            background:rgba(255,255,255,0.2);
        }

        .danger{
            background:rgba(255,0,0,0.2);
        }

        .danger:hover{
            background:#dc2626;
        }

        /* RIGHT */

        .right{
            padding:50px;
        }

        .title{
            font-size:30px;
            font-weight:700;
            margin-bottom:25px;
        }

        label{
            font-size:14px;
            margin-bottom:6px;
        }

        .form-control{
            height:35px;
            border-radius:14px;
            background:rgba(255,255,255,0.08);
            border:none;
            color:white;
            margin-bottom:10px;
        }

        .form-control:focus{
            background:rgba(255,255,255,0.12);
            box-shadow:none;
            color:white;
        }

        .form-control:disabled{
            background:rgba(255,255,255,0.05);
            color:rgba(255,255,255,0.6);
        }

        /* OPTION COLOR */

        select.form-control option{
            color:black;
            background:white;
        }

        textarea.form-control{
            height:100px;
        }

        /* BUTTON */

        .btn-wrapper{
            display:flex;
            justify-content:center;
            margin-top:25px;
        }

        .btn-save{
            padding:12px 40px;
            border:none;
            border-radius:30px;
            background:linear-gradient(135deg,#3b82f6,#2563eb);
            color:white;
            font-weight:600;
            transition:0.3s;
        }

        .btn-save:hover{
            transform:translateY(-2px);
        }

        /* ALERT */

        .custom-alert{
            border:none;
            border-radius:16px;
            padding:14px 18px;
        }

        .alert-success{
            background:rgba(34,197,94,0.15);
            color:#4ade80;
        }

        .alert-danger{
            background:rgba(239,68,68,0.15);
            color:#f87171;
        }

        .btn-close{
            filter:invert(1);
        }

        /* REMOVE SPINNER */

        .no-spinner::-webkit-outer-spin-button,
        .no-spinner::-webkit-inner-spin-button{
            -webkit-appearance:none;
            margin:0;
        }

        .no-spinner{
            -moz-appearance:textfield;
        }

    </style>

</head>

<body>

<!-- BACKGROUND -->
<div class="bg bg1"></div>
<div class="bg bg2"></div>

<div class="wrapper">

    <div class="card-profile">

        <div class="row g-0">

            <!-- LEFT -->
            <div class="col-lg-4">

                <div class="left text-center">

                    <!-- AVATAR -->
                    <div class="avatar mb-3">

                        <img src="{{ Auth::user()->avatar_url
                            ? asset(Auth::user()->avatar_url)
                            : 'https://i.pinimg.com/736x/4d/5e/7c/4d5e7c77bb9bcbcd1b4d6e8c6e0bff6d.jpg' }}">

                    </div>

                    <!-- NAME -->
                    <h2 class="name">
                        {{ Auth::user()->username }} #{{ Auth::user()->id }}
                    </h2>

                    <!-- ROLE -->
                    <div class="role">

                        {{ Auth::user()->role_id == 1
                            ? 'Quản trị viên'
                            : 'Người dùng' }}

                    </div>

                    <!-- STATUS -->
                    <div class="role mt-1">

                        Trạng thái:

                        {{ Auth::user()->status
                            ? 'Đang hoạt động'
                            : 'Bị khoá' }}

                    </div>

                    <!-- MENU -->
                    <div class="nav-menu">

                        <a href="/" class="nav-item">

                            <i class="fa fa-home"></i>

                            Trang chủ

                        </a>

                        <a href="{{ route('change.password') }}"
                           class="nav-item">

                            <i class="fa fa-key"></i>

                            Đổi mật khẩu

                        </a>
						
						<!-- SUPPORT -->
						<a href="#"
						   class="nav-item">

							<i class="fa fa-headset"></i>

							Hỗ trợ người dùng

						</a>

                        <!-- DELETE -->
                        <button class="nav-item danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal">

                            <i class="fa fa-trash"></i>

                            Xoá tài khoản

                        </button>

                        <!-- LOGOUT -->
                        <form action="{{ route('logout') }}"
                              method="POST">

                            @csrf

                            <button class="nav-item">

                                <i class="fa fa-right-from-bracket"></i>

                                Đăng xuất

                            </button>

                        </form>

                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="col-lg-8">

                <div class="right">

                    <!-- TITLE -->
                    <div class="title">
                        Thông tin hồ sơ
                    </div>

                    <!-- SUCCESS -->
                    @if(session('success'))

                        <div class="alert alert-success alert-dismissible fade show custom-alert"
                             role="alert">

                            {{ session('success') }}

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert">
                            </button>

                        </div>

                    @endif

                    <!-- ERROR -->
                    @if($errors->any())

                        <div class="alert alert-danger alert-dismissible fade show custom-alert"
                             role="alert">

                            <ul class="mb-0">

                                @foreach($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert">
                            </button>

                        </div>

                    @endif

                    <!-- FORM -->
                    <form action="{{ route('profile.update') }}"
                          method="POST"
                          enctype="multipart/form-data">

                        @csrf

                        <!-- HIDDEN USERNAME -->
                        <input type="hidden"
                               name="username"
                               value="{{ Auth::user()->username }}">

                        <!-- AVATAR -->
                        <label class="form-label">
                            Ảnh đại diện
                        </label>

                        <input type="file"
                               name="avatar"
                               class="form-control">

                        <!-- NAME -->
                        <label class="form-label">
                            Họ và tên
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ Auth::user()->name }}"
                               class="form-control"
                               placeholder="Nhập họ và tên">

                        <!-- USERNAME + ID -->
                        <div class="row">

                            <!-- USERNAME -->
                            <div class="col-md-8">

                                <label class="form-label">
                                    Tên đăng nhập
                                </label>

                                <input type="text"
                                       class="form-control"
                                       value="{{ Auth::user()->username }}"
                                       disabled>

                            </div>

                            <!-- USER ID -->
                            <div class="col-md-4">

                                <label class="form-label">
                                    ID người dùng
                                </label>

                                <input type="number"
                                       name="user_id"
                                       class="form-control no-spinner"
                                       value="{{ Auth::user()->id }}"
                                       placeholder="ID">

                            </div>

                        </div>

                        <!-- EMAIL + PHONE -->
                        <div class="row">

                            <!-- EMAIL -->
                            <div class="col-md-6">

                                <label class="form-label">
                                    Email
                                </label>

								<input type="email"
									   name="email"
									   value="{{ Auth::user()->email }}"
									   class="form-control"
									   readonly
									   disabled>


                            </div>

                            <!-- PHONE -->
                            <div class="col-md-6">

                                <label class="form-label">
                                    Số điện thoại
                                </label>

                                <input type="text"
                                       name="phone"
                                       value="{{ Auth::user()->phone }}"
                                       class="form-control"
                                       placeholder="Nhập số điện thoại">

                            </div>

                        </div>

                        <!-- GENDER + DATE -->
                        <div class="row">

                            <!-- GENDER -->
                            <div class="col-md-6">

                                <label class="form-label">
                                    Giới tính
                                </label>

                                <select name="gender"
                                        class="form-control">

                                    <option value="">
                                        Chọn giới tính
                                    </option>

                                    <option value="male"
                                        {{ Auth::user()->gender == 'male'
                                            ? 'selected'
                                            : '' }}>
                                        Nam
                                    </option>

                                    <option value="female"
                                        {{ Auth::user()->gender == 'female'
                                            ? 'selected'
                                            : '' }}>
                                        Nữ
                                    </option>

                                    <option value="other"
                                        {{ Auth::user()->gender == 'other'
                                            ? 'selected'
                                            : '' }}>
                                        Khác
                                    </option>

                                </select>

                            </div>

                            <!-- DATE -->
                            <div class="col-md-6">

                                <label class="form-label">
                                    Ngày sinh
                                </label>

                                <input type="date"
                                       name="date_of_birth"
                                       value="{{ Auth::user()->date_of_birth
                                            ? \Carbon\Carbon::parse(Auth::user()->date_of_birth)->format('Y-m-d')
                                            : '' }}"
                                       class="form-control">

                            </div>

                        </div>

                        <!-- ADDRESS -->
                        <label class="form-label">
                            Địa chỉ
                        </label>

                        <textarea name="home_address"
                                  class="form-control"
                                  placeholder="Nhập địa chỉ">{{ Auth::user()->home_address }}</textarea>

                        <!-- BUTTON CENTER -->
                        <div class="btn-wrapper">

                            <button type="submit"
                                    class="btn-save">

                                Lưu thay đổi

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- DELETE MODAL -->
<div class="modal fade"
     id="deleteModal"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content bg-dark text-white rounded-4">

            <div class="modal-header border-0">

                <h5>
                    Xác nhận xoá tài khoản
                </h5>

            </div>

            <div class="modal-body">

                Bạn có chắc chắn muốn xoá tài khoản không?

                <br>

                <span class="text-danger">
                    Hành động này không thể hoàn tác.
                </span>

            </div>

            <div class="modal-footer border-0">

                <button class="btn btn-secondary"
                        data-bs-dismiss="modal">

                    Huỷ

                </button>

                <form action="{{ route('profile.delete') }}"
                      method="POST">

                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger">

                        Xoá tài khoản

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<!-- BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>