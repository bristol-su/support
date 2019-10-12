<?php

namespace BristolSU\Support\Filters;

use BristolSU\Support\Filters\Contracts\FilterManager as FilterManagerContract;

class FilterManager implements FilterManagerContract
{

    protected $filters = [];
    
    public function register($alias, $class)
    {
        $this->filters[$alias] = $class;
    }
    
    public function getAll()
    {
        return $this->filters;
    }

    public function getClassFromAlias($alias)
    {
        if(!isset($this->filters[$alias])) {
            throw new \Exception(sprintf('Filter alias [%s] not found', $alias));
        }
        return $this->filters[$alias];
    }
}