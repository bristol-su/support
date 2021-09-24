<?php

namespace BristolSU\Support\Logic;

use BristolSU\Support\Logic\Audience\AudienceMemberFactory;
use BristolSU\Support\Logic\Audience\DatabaseLogicAudience;
use BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory as AudienceFactoryContract;
use BristolSU\Support\Logic\Contracts\Audience\LogicAudience as LogicAudienceContract;
use BristolSU\Support\Logic\Contracts\LogicRepository as LogicRepositoryContract;
use BristolSU\Support\Logic\Contracts\LogicTester as LogicTesterContract;
use BristolSU\Support\Logic\DatabaseDecorator\CacheLogic;
use BristolSU\Support\Logic\DatabaseDecorator\LogicDatabaseDecorator;
use Illuminate\Support\ServiceProvider;

/**
 * Logic Service Provider.
 */
class LogicServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * - Bind implementations to interfaces
     *
     */
    public function register()
    {
        $this->app->bind(LogicRepositoryContract::class, LogicRepository::class);
        $this->app->bind(LogicTesterContract::class, LogicTester::class);
        $this->app->bind(AudienceFactoryContract::class, AudienceMemberFactory::class);
        $this->app->extend(LogicTesterContract::class, fn(LogicTesterContract $logicTester) => new LogicDatabaseDecorator($logicTester));

    }

    public function boot()
    {
        $this->commands([CacheLogic::class]);

    }
}
