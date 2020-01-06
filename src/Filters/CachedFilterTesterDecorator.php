<?php

namespace BristolSU\Support\Filters;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Filters\Contracts\FilterInstance as FilterInstanceContract;
use BristolSU\Support\Filters\Contracts\FilterTester as FilterTesterContract;
use Illuminate\Contracts\Cache\Repository;

/**
 * Decorator to cache filter results
 */
class CachedFilterTesterDecorator implements FilterTesterContract
{

    /**
     * Holds the underlying filter tester
     * 
     * @var FilterTesterContract
     */
    private $filterInstanceTester;
    
    /**
     * Cache implementation
     * 
     * @var Repository
     */
    private $cache;

    /**
     * @param FilterTesterContract $filterInstanceTester Underlying filter instance tester
     * @param Repository $cache Cache implementation
     */
    public function __construct(FilterTesterContract $filterInstanceTester, Repository $cache)
    {
        $this->filterInstanceTester = $filterInstanceTester;
        $this->cache = $cache;
    }

    /**
     * Cache the test result
     * 
     * @param FilterInstanceContract $filterInstance Filter instance to test
     * @param Group|Role|User $model Model to test
     * @return bool If the filter passes
     */
    public function evaluate(FilterInstanceContract $filterInstance, $model): bool
    {
        return $this->cache->remember($this->getKey($filterInstance, $model), 7200, function () use ($filterInstance, $model) {
            return $this->filterInstanceTester->evaluate($filterInstance, $model);
        });
    }

    /**
     * Get a unique key for the filter test
     * 
     * @param FilterInstanceContract $filterInstance Filter instance to test
     * @param User|Group|Role $model Model to test
     * @return string Get the unique token
     */
    private function getKey(FilterInstanceContract $filterInstance, $model)
    {
        return CachedFilterTesterDecorator::class . 
            'FilterInstance:' . $filterInstance->id . ';' .
            'Model:' . get_class($model) . '::' . $model->id;
    }

}