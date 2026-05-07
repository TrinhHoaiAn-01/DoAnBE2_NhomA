<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    protected $fillable = [
        'user_name',
        'action',
        'target_type',
        'old_data',
        'new_data',
    ];

    protected $casts = [
        'old_data' => 'json',
        'new_data' => 'json',
    ];

    /**
     * Tự động phân tích sự thay đổi giữa dữ liệu cũ và mới (Diff Analysis)
     * Giúp Controller gọn gàng hơn, tuân thủ đúng chuẩn MVC.
     */
    public function getChangesDiffAttribute()
    {
        $changes = [];
        $oldData = $this->old_data;
        $newData = $this->new_data;

        if (is_array($oldData) && is_array($newData)) {
            foreach ($newData as $key => $newValue) {
                $oldValue = $oldData[$key] ?? null;

                // Trường hợp 1: Dữ liệu là mảng lồng nhau (ví dụ: Ma trận phân quyền)
                if (is_array($newValue) && is_array($oldValue)) {
                    foreach ($newValue as $field => $val) {
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
                // Trường hợp 2: Dữ liệu phẳng
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
