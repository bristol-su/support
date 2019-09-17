<?php


namespace BristolSU\Support\Filters\Contracts;


use BristolSU\Support\Filters\Contracts\Filters\Filter;

interface FilterRepository
{

    /**
     * Get a filter instance by alias
     *
     * @param string $alias
     * @return Filter mixed
     */
    public function getByAlias($alias);

    public function getAll();
}
