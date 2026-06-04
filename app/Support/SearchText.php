<?php

namespace App\Support;

class SearchText
{
    public const LIMIT = 50;

    public static function normalize(?string $value): string
    {
        $value = trim((string) $value);

        if (mb_strlen($value, 'UTF-8') <= self::LIMIT) {
            return $value;
        }

        return mb_substr($value, 0, self::LIMIT, 'UTF-8');
    }

    public static function wasLimited(?string $value): bool
    {
        return mb_strlen(trim((string) $value), 'UTF-8') > self::LIMIT;
    }

    public static function limitMessage(): string
    {
        return 'Từ khóa tìm kiếm chỉ được tối đa '.self::LIMIT.' ký tự. Nội dung vượt quá đã được cắt bớt.';
    }
}
