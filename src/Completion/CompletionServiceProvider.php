<?php


namespace BristolSU\Support\Completion;


use BristolSU\Support\Completion\Contracts\CompletionEventManager as CompletionEventManagerContract;
use BristolSU\Support\Completion\Contracts\CompletionEventRepository as CompletionEventRepositoryContract;
use BristolSU\Support\Completion\Contracts\CompletionTester as CompletionTesterContract;
use Illuminate\Support\ServiceProvider;

class CompletionServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(CompletionEventRepositoryContract::class, CompletionEventRepository::class);
        $this->app->bind(CompletionTesterContract::class, CompletionTester::class);
        $this->app->singleton(CompletionEventManagerContract::class, function($app) {
            return new CompletionEventManager;
        });
    }

    public function boot()
    {

    }


}
