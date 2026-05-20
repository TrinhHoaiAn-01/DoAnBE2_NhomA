<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile User</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

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
        }

        .bg1{width:350px;height:350px;background:#2563eb;top:-100px;left:-100px;}
        .bg2{width:320px;height:320px;background:#7c3aed;bottom:-100px;right:-100px;}

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
        }

        .left-panel{
            background:linear-gradient(180deg,#2563eb,#1d4ed8);
            color:white;
            padding:50px 30px;
        }

        .avatar-box{text-align:center;margin-bottom:30px;}
        .avatar-box img{
            width:170px;height:170px;
            object-fit:cover;
            border-radius:50%;
            border:6px solid rgba(255,255,255,0.3);
            margin-bottom:20px;
        }

        .user-name{font-size:28px;font-weight:700;}
        .user-role{margin-top:10px;font-size:15px;opacity:0.9;}

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
        }

        .right-panel{padding:50px;color:white;}
        .section-title{font-size:32px;font-weight:700;margin-bottom:35px;}

        .form-control{
            height:58px;
            border:none;
            border-radius:16px;
            background:rgba(255,255,255,0.08);
            color:white;
            padding-left:20px;
            margin-bottom:20px;
        }

        .save-btn{
            border:none;
            padding:15px 40px;
            border-radius:50px;
            background:linear-gradient(135deg,#3b82f6,#2563eb);
            color:white;
            font-weight:600;
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

                        <img src="{{ Auth::user()->avatar_url ? asset(Auth::user()->avatar_url) : 'https://i.pinimg.com/736x/4d/5e/7c/4d5e7c77bb9bcbcd1b4d6e8c6e0bff6d.jpg' }}">

                        <h2 class="user-name">{{ Auth::user()->name }}</h2>

                        <p class="user-role">
                            {{ Auth::user()->role_id == 1 ? 'Admin' : 'User' }}
                        </p>

                        <p class="user-role">
                            Status:
                            {{ Auth::user()->status ? 'Active' : 'Locked' }}
                        </p>

                    </div>

                    <div class="menu-list">

                        <a href="/" class="menu-item">
                            <i class="fa-solid fa-house"></i> Home
                        </a>

                        <a href="{{ route('change.password') }}" class="menu-item">
                            <i class="fa-solid fa-key"></i> Change Password
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="menu-item">
                                <i class="fa-solid fa-right-from-bracket"></i> Logout
                            </button>
                        </form>

                    </div>

                </div>
            </div>

            <!-- RIGHT -->
            <div class="col-lg-8">

                <div class="right-panel">

                    <h2 class="section-title">Edit Profile</h2>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- avatar -->
                        <label>Avatar</label>
                        <input type="file" name="avatar" class="form-control">

                        <!-- name -->
                        <label>Name</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}" class="form-control">

                        <!-- username -->
                        <label>Username</label>
                        <input type="text" name="username" value="{{ Auth::user()->username }}" class="form-control">

                        <!-- email -->
                        <label>Email</label>
                        <input type="email" name="email" value="{{ Auth::user()->email }}" class="form-control">

                        <!-- phone -->
                        <label>Phone</label>
                        <input type="text" name="phone" value="{{ Auth::user()->phone }}" class="form-control">

                        <!-- gender -->
                        <label>Gender</label>
                        <select name="gender" class="form-control">
                            <option value="">Select</option>
                            <option value="male" {{ Auth::user()->gender=='male'?'selected':'' }}>Male</option>
                            <option value="female" {{ Auth::user()->gender=='female'?'selected':'' }}>Female</option>
                            <option value="other" {{ Auth::user()->gender=='other'?'selected':'' }}>Other</option>
                        </select>

                        <!-- dob -->
                        <label>Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ Auth::user()->date_of_birth }}" class="form-control">

                        <!-- address -->
                        <label>Address</label>
                        <textarea name="home_address" class="form-control">{{ Auth::user()->home_address }}</textarea>

                        <!-- button -->
                        <button class="save-btn mt-3">
                            Save Changes
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>