<?php

namespace BristolSU\Support\Completion;

use BristolSU\Support\Completion\Contracts\CompletionConditionInstanceRepository as CompletionConditionInstanceRepositoryContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class to retrieve and change completion condition instances.
 */
class CompletionConditionInstanceRepository implements CompletionConditionInstanceRepositoryContract
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
    public function create($attributes = [])
    {
        return CompletionConditionInstance::create($attributes);
    }

    /**
     * Get all completion condition instances.
     *
     * @return CompletionConditionInstance[]
     */
    public function all()
    {
        return CompletionConditionInstance::all();
    }

    /**
     * Get a completion condition instance by ID.
     *
     * @param int $id
     * @throws ModelNotFoundException If the completion condition instance was not found
     * @return Contracts\CompletionConditionInstance
     *
     */
    public function getById(int $id): Contracts\CompletionConditionInstance
    {
        return CompletionConditionInstance::findOrFail($id);
    }

    /**
     * Update a completion condition instance.
     *
     * Any number of parameters may be passed to be updated. The possible attributes are alias, name, description and settings
     *
     * @param int $id
     * @param array $attributes
     * @throws ModelNotFoundException If the completion condition instance was not found
     * @return Contracts\CompletionConditionInstance
     *
     */
    public function update(int $id, array $attributes): Contracts\CompletionConditionInstance
    {
        $completionCondition = $this->getById($id);
        $completionCondition->fill($attributes);
        $completionCondition->save();

        return $completionCondition;
    }
}
