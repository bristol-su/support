<?php

namespace BristolSU\Support\Completion;

use BristolSU\Support\Completion\Contracts\CompletionConditionManager as CompletionConditionManagerContract;

/**
 * Class CompletionConditionManager
 * @package BristolSU\Support\Completion
 */
class CompletionConditionManager implements CompletionConditionManagerContract
{

    /**
     * @var array
     */
    protected $conditions = [];

    protected $global = [];

    public function registerGlobalCondition($alias, $class)
    {
        $this->global[$alias] = $class;
    }
    
    /**
     * @param $alias
     * @param $class
     */
    public function register($moduleAlias, $alias, $class)
    {
        if(!isset($this->conditions[$moduleAlias])) {
            $this->conditions[$moduleAlias] = [];
        }
        $this->conditions[$moduleAlias][$alias] = $class;
    }

    /**
     * @return array
     */
    public function getForModule($moduleAlias)
    {
        return (isset($this->conditions[$moduleAlias])?
            array_merge($this->conditions[$moduleAlias], $this->global):[]);
    }

    /**
     * @param $alias
     * @return mixed
     * @throws \Exception
     */
    public function getClassFromAlias($moduleAlias, $alias)
    {
        if(isset($this->conditions[$moduleAlias]) && isset($this->conditions[$moduleAlias][$alias])) {
            return $this->conditions[$moduleAlias][$alias];
        }
        if(isset($this->global[$alias])) {
            return $this->global[$alias];
        }
        throw new \Exception(sprintf('Completion Condition alias [%s] not found for module [%s]', $alias, $moduleAlias));
    }
}