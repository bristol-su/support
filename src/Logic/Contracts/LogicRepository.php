<?php

namespace BristolSU\Support\Logic\Contracts;

use BristolSU\Support\Logic\Logic;

/**
 * Retrieve logic groups
 */
interface LogicRepository
{

    /**
     * Create a logic group
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
     * Get all logic groups
     * 
     * @return Logic[]
     */
    public function all();
}
