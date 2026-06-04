<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

/**
 * Controller ProfileUserController
 *
 * Quản lý hồ sơ cá nhân của người dùng bao gồm:
 * Hiển thị trang hồ sơ, cập nhật thông tin cá nhân (ảnh đại diện, họ tên, email, SĐT, giới tính...),
 * và chức năng thay đổi mật khẩu tài khoản.
 */
class ProfileUserController extends Controller
{
    /**
     * Hiển thị trang hồ sơ cá nhân của người dùng.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('user.profile-user');
    }

    /**
     * Xử lý cập nhật thông tin hồ sơ người dùng.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Xác thực tính hợp lệ của dữ liệu đầu vào
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
                'unique:users,email,' . $user->id // Ngoại trừ Email của chính người dùng hiện tại
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
                'max:2048' // Dung lượng ảnh tối đa 2MB
            ],
        ]);

        // 2. Kiểm tra xem tên đăng nhập mới có bị trùng với tài khoản khác hay không
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

        // 3. Kiểm tra xem ID mới có bị trùng lặp với người dùng khác trong hệ thống không
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

        // 4. Xử lý cập nhật ảnh đại diện (Avatar) mới nếu có file được tải lên
        if ($request->hasFile('avatar')) {

            // 4.1 Xóa ảnh đại diện cũ trên ổ đĩa nếu tồn tại
            if (
                $user->avatar_url &&
                File::exists(public_path($user->avatar_url))
            ) {
                File::delete(public_path($user->avatar_url));
            }

            // 4.2 Tải ảnh mới lên thư mục public/uploads/avatar
            $file = $request->file('avatar');

            $filename = time() . '_' . $file->getClientOriginalName();

            $file->move(
                public_path('uploads/avatar'),
                $filename
            );

            // Lưu đường dẫn ảnh đại diện mới vào cơ sở dữ liệu
            $user->avatar_url = 'uploads/avatar/' . $filename;
        }

        // 5. Gán và cập nhật các thông tin cá nhân mới
        $user->name = $request->name;

        $user->username = $request->username;

        // Cập nhật lại ID người dùng nếu thay đổi hợp lệ
        $user->id = $request->user_id;

        $user->email = $request->email;

        $user->phone = $request->phone;

        $user->home_address = $request->home_address;

        $user->gender = $request->gender;

        $user->date_of_birth = $request->date_of_birth;

        // Lưu thông tin vào Database
        $user->save();

        // =========================
        // SUCCESS
        // =========================
        return back()->with(
            'success',
            'Cập nhật hồ sơ thành công!'
        );
    }
	
    /**
     * Hiển thị trang đổi mật khẩu.
     *
     * @return \Illuminate\View\View
     */
	public function showChangePassword()
	{
		return view('user.change-password');
	}
	
    /**
     * Xử lý yêu cầu thay đổi mật khẩu người dùng.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
	public function changePassword(Request $request)
	{
        // 1. Xác thực dữ liệu mật khẩu cũ và mới
		$request->validate([
			'current_password' => 'required',
			'new_password' => 'required|min:6|confirmed', // Mật khẩu mới tối thiểu 6 ký tự và khớp với trường xác nhận
		]);

		$user = auth()->user();

        // 2. Kiểm tra tính chính xác của mật khẩu cũ hiện tại
		if (!Hash::check($request->current_password, $user->password)) {
			return back()->withErrors(['current_password' => 'Mật khẩu cũ không đúng']);
		}

        // 3. Tiến hành mã hóa mật khẩu mới và lưu vào cơ sở dữ liệu
		$user->password = Hash::make($request->new_password);
		$user->save();

		return back()->with('success', 'Đổi mật khẩu thành công');
	}
}