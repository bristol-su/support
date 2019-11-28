<?php

namespace BristolSU\Support\Completion\Contracts;

/**
 * Interface CompletionConditionManager
 * @package BristolSU\Support\Completion\Contracts
 */
interface CompletionConditionManager
{

    public function register($moduleAlias, $alias, $class);

    public function getForModule($moduleAlias);

    public function getClassFromAlias($moduleAlias, $alias);
}