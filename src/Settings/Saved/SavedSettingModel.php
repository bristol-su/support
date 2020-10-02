<?php

namespace BristolSU\Support\Settings\Saved;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents a sitewide setting saved in the database
 *
 * @method static Builder key(string $key) Get a setting model with the given key
 */
class SavedSettingModel extends Model
{

    protected $table = 'settings';

    protected $fillable = [
        'key', 'value'
    ];

    public function setValueAttribute($value)
    {
        if(is_string($value)) {
            $this->attributes['value'] = $value;
            $this->attributes['type'] = 'string';
        } elseif(is_integer($value)) {
            $this->attributes['value'] = $value;
            $this->attributes['type'] = 'integer';
        } elseif(is_array($value)) {
            $this->attributes['value'] = json_encode($value);
            $this->attributes['type'] = 'array';
        } elseif(is_bool($value)) {
            $this->attributes['value'] = ($value?1:0);
            $this->attributes['type'] = 'boolean';
        } elseif(is_float($value)) {
            $this->attributes['value'] = $value;
            $this->attributes['type'] = 'float';
        } elseif(is_null($value)) {
            $this->attributes['value'] = null;
            $this->attributes['type'] = 'null';
        } elseif(is_object($value) && !is_callable($value)) {
            $this->attributes['value'] = serialize($value);
            $this->attributes['type'] = 'object';
        } else {
            throw new \Exception(sprintf('Type %s is not supported in saving settings', gettype($value)));
        }
    }

    public function getValueAttribute()
    {
        $value = $this->attributes['value'];
        $type = $this->attributes['type'];

        if($type === 'string') {
            return (string) $value;
        } elseif($type === 'integer') {
            return (int) $value;
        } elseif($type === 'array') {
            return json_decode($value, true);
        } elseif($type === 'boolean') {
            return (int) $value === 1;
        } elseif($type === 'float') {
            return (float) $value;
        } elseif($type === 'null') {
            return null;
        } elseif($type === 'object') {
            return unserialize($value);
        }
        throw new \Exception(sprintf('Type %s is not supported in retrieving settings', $type));
    }

    public function scopeKey(Builder $query, string $key)
    {
        $query->where('key', $key);
    }

    public function getSettingValue()
    {
        return $this->value;
    }

    public function getSettingKey()
    {
        return $this->key;
    }

}
