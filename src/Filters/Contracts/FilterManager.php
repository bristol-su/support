<?php

namespace BristolSU\Support\Filters\Contracts;

use Exception;

/**
 * Register and retrieve filters
 */
interface FilterManager
{

    /**
     * Register a new filter
     * 
     * @param string $alias Alias of the filter
     * @param string $class Class of the filter
     * @return void
     */
    public function register($alias, $class);

    /**
     * Get all the registered filters
     * 
     * Return filters represented as an array, e.g. ['filter_alias_1' => 'FilterClass1', ... ]
     * 
     * @return array filters
     */
    public function getAll();

    /**
     * Get the class name from a filter alias
     * 
     * @param string $alias Alias of the filter
     * @return string
     * 
     * @throws Exception If the alias is not registered
     */
    public function getClassFromAlias($alias);
}