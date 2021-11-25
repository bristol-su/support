<?php

namespace BristolSU\Support\Filters;

use BristolSU\Support\Filters\Contracts\FilterManager as FilterManagerContract;
use BristolSU\Support\Filters\Listeners\RefreshFilterResults;
use Illuminate\Support\Facades\Event;

/**
 * Register and retrieve filters.
 */
class FilterManagerEventRegistrationDecorator implements FilterManagerContract
{

    private FilterManagerContract $baseFilterManager;

    public function __construct(FilterManagerContract $baseFilterManager)
    {
        $this->baseFilterManager = $baseFilterManager;
    }


    /**
     * Register a new filter.
     *
     * @param string $alias Alias of the filter
     * @param string $class Class of the filter
     */
    public function register($alias, $class)
    {
        $this->baseFilterManager->register($alias, $class);
        Event::listen($class::listensTo(), [RefreshFilterResults::class, 'handle']);
    }

    public function getAll()
    {
        return $this->baseFilterManager->getAll();
    }

    public function getClassFromAlias($alias)
    {
        return $this->baseFilterManager->getClassFromAlias($alias);
    }
}
