<?php

namespace BristolSU\Support\Logic;

use BristolSU\Support\Filters\ConfigFilterRepository;
use BristolSU\Support\Filters\Contracts\FilterFactory as FilterFactoryContract;
use BristolSU\Support\Filters\Contracts\FilterInstance as FilterInstanceContract;
use BristolSU\Support\Filters\Contracts\FilterRepository as FilterRepositoryContract;
use BristolSU\Support\Filters\Contracts\FilterTester as FilterTesterContract;
use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Filters\FilterTester;
use BristolSU\Support\Logic\AudienceFactory;
use BristolSU\Support\Logic\Contracts\AudienceFactory as AudienceFactoryContract;
use BristolSU\Support\Logic\Contracts\LogicAudience as LogicAudienceContract;
use BristolSU\Support\Logic\Contracts\LogicRepository as LogicRepositoryContract;
use BristolSU\Support\Logic\Contracts\LogicTester as LogicTesterContract;
use BristolSU\Support\Logic\LogicAudience;
use BristolSU\Support\Logic\LogicRepository;
use BristolSU\Support\Logic\LogicTester;
use Illuminate\Support\ServiceProvider;

class LogicServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LogicRepositoryContract::class, LogicRepository::class);
        $this->app->bind(LogicTesterContract::class, LogicTester::class);
        $this->app->bind(LogicAudienceContract::class, LogicAudience::class);
        $this->app->bind(AudienceFactoryContract::class, AudienceFactory::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
