<?php

namespace BristolSU\Support\Completion\Contracts;

/**
 * Repository for completion conditions
 */
interface CompletionConditionRepository
{

    /**
     * Get a completion condition by its alias
     * 
     * @param string $moduleAlias Module calling for the completion condition
     * @param string $alias Alias of the completion condition
     * 
     * @return CompletionCondition Constructed completion condition
     */
    public function getByAlias($moduleAlias, $alias): CompletionCondition;

    /**
     * Get all completion conditions usable by a module
     * 
     * @param string $moduleAlias Module to get all completion conditions for
     * @return CompletionCondition[]
     */
    public function getAllForModule($moduleAlias);
}
