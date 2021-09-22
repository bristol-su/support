<?php

namespace BristolSU\Support\ModuleInstance\Connection;

use BristolSU\Support\Connection\AccessibleConnectionScope;
use BristolSU\Support\Connection\Connection;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Revision\HasRevisions;
use Database\Factories\ModuleInstanceServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents the assignment of a service connection to a module instance.
 */
class ModuleInstanceService extends Model
{
    use HasRevisions, HasFactory;

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

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new ModuleInstanceServiceFactory();
    }
}
