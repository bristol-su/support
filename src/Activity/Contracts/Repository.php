<?php

namespace BristolSU\Support\Activity\Contracts;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Activity\Activity;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * Activity Repository.
 * 
 * Handles creating and retrieving Activities.
 */
interface Repository
{

    /**
     * Retrieve all active activities.
     * 
     * This method should return all activities that're currently active. This currently just means they are within
     * their active timeframe and enabled
     * 
     * @return Collection
     */
    public function active(): Collection;

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
    public function getForParticipant(?User $user = null, ?Group $group = null, ?Role $role = null): Collection;

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
    public function getForAdministrator(?User $user = null, ?Group $group = null, ?Role $role = null): Collection;

    /**
     * Return all activities
     * 
     * @return Collection
     */
    public function all(): Collection;

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
     *        'type' => 'open'
     * ]
     * 
     * @param array $attributes Array of attributes to create the activity with
     * @return Activity
     */
    public function create(array $attributes): Activity;

    /**
     * Get an activity by ID
     * 
     * @param int $id ID of the activity
     * @return Activity
     * 
     * @throws ModelNotFoundException
     */
    public function getById($id): Activity;

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
     *        'type' => 'open'
     * ]
     * 
     * @param $id
     * @param $attributes
     * @return Activity
     * @throws ModelNotFoundException
     */
    public function update($id, $attributes);

    /**
     * Delete an activity by ID
     * 
     * @param int $id ID of the activity
     * @return void
     * 
     * @throws ModelNotFoundException
     */
    public function delete($id);
}
