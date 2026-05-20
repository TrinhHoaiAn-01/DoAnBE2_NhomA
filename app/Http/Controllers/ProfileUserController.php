<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class ProfileUserController extends Controller
{
    // =========================
    // PROFILE PAGE
    // =========================
    public function index()
    {
        return view('user.profile-user');
    }

    // =========================
    // UPDATE PROFILE
    // =========================
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username,' . $user->id
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20'
            ],

            'home_address' => [
                'nullable',
                'string',
                'max:1000'
            ],

            'gender' => [
                'nullable',
                'in:male,female,other'
            ],

            'date_of_birth' => [
                'nullable',
                'date'
            ],

            'avatar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],
        ]);

        // =========================
        // HANDLE AVATAR UPLOAD
        // =========================
        if ($request->hasFile('avatar')) {

            // delete old avatar if exists
            if ($user->avatar_url && File::exists(public_path($user->avatar_url))) {
                File::delete(public_path($user->avatar_url));
            }

            $file = $request->file('avatar');

            $filename = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('uploads/avatar'), $filename);

            $user->avatar_url = 'uploads/avatar/' . $filename;
        }

        // =========================
        // UPDATE DATA
        // =========================
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->home_address = $request->home_address;
        $user->gender = $request->gender;
        $user->date_of_birth = $request->date_of_birth;

        $user->save();

        return back()->with('success', 'Cập nhật hồ sơ thành công!');
    }

    // =========================
    // SHOW CHANGE PASSWORD
    // =========================
    public function showChangePassword()
    {
        return view('user.change-password');
    }

    // =========================
    // CHANGE PASSWORD
    // =========================
    public function changePassword(Request $request)
    {
        $request->validate([

            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {

            return back()->withErrors([
                'current_password' => 'Mật khẩu hiện tại không đúng!'
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    // =========================
    // DELETE ACCOUNT
    // =========================
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        // logout first
        Auth::logout();

        // delete avatar file
        if ($user->avatar_url && File::exists(public_path($user->avatar_url))) {
            File::delete(public_path($user->avatar_url));
        }

        // delete user
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Tài khoản đã được xoá!');
    }
}