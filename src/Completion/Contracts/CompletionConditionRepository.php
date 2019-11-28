<?php


namespace BristolSU\Support\Completion\Contracts;



/**
 * Interface CompletionConditionRepository
 * @package BristolSU\Support\Completion\Contracts
 */
interface CompletionConditionRepository
{

    public function getByAlias($moduleAlias, $alias): CompletionCondition;

    public function getAllForModule($moduleAlias);
}
