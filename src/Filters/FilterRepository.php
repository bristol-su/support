<?php


namespace BristolSU\Support\Filters;


use BristolSU\Support\Filters\Contracts\FilterFactory as FilterFactoryContract;
use BristolSU\Support\Filters\Contracts\FilterManager as FilterManagerContract;
use BristolSU\Support\Filters\Contracts\FilterRepository as FilterRepositoryContract;
use BristolSU\Support\Filters\Contracts\Filters\Filter;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

/**
 * Repository to retrieve filters
 */
class FilterRepository implements FilterRepositoryContract
{

    /**
     * Holds the filter manager contract
     * 
     * @var FilterManagerContract
     */
    private $manager;
    
    /**
     * Hold the filter factory to create a filter
     * 
     * @var FilterFactoryContract
     */
    private $filterFactory;

    /**
     * @param FilterManagerContract $manager Filter manager to resolve the filters from
     * @param FilterFactoryContract $filterFactory Factory to create the filters
     */
    public function __construct(FilterManagerContract $manager, FilterFactoryContract $filterFactory)
    {
        $this->manager = $manager;
        $this->filterFactory = $filterFactory;
    }

    /**
     * Get a filter by alias
     * 
     * @param string $alias Alias of the filter
     * @return Filter Filter class with the given alias
     * @throws \Exception If the filter is not registered
     */
    public function getByAlias($alias)
    {
        $class = $this->manager->getClassFromAlias($alias);
        return $this->filterFactory->createFilterFromClassName($class);
    }

    /**
     * Get all registered filters.
     *
     * @return array
     */
    public function getAll()
    {
        $classes = $this->manager->getAll();

        $filters = [];
        foreach ($classes as $class) {
            $filters[] = $this->filterFactory->createFilterFromClassName($class);
        }
        return $filters;
    }
}
