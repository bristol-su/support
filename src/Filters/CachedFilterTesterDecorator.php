<?php

namespace BristolSU\Support\Filters;

use BristolSU\Support\Filters\Contracts\FilterInstance as FilterInstanceContract;
use BristolSU\Support\Filters\Contracts\FilterTester as FilterTesterContract;
use Illuminate\Contracts\Cache\Repository;

class CachedFilterTesterDecorator implements FilterTesterContract
{

    /**
     * @var FilterTesterContract
     */
    private $filterInstanceTester;
    /**
     * @var Repository
     */
    private $cache;

    public function __construct(FilterTesterContract $filterInstanceTester, Repository $cache)
    {
        $this->filterInstanceTester = $filterInstanceTester;
        $this->cache = $cache;
    }
    
    public function evaluate(FilterInstanceContract $filterInstance, $model): bool
    {
        return $this->cache->remember($this->getKey($filterInstance, $model), 7200, function () use ($filterInstance, $model) {
            return $this->filterInstanceTester->evaluate($filterInstance, $model);
        });
    }

    private function getKey(FilterInstanceContract $filterInstance, $model)
    {
        return CachedFilterTesterDecorator::class . 
            'FilterInstance:' . $filterInstance->id . ';' .
            'Model:' . get_class($model) . '::' . $model->id;
    }

}