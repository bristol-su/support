<?php

namespace BristolSU\Support\Permissions\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ModuleInstancePermissions
 * @package BristolSU\Support\Permissions\Models
 */
class ModuleInstancePermissions extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'participant_permissions',
        'admin_permissions'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'participant_permissions' => 'array',
        'admin_permissions' => 'array'
    ];
}
