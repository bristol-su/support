<?php

namespace BristolSU\Support\Completion;

use BristolSU\Support\Completion\Contracts\CompletionConditionManager as CompletionConditionManagerContract;

/**
 * Register and retrieve completion conditions
 */
class CompletionConditionManager implements CompletionConditionManagerContract
{

    /**
     * Holds the completion conditions
     * 
     * [
     *      'module_alias' => [
     *          'condition_alias' => 'ConditionClass',
     *          ...
     *      ],
     *      ...
     * ]
     * @var array
     */
    protected $conditions = [];

    /**
     * Holds global completion conditions relevant for all modules
     * 
     * [
     *      'condition_alias' => 'ConditionClass',
     *      ...
     * ]
     * 
     * @var array 
     */
    protected $global = [];

    /**
     * Register a condition which all modules can use
     * 
     * @param string $alias Alias of the completion condition
     * @param string $class Completion condition class name
     * 
     * @return void
     */
    public function registerGlobalCondition($alias, $class)
    {
        $this->global[$alias] = $class;
    }

    /**
     * Register a completion condition for a module
     * 
     * @param string $moduleAlias Module which owns the completion condition
     * @param string $alias Alias of the completion condition
     * @param string $class Class name for the completion condition
     * 
     * @return void
     */
    public function register($moduleAlias, $alias, $class)
    {
        if (!isset($this->conditions[$moduleAlias])) {
            $this->conditions[$moduleAlias] = [];
        }
        $this->conditions[$moduleAlias][$alias] = $class;
    }

    /**
     * Get all completion conditions for a given module
     *
     * @param string $moduleAlias Alias of the module
     * 
     * @return array [ 'condition_alias' => 'ConditionClass', ...]
     */
    public function getForModule($moduleAlias)
    {
        return (isset($this->conditions[$moduleAlias]) ?
            array_merge($this->conditions[$moduleAlias], $this->global) : []);
    }

    /**
     * Get a class name given the completion condition alias
     * 
     * @param string $moduleAlias Module requesting the completion condition alias
     * @param string $alias Alias of the completion condition
     * 
     * @return string Class name of the completion condition
     * 
     * @throws \Exception If the condition was not registered for the given module
     */
    public function getClassFromAlias($moduleAlias, $alias)
    {
        if (isset($this->conditions[$moduleAlias]) && isset($this->conditions[$moduleAlias][$alias])) {
            return $this->conditions[$moduleAlias][$alias];
        }
        if (isset($this->global[$alias])) {
            return $this->global[$alias];
        }
        throw new \Exception(sprintf('Completion Condition alias [%s] not found for module [%s]', $alias, $moduleAlias));
    }
}