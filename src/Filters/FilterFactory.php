<?php


namespace BristolSU\Support\Filters;


use BristolSU\Support\Filters\Contracts\FilterFactory as FilterFactoryContract;
use Illuminate\Contracts\Container\Container;

/**
 * Class FilterFactory
 * @package BristolSU\Support\Filters
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
     * @param $className
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function createFilterFromClassName($className)
    {
        return $this->container->make($className);
    }
}
