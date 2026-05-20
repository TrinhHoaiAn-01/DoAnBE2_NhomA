<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProfileUserController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([

            'username' => [
                'required',
                'string',
                'max:255'
            ],

            'user_id' => [
                'required',
                'integer',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id
            ],

            'phone' => 'nullable|string|max:20',
            'home_address' => 'nullable|string',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // =========================
        // CHECK TRÙNG USERNAME
        // =========================
        $checkUsername = DB::table('users')
            ->where('username', $request->username)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($checkUsername) {
            return back()->withErrors([
                'username' => 'Tên đăng nhập đã tồn tại!'
            ]);
        }

        // =========================
        // CHECK TRÙNG ID (IMPORTANT)
        // =========================
        $checkId = DB::table('users')
            ->where('id', $request->user_id)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($checkId) {
            return back()->withErrors([
                'user_id' => 'ID này đã bị trùng!'
            ]);
        }

        // =========================
        // UPDATE AVATAR
        // =========================
        if ($request->hasFile('avatar')) {

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
        $user->username = $request->username;

        $user->id = $request->user_id; 

        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->home_address = $request->home_address;
        $user->gender = $request->gender;
        $user->date_of_birth = $request->date_of_birth;

        $user->save();

        return back()->with('success', 'Cập nhật thành công!');
    }
}