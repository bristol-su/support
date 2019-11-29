<?php

namespace BristolSU\Support\Completion;

use BristolSU\Support\Completion\Contracts\CompletionConditionFactory as CompletionConditionFactoryContract;
use BristolSU\Support\Completion\Contracts\CompletionConditionInstance as CompletionConditionInstanceContract;
use BristolSU\Support\Completion\Contracts\CompletionConditionInstanceRepository as CompletionConditionInstanceRepositoryContract;
use BristolSU\Support\Completion\Contracts\CompletionConditionManager as CompletionConditionManagerContract;
use BristolSU\Support\Completion\Contracts\CompletionConditionRepository as CompletionConditionRepositoryContract;
use BristolSU\Support\Completion\Contracts\CompletionConditionTester as CompletionConditionTesterContract;
use Illuminate\Support\ServiceProvider;

class CompletionConditionServiceProvider extends ServiceProvider
{

    protected $completionConditions = [
//        'portalsystem_event_fired' => EventFired::class
    ];
    
    public function register()
    {
        $this->app->bind(CompletionConditionFactoryContract::class, CompletionConditionFactory::class);
        $this->app->bind(CompletionConditionInstanceContract::class, CompletionConditionInstance::class);
        $this->app->bind(CompletionConditionInstanceRepositoryContract::class, CompletionConditionInstanceRepository::class);
        $this->app->singleton(CompletionConditionManagerContract::class, CompletionConditionManager::class);
        $this->app->bind(CompletionConditionRepositoryContract::class, CompletionConditionRepository::class);
        $this->app->bind(CompletionConditionTesterContract::class, CompletionConditionTester::class);
    }

    public function boot()
    {
        foreach ($this->completionConditions as $alias => $class) {
            app(CompletionConditionManagerContract::class)->registerGlobalCondition($alias, $class);
        }
    }


}