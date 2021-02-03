<?php

namespace BristolSU\Support\Progress\Handlers\Database\Models;

use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Model;

class ModuleInstanceProgress extends Model
{
    protected $table = 'module_instance_progress';

    protected $fillable = [
        'module_instance_id',
        'progress_id',
        'mandatory',
        'complete',
        'percentage',
        'active',
        'visible'
    ];

    public function progress()
    {
        return $this->belongsTo(Progress::class);
    }

    public function moduleInstance()
    {
        return $this->belongsTo(ModuleInstance::class);
    }
}
