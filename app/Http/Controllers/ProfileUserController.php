<?php

namespace App\Http\Controllers;

use App\Models\AccountActivityLog;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

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
        if (Auth::user()->role_id == 5) {
            return redirect()->route('profile.admin');
        }

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
        $oldProfile = $this->profileSnapshot($user);

        // 1. Xác thực tính hợp lệ của dữ liệu đầu vào
        $data = $request->validate([

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
                'integer',
                'min:1',
                Rule::unique('users', 'id')->ignore($user->id),
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
        ], [
            'user_id.unique' => 'ID này đã tồn tại!',
            'user_id.min' => 'ID người dùng phải lớn hơn 0!',
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
        $oldUserId = (int) $user->id;
        $newUserId = (int) $data['user_id'];

        DB::transaction(function () use ($user, $data, $oldUserId, $newUserId): void {
            if ($oldUserId !== $newUserId) {
                Schema::disableForeignKeyConstraints();
            }

            try {
                DB::table('users')
                    ->where('id', $oldUserId)
                    ->update([
                        'id' => $newUserId,
                        'name' => $data['name'],
                        'username' => $data['username'],
                        'email' => $data['email'],
                        'phone' => $data['phone'],
                        'home_address' => $data['home_address'],
                        'gender' => $data['gender'],
                        'date_of_birth' => $data['date_of_birth'],
                        'avatar_url' => $user->avatar_url,
                        'updated_at' => now(),
                    ]);

                if ($oldUserId !== $newUserId) {
                    $this->syncUserReferences($oldUserId, $newUserId);
                }
            } finally {
                if ($oldUserId !== $newUserId) {
                    Schema::enableForeignKeyConstraints();
                }
            }
        });

        if ($oldUserId !== $newUserId) {
            Auth::login(User::query()->findOrFail($newUserId));
        }

        $user = Auth::user()->fresh();

        $changes = $this->profileChanges($oldProfile, $this->profileSnapshot($user));

        if ($changes !== []) {
            AccountActivityLog::recordFor(
                $user,
                'profile_update',
                'Cập nhật hồ sơ',
                'Tài khoản đã cập nhật thông tin hồ sơ cá nhân.',
                ['changes' => $changes],
                $request
            );
        }

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

        AccountActivityLog::recordFor(
            $user,
            'profile_update',
            'Đổi mật khẩu',
            'Tài khoản đã thay đổi mật khẩu đăng nhập.',
            ['changes' => [
                [
                    'field' => 'password',
                    'label' => 'Mật khẩu',
                    'old' => 'Đã ẩn',
                    'new' => 'Đã cập nhật',
                ],
            ]],
            $request
        );

		return back()->with('success', 'Đổi mật khẩu thành công');
	}

    /**
     * Xử lý yêu cầu xóa tài khoản người dùng.
     */
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        // Đăng xuất trước khi xóa
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Xóa tài khoản
        $user->delete();

        return redirect()->route('home')->with('success', 'Tài khoản đã được xóa thành công.');
    }

    /**
     * Đồng bộ các bản ghi đang tham chiếu tới user khi cho phép đổi ID tài khoản.
     */
    private function syncUserReferences(int $oldUserId, int $newUserId): void
    {
        $tables = [
            'account_activity_logs',
            'orders',
            'product_reviews',
            'warehouse_receipts',
            'warehouse_issues',
            'inventory_checks',
            'sessions',
        ];

        foreach ($tables as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'user_id')) {
                continue;
            }

            DB::table($table)
                ->where('user_id', $oldUserId)
                ->update(['user_id' => $newUserId]);
        }
    }

    /**
     * Lấy ảnh chụp các trường hồ sơ cần ghi nhật ký.
     */
    private function profileSnapshot($user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'phone' => $user->phone,
            'home_address' => $user->home_address,
            'gender' => $user->gender,
            'date_of_birth' => $this->formatDateForLog($user->date_of_birth),
            'avatar_url' => $user->avatar_url,
        ];
    }

    /**
     * Chuẩn bị danh sách trường thay đổi để hiển thị trên giao diện nhật ký.
     */
    private function profileChanges(array $oldProfile, array $newProfile): array
    {
        $labels = [
            'id' => 'ID người dùng',
            'name' => 'Họ và tên',
            'username' => 'Tên đăng nhập',
            'email' => 'Email',
            'phone' => 'Số điện thoại',
            'home_address' => 'Địa chỉ',
            'gender' => 'Giới tính',
            'date_of_birth' => 'Ngày sinh',
            'avatar_url' => 'Ảnh đại diện',
        ];

        $changes = [];

        foreach ($labels as $field => $label) {
            $oldValue = $oldProfile[$field] ?? null;
            $newValue = $newProfile[$field] ?? null;

            if (($oldValue ?? '') === ($newValue ?? '')) {
                continue;
            }

            $changes[] = [
                'field' => $field,
                'label' => $label,
                'old' => $oldValue,
                'new' => $newValue,
            ];
        }

        return $changes;
    }

    /**
     * Chuẩn hóa ngày sinh trước khi ghi vào metadata JSON.
     */
    private function formatDateForLog($value): ?string
    {
        if ($value instanceof CarbonInterface) {
            return $value->format('Y-m-d');
        }

        return $value ? (string) $value : null;
    }
}
