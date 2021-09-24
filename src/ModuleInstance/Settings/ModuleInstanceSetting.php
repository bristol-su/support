<?php

namespace BristolSU\Support\ModuleInstance\Settings;

use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Revision\HasRevisions;
use Database\Factories\ModuleInstanceSettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Represents a setting value associated with a module instance.
 */
class ModuleInstanceSetting extends Model
{
    use HasRevisions, HasFactory, SoftDeletes;

    /**
     * Fillable attributes for mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'key', 'value', 'module_instance_id', 'encoded'
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
     * Dynamically encode the value of the setting if an array.
     *
     * @param mixed $value
     */
    public function setValueAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['value'] = json_encode($value);
            $this->attributes['encoded'] = true;
        } else {
            $this->attributes['value'] = $value;
        }
    }

    /**
     * Dynamically decode the value of the setting if json.
     *
     * @return mixed
     */
    public function getValueAttribute()
    {
        if (($this->attributes['encoded']??false)) {
            return json_decode($this->attributes['value'], true);
        }

        return $this->attributes['value'];
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new ModuleInstanceSettingFactory();
    }
}
