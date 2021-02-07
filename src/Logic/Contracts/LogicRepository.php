<?php

namespace BristolSU\Support\Logic\Contracts;

use BristolSU\Support\Logic\Logic;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Retrieve logic groups.
 */
interface LogicRepository
{
    /**
     * Create a logic group.
     *
     * The attributes should be a name and a description
     * [
     *      'name' => 'Name of the logic group',
     *      'description' => 'Description of the logic group'
     * ]
     *
     * @param array $attributes Attributes for the logic group
     * @return Logic Created logic group
     */
    public function create(array $attributes);

    /**
     * Get all logic groups.
     *
     * @return Logic[]
     */
    public function all();

    /**
     * Get a logic group by ID.
     *
     * @param int $id
     * @throws ModelNotFoundException
     * @return Logic
     */
    public function getById(int $id): Logic;

    /**
     * Update a logic group.
     *
     * @param int $id ID of the logic group
     * @param array $attributes
     * @throws ModelNotFoundException
     * @return Logic
     */
    public function update(int $id, array $attributes): Logic;

    /**
     * Delete a logic group.
     *
     * @param int $id
     * @throws ModelNotFoundException
     */
    public function delete(int $id);
}
