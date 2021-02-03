<?php

namespace BristolSU\Support\ModuleInstance\Connection;

use BristolSU\Support\Connection\AccessibleConnectionScope;
use BristolSU\Support\Connection\Connection;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Revision\HasRevisions;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents the assignment of a service connection to a module instance.
 */
class ModuleInstanceService extends Model
{
    use HasRevisions;
    
    /**
     * Table to store data in.
     *
     * @var string
     */
    protected $table = 'module_instance_services';

    /**
     * Fillable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'service', 'connection_id'
    ];

    /**
     * Module instance relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function moduleInstance()
    {
        return $this->belongsTo(ModuleInstance::class);
    }

    /**
     * Connection relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function connection()
    {
        return $this->belongsTo(Connection::class)->withoutGlobalScope(AccessibleConnectionScope::class);
    }
}
