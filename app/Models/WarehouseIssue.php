<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'reason',
        'note',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(WarehouseIssueItem::class);
    }
}
