<?php

namespace BristolSU\Support\Filters;

use BristolSU\Support\Filters\Contracts\FilterManager as FilterManagerContract;

/**
 * Class FilterManager
 * @package BristolSU\Support\Filters
 */
class FilterManager implements FilterManagerContract
{

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @param $alias
     * @param $class
     */
    public function register($alias, $class)
    {
        $this->filters[$alias] = $class;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->filters;
    }

    /**
     * @param $alias
     * @return mixed
     * @throws \Exception
     */
    public function getClassFromAlias($alias)
    {
        if(!isset($this->filters[$alias])) {
            throw new \Exception(sprintf('Filter alias [%s] not found', $alias));
        }
        return $this->filters[$alias];
    }
}