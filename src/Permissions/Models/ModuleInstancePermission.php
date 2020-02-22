<?php

namespace BristolSU\Support\Permissions\Models;

use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Contracts\PermissionRepository;
use BristolSU\Support\Revision\HasRevisions;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents a module instance permission
 */
class ModuleInstancePermission extends Model
{
    use HasRevisions;
    
    /**
     * Fillable attributes
     * 
     * @var array
     */
    protected $fillable = [
        'logic_id', 'ability', 'module_instance_id'
    ];

    /**
     * Appended properties
     * 
     * - Type: Returns the type of the permission. One of module or global
     * - Permission: Returns a permission model representing the permission attached to this module instance.
     * 
     * @var array 
     */
    protected $appends = ['type', 'permission'];

    /**
     * Get the associated module instance
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function moduleInstance()
    {
        return $this->belongsTo(ModuleInstance::class);
    }

    /**
     * Get the associated logic group
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function logic()
    {
        return $this->belongsTo(Logic::class);
    }

    /**
     * Get the permission associated with the module instance permission class
     * 
     * @return mixed
     */
    public function getPermissionAttribute()
    {
        return app(PermissionRepository::class)->get($this->ability);
    }

    /**
     * Get the type of the associated permission class.
     * 
     * @return string module or global
     */
    public function getTypeAttribute()
    {
        return $this->permission->getType();
    }
    
}
