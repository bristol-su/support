<?php


namespace BristolSU\Support\Logic;

use BristolSU\Support\Logic\Contracts\LogicRepository as LogicRepositoryContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Handles saving and retrieving logic group information.
 */
class LogicRepository implements LogicRepositoryContract
{
    /**
     * Create a logic group in the database.
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
    public function create(array $attributes)
    {
        return Logic::create($attributes);
    }

    /**
     * Get all logic groups.
     *
     * @return Logic[]
     */
    public function all()
    {
        return Logic::all();
    }

    /**
     * Get a logic group by ID.
     *
     * @param int $id
     * @throws ModelNotFoundException
     * @return Logic
     */
    public function getById(int $id): Logic
    {
        return Logic::findOrFail($id);
    }

    /**
     * Update a logic group.
     *
     * @param int $id ID of the logic group
     * @param array $attributes
     * @throws ModelNotFoundException
     * @return Logic
     */
    public function update(int $id, array $attributes): Logic
    {
        $logic = $this->getById($id);
        $logic->fill($attributes);
        $logic->save();

        return $logic;
    }

    /**
     * Delete a logic group.
     *
     * @param int $id
     * @throws ModelNotFoundException
     * @throws \Exception
     */
    public function delete(int $id)
    {
        $logic = $this->getById($id);
        $logic->delete();
    }
}
