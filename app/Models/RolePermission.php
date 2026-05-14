<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $fillable = [
        'role_code',
        'role_name',
        'module',
        'can_view',
        'can_add',
        'can_edit',
        'can_delete',
        'can_approve',
    ];

    protected $casts = [
        'can_view' => 'boolean',
        'can_add' => 'boolean',
        'can_edit' => 'boolean',
        'can_delete' => 'boolean',
        'can_approve' => 'boolean',
    ];
}
