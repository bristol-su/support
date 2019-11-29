<?php

namespace BristolSU\Support\ActivityInstance;

use BristolSU\Support\Activity\Activity;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;

class ActivityInstance extends Model implements Authenticatable
{
    protected $guarded = [];

    protected $appends = ['run_number'];
    
    public function getRunNumberAttribute()
    {
        $activityInstances = static::newQuery()
            ->where('activity_id', $this->activity_id)
            ->where('resource_type', $this->resource_type)
            ->where('resource_id', $this->resource_id)
            ->orderBy('created_at')
            ->get();
        for($i=0;$i<=$activityInstances->count();$i++) {
            if($this->is($activityInstances->offsetGet($i))) {
                return $i+1;
            }
        }
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        // TODO: Implement getAuthPassword() method.
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        // TODO: Implement getRememberToken() method.
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        // TODO: Implement setRememberToken() method.
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        // TODO: Implement getRememberTokenName() method.
    }
}