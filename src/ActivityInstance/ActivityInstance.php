<?php

namespace BristolSU\Support\ActivityInstance;

use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Progress\Handlers\Database\Models\Progress;
use BristolSU\Support\Revision\HasRevisions;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

/**
 * An eloquent model representing an Activity Instance.
 */
class ActivityInstance extends Model implements Authenticatable
{
    use HasRevisions;

    /**
     * Additional attributes to add to the model.
     *
     * Run number is an incrementing ID to represent multiple activity instances for the same resource/activity.
     * Participant is the resource model
     *
     * @var array
     */
    protected $appends = ['run_number', 'participant', 'participant_name'];

    /**
     * Fillable properties.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'activity_id', 'resource_type', 'resource_id'
    ];

    /**
     * Get the run number of the activity instance as a Laravel attribute.
     *
     * If there are multiple run throughs of an activity, this ID will number them from oldest to newest.
     *
     * @return int
     */
    public function getRunNumberAttribute()
    {
        $activityInstances = static::newQuery()
            ->where('activity_id', $this->activity_id)
            ->where('resource_type', $this->resource_type)
            ->where('resource_id', $this->resource_id)
            ->orderBy('created_at')
            ->get();
        if ($activityInstances->count() > 0) {
            for ($i = 0; $i <= $activityInstances->count(); $i++) {
                if ($this->is($activityInstances->offsetGet($i))) {
                    return $i + 1;
                }
            }
        }

        return 1;
    }

    /**
     * Retrieve the name of the participant to show to users.
     *
     * @throws \Exception
     * @return string|null
     */
    public function getParticipantNameAttribute()
    {
        return $this->participantName();
    }

    /**
     * Get the model from the current resource as a Laravel attribute.
     *
     * If the resource_type is user, returns the user with the id 'resource_id'
     * If the resource_type is group, returns the group with the id 'resource_id'
     * If the resource_type is role, returns the role with the id 'resource_id'
     *
     * @throws \Exception If the resource type is not one of user, group or role
     * @return User|Group|Role The model for the resource of the activity instance.
     *
     */
    public function getParticipantAttribute()
    {
        if ($this->resource_type === 'user') {
            return app(UserRepository::class)->getById($this->resource_id);
        }
        if ($this->resource_type === 'group') {
            return app(GroupRepository::class)->getById($this->resource_id);
        }
        if ($this->resource_type === 'role') {
            return app(RoleRepository::class)->getById($this->resource_id);
        }

        throw new \Exception('Resource type is not valid');
    }

    /**
     * Get the model from the current resource.
     *
     * Function for retrieving the user, group or role associated with the activity instance.
     *
     * @return User|Group|Role The model for the resource of the activity instance.
     */
    public function participant()
    {
        return $this->participant;
    }

    /**
     * Module Instance relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function moduleInstances()
    {
        return $this->hasManyThrough(ModuleInstance::class, Activity::class, 'id', 'activity_id', 'activity_id', 'id');
    }

    /**
     * Activity relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Get the name of the unique identifier for the activity instance.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the activity instance.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    /**
     * Get the password for the activity instance.
     *
     * @return string
     */
    public function getAuthPassword()
    {
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param string $value
     */
    public function setRememberToken($value)
    {
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
    }

    public function participantName()
    {
        if ($this->resource_type === 'user') {
            return $this->participant()->data()->preferredName();
        }
        if ($this->resource_type === 'group') {
            return $this->participant()->data()->name();
        }
        if ($this->resource_type === 'role') {
            return $this->participant()->data()->roleName();
        }

        throw new \Exception();
    }

    public function progress()
    {
        return $this->hasMany(Progress::class);
    }
}
