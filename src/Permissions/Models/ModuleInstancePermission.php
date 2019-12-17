<?php

namespace BristolSU\Support\Permissions\Models;

use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Contracts\PermissionRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModuleInstancePermissions
 * @package BristolSU\Support\Permissions\Models
 */
class ModuleInstancePermission extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'logic_id', 'ability', 'module_instance_id'
    ];
    
    protected $appends = ['type', 'permission'];

    public function moduleInstance()
    {
        return $this->belongsTo(ModuleInstance::class);
    }

    public function logic()
    {
        return $this->belongsTo(Logic::class);
    }

    public function getPermissionAttribute()
    {
        return app(PermissionRepository::class)->get($this->ability);
    }
    
    public function getTypeAttribute()
    {
        return $this->permission->getModuleType();
    }
    
}
