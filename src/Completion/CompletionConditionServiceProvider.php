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

    public function register()
    {
        $this->app->bind(CompletionConditionFactoryContract::class, CompletionConditionFactory::class);
        $this->app->bind(CompletionConditionInstanceContract::class, CompletionConditionInstance::class);
        $this->app->bind(CompletionConditionInstanceRepositoryContract::class, CompletionConditionInstanceRepository::class);
        $this->app->bind(CompletionConditionManagerContract::class, CompletionConditionManager::class);
        $this->app->bind(CompletionConditionRepositoryContract::class, CompletionConditionRepository::class);
        $this->app->bind(CompletionConditionTesterContract::class, CompletionConditionTester::class);
    }

}