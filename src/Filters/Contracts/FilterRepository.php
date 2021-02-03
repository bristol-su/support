<?php

namespace BristolSU\Support\Filters\Contracts;

use BristolSU\Support\Filters\Contracts\Filters\Filter;

/**
 * Repository to retrieve filters.
 */
interface FilterRepository
{
    /**
     * Get a filter by alias.
     *
     * Returns a filter given an alias
     *
     * @param string $alias Alias of the filter
     * @return Filter Resolved filter
     */
    public function getByAlias($alias);

    /**
     * Get all filters registered.
     *
     * @return Filter[]
     */
    public function getAll();
}
