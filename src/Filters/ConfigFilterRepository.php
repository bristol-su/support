<?php


namespace BristolSU\Support\Filters;


use BristolSU\Support\Filters\Contracts\FilterFactory as FilterFactoryContract;
use BristolSU\Support\Filters\Contracts\FilterRepository as FilterRepositoryContract;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class ConfigFilterRepository implements FilterRepositoryContract
{

    /**
     * @var ConfigRepository
     */
    private $config;
    /**
     * @var FilterFactoryContract
     */
    private $filterFactory;

    public function __construct(ConfigRepository $config, FilterFactoryContract $filterFactory)
    {

        $this->config = $config;
        $this->filterFactory = $filterFactory;
    }

    public function getByAlias($alias)
    {
        $class = $this->config->get('support.filters.' . $alias);
        if($class === null) {
            throw new \Exception('Alias not found');
        }
        return $this->filterFactory->createFilterFromClassName($class);
    }

    public function getAll()
    {
        $classes = $this->config->get('support.filters');

        $filters = [];
        foreach($classes as $class) {
            $filters[] = $this->filterFactory->createFilterFromClassName($class);
        }
        return $filters;
    }
}
