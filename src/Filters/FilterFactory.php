<?php


namespace BristolSU\Support\Filters;


use BristolSU\Support\Filters\Contracts\FilterFactory as FilterFactoryContract;
use BristolSU\Support\Filters\Contracts\Filters\Filter;
use Illuminate\Contracts\Container\Container;

/**
 * Create a filter from a class
 */
class FilterFactory implements FilterFactoryContract
{
    /**
     * @var Container
     */
    private $container;

    /**
     * FilterFactory constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Resolve a filter from the container
     *
     * @param string $className Class name of the filter
     *
     * @return Filter
     * @throws \Illuminate\Contracts\Container\BindingResolutionException If the class is not found
     */
    public function createFilterFromClassName($className)
    {
        return $this->container->make($className);
    }
}
