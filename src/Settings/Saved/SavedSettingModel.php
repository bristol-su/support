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
     * The table name to use.
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * Fillable attributes.
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
     * Where the setting override is global.
     *
     * @param Builder $query
     */
    public function scopeGlobal(Builder $query)
    {
        $query->where('visibility', 'global');
    }

    /**
     * Where the setting override is for a user.
     *
     * @param Builder $query
     * @param int|null $userId Optional user ID. If given, settings for the user will be given. Otherwise, just defaults will be given
     */
    public function scopeUser(Builder $query, int $userId = null)
    {
        $query->where('visibility', 'user');
        if ($userId === null) {
            $query->whereNull('user_id');
        } else {
            $query->where('user_id', $userId);
        }
    }

    /**
     * Get settings with the given key.
     *
     * @param Builder $query
     * @param string $key
     */
    public function scopeKey(Builder $query, string $key)
    {
        $query->where('key', $key);
    }

    /**
     * Get the value of the setting.
     *
     * @return mixed
     */
    public function getSettingValue()
    {
        return $this->value;
    }

    /**
     * Get the key of the setting.
     *
     * @return mixed
     */
    public function getSettingKey()
    {
        return $this->key;
    }
}
