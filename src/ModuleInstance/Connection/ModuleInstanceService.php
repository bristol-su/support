<?php

namespace BristolSU\Support\ModuleInstance\Connection;

use BristolSU\Support\Connection\Connection;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Model;

class ModuleInstanceService extends Model
{

    protected $table = 'module_instance_services';
    
    protected $fillable = [
        'service'
    ];

    public function moduleInstance()
    {
        return $this->belongsTo(ModuleInstance::class);
    }

    public function connection()
    {
        return $this->belongsTo(Connection::class);
    }
    
}