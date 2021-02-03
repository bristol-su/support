<?php


namespace BristolSU\Support\Filters\Contracts;

use BristolSU\Support\Filters\Contracts\Filters\Filter;

/**
 * Create a filter from a class.
 */
interface FilterFactory
{
    /**
     * Create a filter.
     *
     * @param string $className Class name of the filter
     *
     * @return Filter
     */
    public function createFilterFromClassName($className);
}
