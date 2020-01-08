<?php

namespace BristolSU\Support\ModuleInstance\Settings;

use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModuleInstanceSettings
 * @package BristolSU\Support\ModuleInstance\Settings
 * TODO Move to Settings directory
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
        'key', 'value', 'module_instance_id', 'encoded'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function moduleInstance()
    {
        return $this->belongsTo(ModuleInstance::class);
    }

    public function setValueAttribute($value)
    {
        if(is_array($value)) {
            $this->attributes['value'] = json_encode($value);
            $this->attributes['encoded'] = true;
        } else {
            $this->attributes['value'] = $value;
        }
    }

    public function getValueAttribute()
    {
        if(($this->attributes['encoded']??false)) {
            return json_decode($this->attributes['value']);
        }
        return $this->attributes['value'];
    }
}
