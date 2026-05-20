<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProfileUserController extends Controller
{
    // =========================
    // SHOW PROFILE PAGE
    // =========================
    public function index()
    {
        return view('profile');
    }

    // =========================
    // UPDATE PROFILE
    // =========================
    public function update(Request $request)
    {
        $user = Auth::user();

        // =========================
        // VALIDATE
        // =========================
        $request->validate([

            'name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'username' => [
                'required',
                'string',
                'max:255'
            ],

            'user_id' => [
                'required',
                'integer'
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
                'string'
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
                'mimes:jpg,jpeg,png',
                'max:2048'
            ],
        ]);

        // =========================
        // CHECK USERNAME EXISTS
        // =========================
        $checkUsername = DB::table('users')
            ->where('username', $request->username)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($checkUsername) {

            return back()
                ->withErrors([
                    'username' => 'Tên đăng nhập đã tồn tại!'
                ])
                ->withInput();
        }

        // =========================
        // CHECK ID EXISTS
        // =========================
        $checkId = DB::table('users')
            ->where('id', $request->user_id)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($checkId) {

            return back()
                ->withErrors([
                    'user_id' => 'ID này đã tồn tại!'
                ])
                ->withInput();
        }

        // =========================
        // UPDATE AVATAR
        // =========================
        if ($request->hasFile('avatar')) {

            // delete old avatar
            if (
                $user->avatar_url &&
                File::exists(public_path($user->avatar_url))
            ) {
                File::delete(public_path($user->avatar_url));
            }

            // upload new avatar
            $file = $request->file('avatar');

            $filename = time() . '_' . $file->getClientOriginalName();

            $file->move(
                public_path('uploads/avatar'),
                $filename
            );

            $user->avatar_url = 'uploads/avatar/' . $filename;
        }

        // =========================
        // UPDATE DATA
        // =========================
        $user->name = $request->name;

        $user->username = $request->username;

        // change id if not duplicate
        $user->id = $request->user_id;

        $user->email = $request->email;

        $user->phone = $request->phone;

        $user->home_address = $request->home_address;

        $user->gender = $request->gender;

        $user->date_of_birth = $request->date_of_birth;

        $user->save();

        // =========================
        // SUCCESS
        // =========================
        return back()->with(
            'success',
            'Cập nhật hồ sơ thành công!'
        );
    }
}