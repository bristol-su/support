<?php

namespace BristolSU\Support\ModuleInstance\Contracts;

use Illuminate\Support\Collection;

/**
 * Handle interacting with module instances
 */
interface ModuleInstanceRepository
{
    /**
     * Get a module instance by ID
     * 
     * @param int $id ID of the module instance
     * @return ModuleInstance Module instance with the given ID
     */
    public function getById(int $id) : ModuleInstance;

    /**
     * Create a new module instance
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
    public function create(array $attributes) : ModuleInstance;

    /**
     * Get all module instances
     * 
     * @return ModuleInstance[]|Collection
     */
    public function all(): Collection;

    /**
     * Get all module instances belonging to a module alias
     * 
     * @param string $alias Alias of the module
     * @return ModuleInstance[]|Collection
     */
    public function allWithAlias(string $alias = ''): Collection;
    
}
