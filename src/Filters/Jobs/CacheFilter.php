<?php

namespace BristolSU\Support\Filters\Jobs;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Filters\Contracts\FilterInstance;
use BristolSU\Support\Filters\Contracts\FilterTester;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Job to cache a filter result
 */
class CacheFilter implements ShouldQueue
{
    use Queueable;

    /**
     * Holds the filter instance to get the result from
     * 
     * @var FilterInstance 
     */
    private $filterInstance;

    /**
     * Model to test the filter against
     * 
     * @var User|Group|Role
     */
    private $model;

    /**
     * Get the filter instance
     * 
     * @return FilterInstance
     */
    public function filterInstance()
    {
        return $this->filterInstance;
    }

    /**
     * Get the model
     * 
     * @return Group|Role|User
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * @param FilterInstance $filterInstance Filter instance to resolve the filter from
     * @param User|Group|Role $model Model to test the filter against
     */
    public function __construct(FilterInstance $filterInstance, $model)
    {
        $this->filterInstance = $filterInstance;
        $this->model = $model;
    }

    /**
     * Handle the job
     * 
     * Test the filter. If the cached decorator is bound to the container, the result will be cached
     * 
     * @param FilterTester $filterTester Filter tester to test the result with. It should cache the result
     * @throws \Exception
     */
    public function handle(FilterTester $filterTester)
    {
        $filterTester->evaluate($this->filterInstance(), $this->model());
    }
}