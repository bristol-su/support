<?php


namespace BristolSU\Support\Filters;


use BristolSU\Support\Filters\Contracts\FilterInstance;
use BristolSU\Support\Filters\Contracts\FilterRepository;
use BristolSU\Support\Filters\Contracts\FilterTester as FilterTesterContract;
use Illuminate\Support\Facades\Log;

/**
 * Class FilterTester
 * @package BristolSU\Support\Filters
 */
class FilterTester implements FilterTesterContract
{
    /**
     * @var FilterRepository
     */
    private $repository;

    /**
     * FilterTester constructor.
     * @param FilterRepository $repository
     */
    public function __construct(FilterRepository $repository)
    {
        $this->repository = $repository;
    }


    public function evaluate(FilterInstance $filterInstance, $model): bool
    {
//        Log::info('Testing FI ' . $filterInstance->id . ' with model ' . $model->id . ' (' . get_class($model) . ')');
        $filter = $this->repository->getByAlias($filterInstance->alias());
        $filter->setModel($model);
        return $filter->evaluate($filterInstance->settings());
    }
}
