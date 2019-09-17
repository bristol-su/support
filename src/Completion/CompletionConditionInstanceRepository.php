<?php


namespace BristolSU\Support\Completion;


use BristolSU\Support\Completion\Contracts\CompletionConditionInstanceRepository as CompletionConditionInstanceRepositoryContract;

/**
 * Class to retrieve and change completion condition instances
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
     * Get all completion condition instances
     *
     * @return CompletionConditionInstance[]
     */
    public function all()
    {
        return CompletionConditionInstance::all();
    }
}
