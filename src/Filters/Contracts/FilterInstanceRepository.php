<?php

namespace BristolSU\Support\Filters\Contracts;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * Repository for retrieving and updating filter instances
 */
interface FilterInstanceRepository
{

    /**
     * Create a filter instance
     * 
     * The attributes should be
     * [
     *      'alias' => 'alias_of_the_filter',
     *      'name' => 'Name of the filter',
     *      'settings' => [], // Settings for the filter
     *      'logic_id' => '', // ID of the logic row
     *      'logic_type' => 'all_true', 'any_true', 'all_false', 'any_false' // How the filter should be tested in the logic group
     * ]
     * 
     * @param array $attributes Attributes
     * @return FilterInstance
     */
    public function create($attributes = []);

    /**
     * Get all filter instances
     * 
     * @return FilterInstance[]|Collection
     */
    public function all(): Collection;

    /**
     * Get a filter instance by ID
     * 
     * @param int $id
     * @return FilterInstance
     * 
     * @throws ModelNotFoundException
     */
    public function getById(int $id): FilterInstance;

    /**
     * Update a filter instance 
     * 
     * @param int $id
     * @param array $attributes
     * 
     * @return FilterInstance
     * @throws ModelNotFoundException
     */
    public function update(int $id, array $attributes): FilterInstance;

    /**
     * Delete a module instance
     * 
     * @param int $id
     * @return void
     * 
     * @throws ModelNotFoundException
     */
    public function delete(int $id);

}
