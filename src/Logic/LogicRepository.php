<?php


namespace BristolSU\Support\Logic;


use BristolSU\Support\Logic\Contracts\LogicRepository as LogicRepositoryContract;

/**
 * Handles saving and retrieving logic group information
 */
class LogicRepository implements LogicRepositoryContract
{

    /**
     * Create a logic group in the database
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
     * Get all logic groups
     *
     * @return Logic[]
     */
    public function all()
    {
        return Logic::all();
    }
}
