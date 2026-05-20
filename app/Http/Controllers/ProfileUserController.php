<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileUserController extends Controller
{
    // PROFILE PAGE
    public function index()
    {
        return view('user.profile-user');
    }

    // UPDATE PROFILE
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
                'mimes:jpg,jpeg,png',
                'max:2048'
            ],

        ]);

        // upload avatar
        if ($request->hasFile('avatar')) {

            $file = $request->file('avatar');

            $filename =
                time() . '_' .
                $file->getClientOriginalName();

            $file->move(
                public_path('uploads/avatar'),
                $filename
            );

            $user->avatar_url =
                'uploads/avatar/' . $filename;
        }

        // update profile
        $user->name = $request->name;

        $user->username = $request->username;

        $user->email = $request->email;

        $user->phone = $request->phone;

        $user->home_address =
            $request->home_address;

        $user->gender = $request->gender;

        $user->date_of_birth =
            $request->date_of_birth;

        $user->save();

        return back()->with(
            'success',
            'Profile updated successfully!'
        );
    }

    // SHOW CHANGE PASSWORD PAGE
    public function showChangePassword()
    {
        return view('user.change-password');
    }

    // CHANGE PASSWORD
    public function changePassword(Request $request)
    {
        $request->validate([

            'current_password' => 'required',

            'new_password' => 'required|min:6|confirmed',

        ]);

        $user = Auth::user();

        // check old password
        if (!Hash::check(
            $request->current_password,
            $user->password
        )) {

            return back()->withErrors([
                'current_password' =>
                    'Current password is incorrect.'
            ]);
        }

        // update password
        $user->password = Hash::make(
            $request->new_password
        );

        $user->save();

        return back()->with(
            'success',
            'Password changed successfully!'
        );
    }

    // REMOVE ACCOUNT
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}