<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'image_url',
        'link',
        'position',
        'sort_order',
        'is_active',
        'start_date',
        'end_date',
    ];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];
}
