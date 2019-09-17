<?php

namespace BristolSU\Support\ModuleInstance\Settings;

use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Model;

class ModuleInstanceSettings extends Model
{
    protected $casts = [
        'settings' => 'array'
    ];

    protected $fillable = [
        'settings'
    ];

    public function moduleInstance()
    {
        return $this->hasOne(ModuleInstance::class);
    }
}
