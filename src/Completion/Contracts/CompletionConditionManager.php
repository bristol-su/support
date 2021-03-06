<?php

namespace BristolSU\Support\Completion\Contracts;

use Exception;

/**
 * Allow for registration of completion conditions.
 */
interface CompletionConditionManager
{
    /**
     * Register a completion condition usable by all modules.
     *
     * @param string $alias Alias of the completion condition
     * @param string $class Class name for the completion condition
     *
     */
    public function registerGlobalCondition($alias, $class);

    /**
     * Register a completion condition specific to a module.
     *
     * @param string $moduleAlias Module to which the completion condition belongs
     * @param string $alias Alias of the completion condition
     * @param string $class Class name for the completion condition
     *
     */
    public function register($moduleAlias, $alias, $class);

    /**
     * Get all completion conditions for a module.
     *
     * @param string $moduleAlias Module alias to get completion conditions for
     * @return mixed Array of aliases and classes e.g. ['alias1' => 'classname', ...]
     */
    public function getForModule($moduleAlias);

    /**
     * Get a class name of a completion condition given an alias.
     *
     * @param string $moduleAlias Alias of the module calling for a completion condition
     * @param string $alias Alias of the completion condition
     * @throws Exception If the alias is not registered or accessible
     * @return string Class name
     *
     */
    public function getClassFromAlias($moduleAlias, $alias);
}
