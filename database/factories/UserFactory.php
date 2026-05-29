<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Lớp UserFactory
 *
 * Định nghĩa khuôn mẫu mặc định cho dữ liệu mẫu của Model User.
 * Giúp tạo tự động danh sách lớn các tài khoản người dùng ngẫu nhiên phục vụ kiểm thử.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Mật khẩu tĩnh được mã hóa sử dụng chung cho các tài khoản sinh từ Factory.
     * Tránh việc phải Hash lại mật khẩu nhiều lần gây chậm tiến trình test.
     */
    protected static ?string $password;

    /**
     * Định nghĩa các giá trị thuộc tính mặc định cho thực thể User.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role_id' => 2,
            'status' => 'active',
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Trạng thái tài khoản người dùng chưa xác minh địa chỉ Email.
     *
     * @return static
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
