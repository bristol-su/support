<?php


namespace BristolSU\Support\Completion\Contracts;


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
     * Get all completion condition instances
     * 
     * @return CompletionConditionInstance[]
     */
    public function all();
}
