<?php

namespace BristolSU\Support\Activity;

use BristolSU\Support\Activity\Contracts\Repository as ActivityRepositoryContract;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\ControlDB\Contracts\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * Activity Repository implementation using Eloquent
 */
class Repository implements ActivityRepositoryContract
{

    /**
     * Return all admin activities for a user
     *
     * Return all the admin for which the given user, group and role satisfy the forLogic group.
     *
     * @param User|null $user
     * @param Group|null $group
     * @param Role|null $role
     * @return Collection
     */
    public function getForAdministrator(?User $user = null, ?Group $group = null, ?Role $role = null): Collection
    {
        return $this->active()->filter(function($activity) use ($user, $group, $role) {
            $logicTester = app()->make(LogicTester::class);
            return $logicTester->evaluate($activity->adminLogic, $user, $group, $role);
        })->values();
    }

    /**
     * Retrieve all active activities.
     *
     * This method should return all activities that're currently active. This currently just means they are within
     * their active timeframe and they're turned on,
     *
     * @return Collection
     */
    public function active(): Collection
    {
        return Activity::active()->enabled()->with([
            'moduleInstances',
            'forLogic',
            'adminLogic',
            'moduleInstances.activeLogic',
            'moduleInstances.visibleLogic',
            'moduleInstances.mandatoryLogic',
        ])->get();
    }

    /**
     * Return all participant activities for a user
     *
     * Return all the activities for which the given user, group and role satisfy the forLogic group.
     *
     * @param User|null $user
     * @param Group|null $group
     * @param Role|null $role
     * @return Collection
     */
    public function getForParticipant(?User $user = null, ?Group $group = null, ?Role $role = null): Collection
    {
        return $this->active()->filter(function($activity) use ($user, $group, $role) {
            $logicTester = app()->make(LogicTester::class);
            return $logicTester->evaluate($activity->forLogic, $user, $group, $role);
        })->values();
    }

    /**
     * Return all activities
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return Activity::all();
    }

    /**
     * Create an activity
     *
     * Create an activity using the given attributes. The attributes can be taken from the following:
     * [
     *        'name' => 'Activity Name',
     *        'description' => 'Activity Description',
     *        'activity_for' => 'user',
     *        'for_logic' => '1',
     *        'admin_logic' => 2,
     *        'start_date' => null,
     *        'end_date' => null,
     *        'slug' => 'activity-slug',
     *        'type' => 'open',
     *        'enabled' => true
     * ]
     *
     * @param array $attributes Array of attributes to create the activity with
     * @return Activity
     */
    public function create(array $attributes): Activity
    {
        return Activity::create($attributes);
    }


    /**
     * Get an activity by ID
     *
     * @param int $id ID of the activity
     * @return Activity
     *
     * @throws ModelNotFoundException
     */
    public function getById($id): Activity
    {
        return Activity::findOrFail($id);
    }

    /**
     * Update an activity
     *
     * Update an activity using the given attributes. The attributes can be taken from the following:
     * [
     *        'name' => 'Activity Name',
     *        'description' => 'Activity Description',
     *        'activity_for' => 'user',
     *        'for_logic' => '1',
     *        'admin_logic' => 2,
     *        'start_date' => null,
     *        'end_date' => null,
     *        'slug' => 'activity-slug',
     *        'type' => 'open',
     *        'enabled' => true
     * ]
     *
     * @param $id
     * @param $attributes
     * @return Activity
     * @throws ModelNotFoundException
     */
    public function update($id, $attributes)
    {
        $activity = $this->getById($id);
        $activity->fill($attributes);
        $activity->save();
        return $activity;
    }

    /**
     * Delete an activity by ID
     *
     * @param int $id ID of the activity
     * @return void
     *
     * @throws \Exception
     */
    public function delete($id)
    {
        return $this->getById($id)->delete();
    }
}