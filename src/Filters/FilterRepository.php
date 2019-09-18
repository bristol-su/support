<?php


namespace BristolSU\Support\Filters;


use BristolSU\Support\Filters\Contracts\FilterFactory as FilterFactoryContract;
use BristolSU\Support\Filters\Contracts\FilterManager as FilterManagerContract;
use BristolSU\Support\Filters\Contracts\FilterRepository as FilterRepositoryContract;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class FilterRepository implements FilterRepositoryContract
{

    /**
     * @var FilterManagerContract
     */
    private $manager;
    /**
     * @var FilterFactoryContract
     */
    private $filterFactory;

    public function __construct(FilterManagerContract $manager, FilterFactoryContract $filterFactory)
    {
        $this->manager = $manager;
        $this->filterFactory = $filterFactory;
    }

    public function getByAlias($alias)
    {
        $class = $this->manager->getClassFromAlias($alias);
        return $this->filterFactory->createFilterFromClassName($class);
    }

    public function getAll()
    {
        $classes = $this->manager->getAll();

        $filters = [];
        foreach($classes as $class) {
            $filters[] = $this->filterFactory->createFilterFromClassName($class);
        }
        return $filters;
    }
}
