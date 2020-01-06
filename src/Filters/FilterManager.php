<?php

namespace BristolSU\Support\Filters;

use BristolSU\Support\Filters\Contracts\FilterManager as FilterManagerContract;

/**
 * Register and retrieve filters
 */
class FilterManager implements FilterManagerContract
{

    /**
     * Holds the registered filters
     * 
     * Each filter alias in an index in the array, and the value is the filter class
     * 
     * @var array ['filter_alias' => 'FilterClass', ... ]
     */
    protected $filters = [];

    /**
     * Register a new filter
     * 
     * @param string $alias Alias of the filter
     * @param string $class Class of the filter
     */
    public function register($alias, $class)
    {
        $this->filters[$alias] = $class;
    }

    /**
     * Get all filters
     * 
     * @return array ['filter_alias' => 'FilterClass', ... ]
     */
    public function getAll()
    {
        return $this->filters;
    }

    /**
     * Get the class of a filter by its alias
     * 
     * @param string $alias Alias of the filter
     * @return string
     * @throws \Exception If the alias is not registered
     */
    public function getClassFromAlias($alias)
    {
        if(!isset($this->filters[$alias])) {
            throw new \Exception(sprintf('Filter alias [%s] not found', $alias));
        }
        return $this->filters[$alias];
    }
}