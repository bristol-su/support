<?php


namespace BristolSU\Support\Completion;


use BristolSU\Support\Completion\CompletionTester;
use BristolSU\Support\Completion\ConfigCompletionEventRepository;
use BristolSU\Support\Completion\Contracts\CompletionEventRepository as CompletionEventRepositoryContract;
use BristolSU\Support\Completion\Contracts\CompletionTester as CompletionTesterContract;
use Illuminate\Support\ServiceProvider;

class CompletionServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(CompletionEventRepositoryContract::class, ConfigCompletionEventRepository::class);
        $this->app->bind(CompletionTesterContract::class, CompletionTester::class);
    }

    public function boot()
    {

    }


}
