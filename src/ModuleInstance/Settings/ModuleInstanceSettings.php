<?php

namespace BristolSU\Support\ModuleInstance\Settings;

use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModuleInstanceSettings
 * @package BristolSU\Support\ModuleInstance\Settings
 */
class ModuleInstanceSettings extends Model
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
        'settings'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function moduleInstance()
    {
        return $this->hasOne(ModuleInstance::class);
    }
}
