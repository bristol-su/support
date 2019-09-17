<?php

namespace BristolSU\Support\Permissions\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleInstancePermissions extends Model
{
    protected $fillable = [
        'participant_permissions',
        'admin_permissions'
    ];

    protected $casts = [
        'participant_permissions' => 'array',
        'admin_permissions' => 'array'
    ];
}
