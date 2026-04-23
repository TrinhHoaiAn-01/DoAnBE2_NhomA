<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>

    <h2>Register</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <label>Name</label><br>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name') <p style="color:red">{{ $message }}</p> @enderror
        </div>

        <!-- Email -->
        <div>
            <label>Email</label><br>
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email') <p style="color:red">{{ $message }}</p> @enderror
        </div>

        <!-- Phone -->
        <div>
            <label>Phone</label><br>
            <input type="text" name="phone" value="{{ old('phone') }}">
            @error('phone') <p style="color:red">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div>
            <label>Password</label><br>
            <input type="password" name="password">
            @error('password') <p style="color:red">{{ $message }}</p> @enderror
        </div>

        <!-- Role (role_id) -->
        <div>
            <label>Role</label><br>
            <select name="role_id">
                <option value="1" {{ old('role_id') == 1 ? 'selected' : '' }}>User</option>
                <option value="2" {{ old('role_id') == 2 ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role_id') <p style="color:red">{{ $message }}</p> @enderror
        </div>

        <br>
        <button type="submit">Register</button>
    </form>

</body>
</html>