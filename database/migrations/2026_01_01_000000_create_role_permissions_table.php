<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực thi tạo cấu trúc bảng role_permissions.
     *
     * @return void
     */
    public function up(): void
    {
        // Tạo bảng Phân quyền Vai trò (role_permissions)
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id(); // Khóa chính tự sinh

            $table->string('role_code', 50); // Mã vai trò (ví dụ: ROLE_1, ROLE_5...)
            $table->string('role_name', 100); // Tên vai trò hiển thị
            $table->string('module', 100); // Tên phân hệ chức năng kiểm soát (ví dụ: Customer, Product...)

            // Các cờ quyền hạn (true = có quyền, false = không có quyền)
            $table->boolean('can_view')->default(false); // Quyền xem
            $table->boolean('can_add')->default(false); // Quyền thêm mới
            $table->boolean('can_edit')->default(false); // Quyền cập nhật sửa đổi
            $table->boolean('can_delete')->default(false); // Quyền xóa bỏ
            $table->boolean('can_approve')->default(false); // Quyền phê duyệt thao tác (nếu có)

            $table->timestamps(); // Thời điểm tạo và cập nhật bản ghi

            // Đảm bảo tính duy nhất: Mỗi vai trò chỉ có tối đa một bản ghi quyền cho một phân hệ
            $table->unique(['role_code', 'module']);
        });
    }

    /**
     * Hủy bỏ bảng role_permissions khi rollback.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};