<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model SystemLog
 *
 * Định nghĩa thực thể đại diện cho Nhật ký hoạt động của hệ thống (System Logs/Audit Logs).
 * Ghi lại các hoạt động đăng nhập, cập nhật dữ liệu của quản trị viên và nhân viên.
 * Hỗ trợ lưu trữ trạng thái dữ liệu trước (old_data) và sau (new_data) khi thay đổi dưới dạng JSON
 * để phục vụ phân tích chênh lệch lịch sử (Diff Analysis).
 */
class SystemLog extends Model
{
    /**
     * Các thuộc tính có thể gán giá trị hàng loạt (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_name',
        'action',
        'target_type',
        'old_data',
        'new_data',
    ];

    /**
     * Các thuộc tính cần được chuyển đổi kiểu dữ liệu (Casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'old_data' => 'json',
        'new_data' => 'json',
    ];

    /**
     * Thuộc tính ảo (Accessor) tự động phân tích sự thay đổi chi tiết giữa dữ liệu cũ và dữ liệu mới.
     * Giúp Controller gọn gàng hơn, tuân thủ đúng chuẩn MVC và hiển thị trực quan sai biệt trên UI.
     *
     * @return array Danh sách các cột/trường thông tin bị thay đổi kèm giá trị cũ và mới
     */
    public function getChangesDiffAttribute()
    {
        $changes = [];
        $oldData = $this->old_data;
        $newData = $this->new_data;

        if (is_array($oldData) && is_array($newData)) {
            foreach ($newData as $key => $newValue) {
                $oldValue = $oldData[$key] ?? null;

                // Trường hợp 1: Dữ liệu là mảng lồng nhau (ví dụ: Ma trận phân quyền theo vai trò)
                if (is_array($newValue) && is_array($oldValue)) {
                    foreach ($newValue as $field => $val) {
                        // Loại bỏ các trường mốc thời gian tự động của Laravel khi so sánh
                        if (!in_array($field, ['updated_at', 'created_at']) && $val !== ($oldValue[$field] ?? null)) {
                            $changes[] = [
                                'item' => $key,
                                'field' => $field,
                                'old' => $oldValue[$field] ?? null,
                                'new' => $val
                            ];
                        }
                    }
                }
                // Trường hợp 2: Dữ liệu cấu trúc phẳng (ví dụ: thông tin cá nhân hoặc Nhà cung cấp)
                elseif (!is_array($newValue) && !in_array($key, ['updated_at', 'created_at']) && $newValue !== $oldValue) {
                    $changes[] = [
                        'item' => 'Dữ liệu',
                        'field' => $key,
                        'old' => $oldValue,
                        'new' => $newValue
                    ];
                }
            }
        }

        return $changes;
    }
}

