<?php


namespace BristolSU\Support\Filters;


use BristolSU\Support\Filters\Contracts\FilterInstance;
use BristolSU\Support\Filters\Contracts\FilterRepository;
use BristolSU\Support\Filters\Contracts\FilterTester as FilterTesterContract;

class FilterTester implements FilterTesterContract
{

    /**
     * @var FilterRepository
     */
    private $repository;

    public function __construct(FilterRepository $repository)
    {
        $this->repository = $repository;
    }

    public function evaluate(FilterInstance $filterInstance): bool
    {
        $filter = $this->repository->getByAlias($filterInstance->alias());
        if(!$filter->hasModel()) {
            return false;
        }
        return $filter->evaluate($filterInstance->settings());
    }

}
