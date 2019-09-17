<?php


namespace BristolSU\Support\Completion\Contracts;


/**
 * Completion Condition Instance model
 */
interface CompletionConditionInstance
{
    /**
     * Get the name of the completion condition instance
     * 
     * @return string
     */
    public function name();

    /**
     * Get the alias of the completion condition instance
     *
     * @return string
     */
    public function alias();

    /**
     * Get the settings of the completion condition instance to pass to the completion condition
     * 
     * @return array
     */
    public function settings();
}
