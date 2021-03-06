<?php

namespace BristolSU\Support\Filters;

use BristolSU\Support\Filters\Contracts\FilterInstanceRepository as FilterInstanceRepositoryContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * Repository for retrieving and updating filter instances.
 */
class FilterInstanceRepository implements FilterInstanceRepositoryContract
{
    /**
     * Create a filter instance.
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
    public function create($attributes = [])
    {
        return FilterInstance::create($attributes);
    }

    /**
     * Get all filter instances.
     *
     * @return FilterInstance[]|Collection
     */
    public function all(): Collection
    {
        return FilterInstance::all();
    }

    /**
     * Get a filter instance by ID.
     *
     * @param int $id
     * @throws ModelNotFoundException
     * @return \BristolSU\Support\Filters\Contracts\FilterInstance
     *
     */
    public function getById(int $id): Contracts\FilterInstance
    {
        return FilterInstance::findOrFail($id);
    }

    /**
     * Update a filter instance.
     *
     * @param int $id
     * @param array $attributes
     *
     * @throws ModelNotFoundException
     * @return \BristolSU\Support\Filters\Contracts\FilterInstance
     */
    public function update(int $id, array $attributes): Contracts\FilterInstance
    {
        $filterInstance = $this->getById($id);
        $filterInstance->fill($attributes);
        $filterInstance->save();

        return $filterInstance;
    }

    /**
     * Delete a module instance.
     *
     * @param int $id
     *
     * @throws ModelNotFoundException
     */
    public function delete(int $id)
    {
        $filterInstance = $this->getById($id);
        $filterInstance->delete();
    }
}
