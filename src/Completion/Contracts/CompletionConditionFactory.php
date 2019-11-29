<?php


namespace BristolSU\Support\Completion\Contracts;


interface CompletionConditionFactory
{

    public function createCompletionConditionFromClassName($className, $moduleAlias): CompletionCondition;

        
}