<?php

namespace BristolSU\Support\Settings\Saved;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static Builder key(string $key) Get a setting model with the given key
 * @method static Builder global() Get only global setting models
 * @method static Builder user(?int $userId = null) Get only user setting models for all users, or for the user ID if given
 */
class SavedSettingModel extends Model
{

    /**
     * The table name to use
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * Fillable attributes
     *
     * @var string[]
     */
    protected $fillable = [
        'key',
        'value',
        'user_id',
        'visibility'
    ];

    /**
     * Value accessor to serialize any value before going into the database
     *
     * @param mixed $value
     * @throws \Exception If the value could not be serialized
     */
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

    /**
     * Value mutator to deserialize any value before being given back to the user
     *
     * @return mixed
     * @throws \Exception If the value could not be deserialized
     */
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

    /**
     * Where the setting override is global
     *
     * @param Builder $query
     */
    public function scopeGlobal(Builder $query)
    {
        $query->where('visibility', 'global');
    }

    /**
     * Where the setting override is for a user
     *
     * @param Builder $query
     * @param int|null $userId Optional user ID. If given, settings for the user will be given. Otherwise, just defaults will be given
     */
    public function scopeUser(Builder $query, int $userId = null)
    {
        $query->where('visibility', 'user');
        if($userId === null) {
            $query->whereNull('user_id');
        } else {
            $query->where('user_id', $userId);
        }
    }

    /**
     * Get settings with the given key
     *
     * @param Builder $query
     * @param string $key
     */
    public function scopeKey(Builder $query, string $key)
    {
        $query->where('key', $key);
    }

    /**
     * Get the value of the setting
     *
     * @return mixed
     */
    public function getSettingValue()
    {
        return $this->value;
    }

    /**
     * Get the key of the setting
     *
     * @return mixed
     */
    public function getSettingKey()
    {
        return $this->key;
    }

}
