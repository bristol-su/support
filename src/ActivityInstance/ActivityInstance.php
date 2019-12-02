<?php

namespace BristolSU\Support\ActivityInstance;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Control\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class ActivityInstance extends Model implements Authenticatable
{
    protected $guarded = [];

    protected $appends = ['run_number', 'participant'];
    
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

    public function getParticipantAttribute()
    {
        if($this->resource_type === 'user') {
            return app(UserRepository::class)->getById($this->resource_id);
        }
        if($this->resource_type === 'group') {
            return app(GroupRepository::class)->getById($this->resource_id);
        }
        if($this->resource_type === 'role') {
            return app(RoleRepository::class)->getById($this->resource_id);
        }
        throw new \Exception('Resource type is not valid');
    }

    public function moduleInstances()
    {
        return $this->hasManyThrough(ModuleInstance::class, Activity::class, 'id', 'activity_id', 'activity_id', 'id');
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