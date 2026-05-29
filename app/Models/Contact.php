<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Contact
 *
 * Định nghĩa thực thể đại diện cho các tin nhắn liên hệ từ khách hàng gửi về.
 * Quản lý thông tin họ tên, email, số điện thoại, tiêu đề, nội dung tin nhắn, 
 * trạng thái xử lý và nội dung phản hồi từ ban quản trị.
 */
class Contact extends Model
{
    /**
     * Các thuộc tính có thể gán giá trị hàng loạt (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'reply_message',
    ];
}

