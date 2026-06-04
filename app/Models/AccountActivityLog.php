<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

/**
 * Model AccountActivityLog
 *
 * Ghi lại các hoạt động quan trọng của tài khoản người dùng như đăng nhập,
 * thay đổi hồ sơ và mua hàng.
 */
class AccountActivityLog extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính được phép gán hàng loạt.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'description',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    /**
     * Ép kiểu dữ liệu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Tài khoản sở hữu dòng nhật ký.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ghi nhanh một hoạt động của tài khoản hiện tại.
     */
    public static function recordFor(
        User $user,
        string $type,
        string $title,
        ?string $description = null,
        array $metadata = [],
        ?Request $request = null
    ): self {
        return self::query()->create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'metadata' => $metadata === [] ? null : $metadata,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
