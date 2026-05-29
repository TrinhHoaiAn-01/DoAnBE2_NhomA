<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>

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
            overflow:hidden;
        }

        .bg-circle{
            position:absolute;
            border-radius:50%;
            filter:blur(90px);
            opacity:0.4;
            animation:float 7s ease-in-out infinite;
        }

        .bg1{
            width:350px;
            height:350px;
            background:#2563eb;
            top:-120px;
            left:-120px;
        }

        .bg2{
            width:300px;
            height:300px;
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

        .wrapper{
            position:relative;
            z-index:10;
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:20px;
        }

        .password-card{
            width:100%;
            max-width:520px;
            padding:45px;
            border-radius:30px;
            background:rgba(255,255,255,0.08);
            backdrop-filter:blur(15px);
            border:1px solid rgba(255,255,255,0.1);
            box-shadow:0 20px 50px rgba(0,0,0,0.4);
            color:white;
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

        .top-icon{
            width:90px;
            height:90px;
            border-radius:50%;
            background:linear-gradient(135deg,#3b82f6,#2563eb);
            display:flex;
            justify-content:center;
            align-items:center;
            margin:auto;
            margin-bottom:25px;
            font-size:35px;
            box-shadow:0 10px 30px rgba(59,130,246,0.4);
        }

        .title{
            text-align:center;
            font-size:32px;
            font-weight:700;
            margin-bottom:10px;
        }

        .sub-title{
            text-align:center;
            color:#cbd5e1;
            margin-bottom:35px;
        }

        .form-label{
            margin-bottom:10px;
            font-weight:500;
        }

        .input-group{
            margin-bottom:25px;
        }

        .input-group-text{
            background:rgba(255,255,255,0.08);
            border:none;
            color:white;
            border-radius:16px 0 0 16px;
            padding:0 18px;
        }

        .form-control{
            height:58px;
            background:rgba(255,255,255,0.08);
            border:none;
            color:white;
            border-radius:0 16px 16px 0;
        }

        .form-control:focus{
            background:rgba(255,255,255,0.12);
            color:white;
            box-shadow:none;
            border:1px solid #3b82f6;
        }

        .form-control::placeholder{
            color:#cbd5e1;
        }

        .change-btn{
            width:100%;
            border:none;
            padding:16px;
            border-radius:18px;
            background:linear-gradient(135deg,#3b82f6,#2563eb);
            color:white;
            font-size:16px;
            font-weight:600;
            transition:0.4s;
        }

        .change-btn:hover{
            transform:translateY(-3px);
            box-shadow:0 12px 30px rgba(59,130,246,0.4);
        }

        .back-btn{
            display:block;
            text-align:center;
            margin-top:20px;
            color:#cbd5e1;
            text-decoration:none;
            transition:0.3s;
        }

        .back-btn:hover{
            color:white;
        }

        .alert{
            border-radius:16px;
        }

    </style>

</head>
<body>

<div class="bg-circle bg1"></div>
<div class="bg-circle bg2"></div>

<div class="wrapper">

    <div class="password-card">

        <div class="top-icon">

            <i class="fa-solid fa-lock"></i>

        </div>

        <h1 class="title">
            Change Password
        </h1>

        <p class="sub-title">
            Update your account password securely
        </p>

        @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

        @endif

        @if($errors->any())

            <div class="alert alert-danger">

                @foreach($errors->all() as $error)

                    <div>{{ $error }}</div>

                @endforeach

            </div>

        @endif

        <form action="{{ route('password.update') }}"
              method="POST">

            @csrf

            <!-- CURRENT PASSWORD -->
            <label class="form-label">
                Current Password
            </label>

            <div class="input-group">

                <span class="input-group-text">
                    <i class="fa-solid fa-key"></i>
                </span>

                <input type="password"
                       name="current_password"
                       class="form-control"
                       placeholder="Enter current password">

            </div>

            <!-- NEW PASSWORD -->
            <label class="form-label">
                New Password
            </label>

            <div class="input-group">

                <span class="input-group-text">
                    <i class="fa-solid fa-lock"></i>
                </span>

                <input type="password"
                       name="new_password"
                       class="form-control"
                       placeholder="Enter new password">

            </div>

            <!-- CONFIRM PASSWORD -->
            <label class="form-label">
                Confirm Password
            </label>

            <div class="input-group">

                <span class="input-group-text">
                    <i class="fa-solid fa-shield-halved"></i>
                </span>

                <input type="password"
                       name="new_password_confirmation"
                       class="form-control"
                       placeholder="Confirm new password">

            </div>

            <button type="submit"
                    class="change-btn">

                <i class="fa-solid fa-floppy-disk"></i>
                Change Password

            </button>

        </form>

        @if(Auth::user()->role_id == 5)

			<a href="{{ route('profile.admin') }}" class="back-btn">
				<i class="fa-solid fa-arrow-left"></i>
				Back To Profile
			</a>

		@else

			<a href="{{ route('profile') }}" class="back-btn">
				<i class="fa-solid fa-arrow-left"></i>
				Back To Profile
			</a>

		@endif

    </div>

</div>

</body>
</html>