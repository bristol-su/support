<?php


namespace BristolSU\Support\Filters;


use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Filters\Contracts\FilterInstance;
use BristolSU\Support\Filters\Contracts\FilterRepository;
use BristolSU\Support\Filters\Contracts\FilterTester as FilterTesterContract;
use Exception;

/**
 * Test filters for the result
 */
class FilterTester implements FilterTesterContract
{
    /**
     * Filter repository to resolve the filter out of
     * 
     * @var FilterRepository
     */
    private $repository;

    /**
     * @param FilterRepository $repository Filter repository to resolve the filter out of
     */
    public function __construct(FilterRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Tests a filter and returns the result.
     *
     * This function will test the filter instance with the given model.
     *
     * @param FilterInstance $filterInstance Filter instance to test
     * @param User|Group|Role $model Model to test the filter instance up
     * @return bool If the filter passes
     *
     * @throws Exception If the model type does not match the filter type
     */
    public function evaluate(FilterInstance $filterInstance, $model): bool
    {
        $filter = $this->repository->getByAlias($filterInstance->alias());
        $filter->setModel($model);
        return $filter->evaluate($filterInstance->settings());
    }
}
