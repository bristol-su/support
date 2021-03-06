<?php

namespace BristolSU\Support\ModuleInstance\Contracts;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance as ModuleInstanceContract;
use Illuminate\Support\Collection;

/**
 * Handle interacting with module instances.
 */
interface ModuleInstanceRepository
{
    /**
     * Get a module instance by ID.
     *
     * @param int $id ID of the module instance
     * @return ModuleInstance Module instance with the given ID
     */
    public function getById(int $id): ModuleInstance;

    /**
     * Create a new module instance.
     *
     * The module instance has the following attributes
     * [
     *      'alias' => 'alias of the module',
     *      'activity_id' => 1,
     *      'slug' => 'slug-of-the-module',
     *      'completion_condition_instance_id' => 1, // nullable
     *      'name' => 'Name of the module',
     *      'description' => 'Description',
     *      'active' => Logic ID for the active logic,
     *      'visible' => Logic ID for the visible logic,
     *      'mandatory' => Nullable logic ID for the mandatory logic
     * ]
     *
     * @param array $attributes Attributes as defined in the description
     * @return ModuleInstance Created module instance
     */
    public function create(array $attributes): ModuleInstance;

    /**
     * Get all module instances.
     *
     * @return ModuleInstance[]|Collection
     */
    public function all(): Collection;

    /**
     * Get all module instances belonging to a module alias.
     *
     * @param string $alias Alias of the module
     * @return ModuleInstance[]|Collection
     */
    public function allWithAlias(string $alias = ''): Collection;

    /**
     * Get all module instances that belong to a given activity.
     *
     * @param Activity $activity Activity to retrieve module instances through
     *
     * @return Collection
     */
    public function allThroughActivity(Activity $activity): Collection;

    /**
     * Get all enabled module instances that belong to a given activity.
     *
     * @param Activity $activity Activity to retrieve enabled module instances through
     *
     * @return Collection
     */
    public function allEnabledThroughActivity(Activity $activity): Collection;

    /**
     * Update a module instance.
     *
     * Parameters should be of the form ['key' => 'newValue']. Multiple values may be updated at the same time.
     * Available keys are alias, activity_id, name, description, slug, active, visible, mandatory, completion_condition_instance_id, enabled, user_id
     *
     * @param int $id ID of the module instance
     * @param array $attributes Attributes to be updated
     *
     * @return ModuleInstanceContract The updated module instance
     */
    public function update(int $id, array $attributes = []): ModuleInstanceContract;

    /**
     * Delete a module instance.
     *
     * @param int $id
     */
    public function delete(int $id);
}
