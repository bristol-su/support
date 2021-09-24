<?php

namespace BristolSU\Support\Progress\Handlers\Database\Models;

use BristolSU\Support\ModuleInstance\ModuleInstance;
use Database\Factories\ModuleInstanceProgressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuleInstanceProgress extends Model
{
    use HasFactory, SoftDeletes;

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

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new ModuleInstanceProgressFactory();
    }
}
