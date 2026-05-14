<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile User</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

    <!-- Google Font -->
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
            background:linear-gradient(135deg,#0f172a,#1e293b,#334155);
            overflow-x:hidden;
        }

        .bg-circle{
            position:absolute;
            border-radius:50%;
            filter:blur(90px);
            opacity:0.45;
            animation:float 7s ease-in-out infinite;
        }

        .bg1{
            width:350px;
            height:350px;
            background:#2563eb;
            top:-100px;
            left:-100px;
        }

        .bg2{
            width:320px;
            height:320px;
            background:#7c3aed;
            bottom:-100px;
            right:-100px;
            animation-delay:2s;
        }

        @keyframes float{
            0%{
                transform:translateY(0px);
            }
            50%{
                transform:translateY(25px);
            }
            100%{
                transform:translateY(0px);
            }
        }

        .profile-wrapper{
            position:relative;
            z-index:10;
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:40px 15px;
        }

        .profile-card{
            width:100%;
            max-width:1150px;
            background:rgba(255,255,255,0.08);
            border:1px solid rgba(255,255,255,0.1);
            backdrop-filter:blur(16px);
            border-radius:30px;
            overflow:hidden;
            box-shadow:0 20px 50px rgba(0,0,0,0.4);
            animation:showUp 1s ease;
        }

        @keyframes showUp{
            from{
                opacity:0;
                transform:translateY(40px);
            }
            to{
                opacity:1;
                transform:translateY(0);
            }
        }

        .left-panel{
            background:linear-gradient(180deg,#2563eb,#1d4ed8);
            color:white;
            padding:50px 30px;
            height:100%;
        }

        .avatar-box{
            text-align:center;
            margin-bottom:30px;
        }

        .avatar-box img{
            width:170px;
            height:170px;
            object-fit:cover;
            border-radius:50%;
            border:6px solid rgba(255,255,255,0.3);
            margin-bottom:20px;
        }
		
		.form-control.upload-input{
			border-radius:0 !important;
		}

        .upload-input{
			width:100%;
			height:58px;
			padding:0;
			border:none;
			border-radius:0;
			overflow:hidden;
			background:rgba(255,255,255,0.08);
			color:#e2e8f0;
			cursor:pointer;
			margin-bottom:25px;
		}

		/* Nút Choose File */
		.upload-input::file-selector-button{
			margin-left: -20px;
			width:30%;
			height:65px;
			padding:0 24px;
			border:none;
			border-radius:0;
			background:#f1f5f9;
			color:#0f172a;
			font-weight:600;
			cursor:pointer;
			transition:0.3s;
			margin-right:18px;
		}

		.upload-input::file-selector-button:hover{
			background:#dbeafe;
		}

		.upload-input:focus{
			outline:none;
			border:1px solid #3b82f6;
			box-shadow:none;
		}

        .user-name{
            font-size:28px;
            font-weight:700;
        }

        .user-role{
            margin-top:10px;
            font-size:15px;
            opacity:0.9;
            letter-spacing:1px;
        }

        .menu-list{
            margin-top:40px;
        }

        .menu-item{
            width:100%;
            display:flex;
            align-items:center;
            gap:15px;
            padding:16px 20px;
            border-radius:16px;
            margin-bottom:15px;
            text-decoration:none;
            color:white;
            background:rgba(255,255,255,0.08);
            transition:0.3s;
            border:none;
        }

        .menu-item:hover{
            transform:translateX(8px);
            background:rgba(255,255,255,0.15);
            color:white;
        }

        .logout{
            background:rgba(255,0,0,0.15);
        }

        .logout:hover{
            background:#dc2626;
        }

        .delete{
            cursor:pointer;
        }

        .delete:hover{
            background:#991b1b;
        }

        .right-panel{
            padding:50px;
            color:white;
        }

        .section-title{
            font-size:32px;
            font-weight:700;
            margin-bottom:35px;
        }

        .form-label{
            margin-bottom:10px;
            font-weight:500;
        }

        .form-control{
            height:58px;
            border:none;
            border-radius:16px;
            background:rgba(255,255,255,0.08);
            color:white;
            padding-left:20px;
            margin-bottom:25px;
        }

        .form-control:focus{
            background:rgba(255,255,255,0.12);
            border:1px solid #3b82f6;
            box-shadow:none;
            color:white;
        }

        .form-control::placeholder{
            color:#cbd5e1;
        }

        .save-btn{
            border:none;
            padding:15px 40px;
            border-radius:50px;
            background:linear-gradient(135deg,#3b82f6,#2563eb);
            color:white;
            font-weight:600;
            transition:0.4s;
        }

        .save-btn:hover{
            transform:scale(1.05);
            box-shadow:0 10px 25px rgba(59,130,246,0.4);
        }

        .alert{
            border-radius:15px;
        }

        .modal-content{
            background:#111827;
            color:white;
            border-radius:25px;
        }

        .btn-danger{
            border-radius:30px;
            padding:10px 25px;
        }

        .btn-secondary{
            border-radius:30px;
            padding:10px 25px;
        }

        @media(max-width:991px){

            .right-panel{
                padding:35px 25px;
            }

        }

    </style>

</head>
<body>

<div class="bg-circle bg1"></div>
<div class="bg-circle bg2"></div>

<div class="profile-wrapper">

    <div class="profile-card">

        <div class="row g-0">

            <!-- LEFT -->
            <div class="col-lg-4">

                <div class="left-panel">

                    <div class="avatar-box">

                        <img src="{{ Auth::user()->avatar_url 
                            ? asset(Auth::user()->avatar_url) 
                            : 'https://i.pinimg.com/736x/4d/5e/7c/4d5e7c77bb9bcbcd1b4d6e8c6e0bff6d.jpg' }}">

                        <h2 class="user-name">
                            {{ Auth::user()->name }}
                        </h2>

                        <p class="user-role">

                            @if(Auth::user()->role_id == 1)

                                Admin Account

                            @else

                                User Account

                            @endif

                        </p>

                    </div>

                    <!-- MENU -->
                    <div class="menu-list">

                        <a href="/"
                           class="menu-item">

                            <i class="fa-solid fa-house"></i>
                            Trang Chủ

                        </a>

                        <a href="{{ route('change.password') }}"
                           class="menu-item">

                            <i class="fa-solid fa-key"></i>
                            Thay đổi Password

                        </a>

                        <!-- DELETE -->
                        <button type="button"
                                class="menu-item delete"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal">

                            <i class="fa-solid fa-trash"></i>
                            Xoá Tài khoản

                        </button>

                        <!-- LOGOUT -->
                        <form action="{{ route('logout') }}"
                              method="POST">

                            @csrf

                            <button type="submit"
                                    class="menu-item logout">

                                <i class="fa-solid fa-right-from-bracket"></i>
                                Đăng xuất

                            </button>

                        </form>

                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="col-lg-8">

                <div class="right-panel">

                    <h2 class="section-title">
                        Edit Profile
                    </h2>

                    @if(session('success'))

                        <div class="alert alert-success">

                            {{ session('success') }}

                        </div>

                    @endif

                    <form action="{{ route('profile.update') }}"
                          method="POST"
                          enctype="multipart/form-data">

                        @csrf

                        <div class="row">

                            <div class="col-12">

                                <label class="form-label">
                                    Avatar
                                </label>

                                <input type="file"
                                       name="avatar"
                                       class="form-control upload-input">

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Full Name
                                </label>

                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       value="{{ Auth::user()->name }}"
                                       placeholder="Enter full name">

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Email
                                </label>

                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       value="{{ Auth::user()->email }}"
                                       placeholder="Enter email">

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Phone Number
                                </label>

                                <input type="text"
                                       name="phone"
                                       class="form-control"
                                       value="{{ Auth::user()->phone }}"
                                       placeholder="Enter phone number">

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    User ID
                                </label>

                                <input type="text"
                                       class="form-control"
                                       value="#{{ Auth::user()->id }}"
                                       disabled>

                            </div>

                        </div>

                        <button type="submit"
                                class="save-btn mt-2">

                            <i class="fa-solid fa-floppy-disk"></i>
                            Save Changes

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- DELETE MODAL -->
<div class="modal fade"
     id="deleteModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header border-0">

                <h5 class="modal-title">

                    <i class="fa-solid fa-triangle-exclamation text-danger"></i>
                    Xác nhận xoá tài khoản

                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>

            </div>

            <div class="modal-body">

                Bạn có chắc muốn xoá tài khoản?

                <br><br>

                <span class="text-danger">
                    Hành động này không thể khôi phục.
                </span>

            </div>

            <div class="modal-footer border-0">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                    Huỷ

                </button>

                <form action="{{ route('profile.delete') }}"
                      method="POST">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="btn btn-danger">

                        Xoá tài khoản

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>