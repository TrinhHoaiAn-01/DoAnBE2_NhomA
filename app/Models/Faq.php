<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Faq
 *
 * Định nghĩa thực thể đại diện cho các câu hỏi thường gặp (FAQs).
 * Quản lý thông tin phân nhóm danh mục, nội dung câu hỏi, câu trả lời, thứ tự sắp xếp và trạng thái ẩn hiện.
 */
class Faq extends Model
{
    /**
     * Các thuộc tính có thể gán giá trị hàng loạt (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category',
        'question',
        'answer',
        'sort_order',
        'is_active',
    ];
}

