<?php


namespace BristolSU\Support\Completion\Contracts;

/**
 * Create a completion condition class
 */
interface CompletionConditionFactory
{

    /**
     * Create a completion condition class ready for use given the name of the classs
     * 
     * @param string $className Name of the completion condition class
     * @param string $moduleAlias Module alias.
     * @return CompletionCondition The built completion condition
     */
    public function createCompletionConditionFromClassName($className, $moduleAlias): CompletionCondition;

        
}