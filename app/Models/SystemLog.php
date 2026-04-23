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
}
