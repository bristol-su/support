<?php

namespace BristolSU\Support\Filters\Jobs;

use BristolSU\Support\Filters\Contracts\FilterInstance;
use BristolSU\Support\Filters\Contracts\FilterTester;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class CacheFilter implements ShouldQueue
{
    use Queueable;


    /**
     * @var FilterInstance
     */
    private $filterInstance;
    private $model;

    public function __construct(FilterInstance $filterInstance, $model)
    {
        $this->filterInstance = $filterInstance;
        $this->model = $model;
    }

    public function handle(FilterTester $filterTester)
    {
        $filterTester->evaluate($this->filterInstance, $this->model);
    }
}