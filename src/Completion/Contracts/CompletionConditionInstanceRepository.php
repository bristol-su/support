<?php


namespace BristolSU\Support\Completion\Contracts;

use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class to interact with completion condition instances.
 */
interface CompletionConditionInstanceRepository
{
    /**
     * Create a completion condition instance.
     *
     * The attributes given should be of the form
     * [
     *      'alias' => 'alias of the completion condition',
     *      'name' => 'Name of the completion condition instance',
     *      'description' => 'Description of the completion condition instance',
     *      'settings' => 'Settings for the completion condition instance'
     * ]
     * @param array $attributes Attributes that make up the completion condition instance
     *
     * @return CompletionConditionInstance
     */
    public function create($attributes = []);

    /**
     * Get all completion condition instances.
     *
     * @return CompletionConditionInstance[]
     */
    public function all();

    /**
     * Get a completion condition instance by ID.
     *
     * @param int $id
     * @throws ModelNotFoundException If the completion condition instance was not found
     * @return CompletionConditionInstance
     *
     */
    public function getById(int $id): CompletionConditionInstance;

    /**
     * Update a completion condition instance.
     *
     * Any number of parameters may be passed to be updated. The possible attributes are alias, name, description and settings
     *
     * @param int $id
     * @param array $attributes
     * @throws ModelNotFoundException If the completion condition instance was not found
     * @return CompletionConditionInstance
     *
     */
    public function update(int $id, array $attributes): CompletionConditionInstance;
}
