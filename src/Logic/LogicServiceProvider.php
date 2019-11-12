<?php

namespace BristolSU\Support\Logic;

use BristolSU\Support\Logic\Audience\AudienceFactory;
use BristolSU\Support\Logic\Audience\LogicAudience;
use BristolSU\Support\Logic\Commands\CacheFilters;
use BristolSU\Support\Logic\Contracts\Audience\AudienceFactory as AudienceFactoryContract;
use BristolSU\Support\Logic\Contracts\Audience\LogicAudience as LogicAudienceContract;
use BristolSU\Support\Logic\Contracts\LogicRepository as LogicRepositoryContract;
use BristolSU\Support\Logic\Contracts\LogicTester as LogicTesterContract;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Support\ServiceProvider;

/**
 * Class LogicServiceProvider
 * @package BristolSU\Support\Logic
 */
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
}
