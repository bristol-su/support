<?php

namespace BristolSU\Support\ModuleInstance\Settings;

use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModuleInstanceSettings
 * @package BristolSU\Support\ModuleInstance\Settings
 */
class ModuleInstanceSetting extends Model
{
    /**
     * @var array
     */
    protected $casts = [
        'settings' => 'array'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'key', 'value', 'module_instance_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function moduleInstance()
    {
        return $this->belongsTo(ModuleInstance::class);
    }
}
