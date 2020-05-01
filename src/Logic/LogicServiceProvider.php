<?php

namespace BristolSU\Support\Logic;

use BristolSU\Support\Logic\Audience\AudienceMemberFactory;
use BristolSU\Support\Logic\Audience\CachedAudienceMemberFactory;
use BristolSU\Support\Logic\Audience\LogicAudience;
use BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory as AudienceFactoryContract;
use BristolSU\Support\Logic\Contracts\Audience\LogicAudience as LogicAudienceContract;
use BristolSU\Support\Logic\Contracts\LogicRepository as LogicRepositoryContract;
use BristolSU\Support\Logic\Contracts\LogicTester as LogicTesterContract;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\ServiceProvider;

/**
 * Logic Service Provider
 */
class LogicServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * 
     * - Bind implementations to interfaces
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LogicRepositoryContract::class, LogicRepository::class);
        $this->app->bind(LogicTesterContract::class, LogicTester::class);
        $this->app->bind(LogicAudienceContract::class, LogicAudience::class);
        $this->app->bind(AudienceFactoryContract::class, AudienceMemberFactory::class);
        
        $this->app->extend(AudienceFactoryContract::class, function(AudienceFactoryContract $audienceMemberFactory, $app) {
            return new CachedAudienceMemberFactory($audienceMemberFactory, $app->make(Repository::class));
        });
    }
}
