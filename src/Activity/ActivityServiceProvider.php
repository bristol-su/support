<?php

namespace BristolSU\Support\Activity;

use Illuminate\Support\ServiceProvider;
use BristolSU\Support\Activity\Contracts\Repository as ActivityRepositoryContract;
use BristolSU\Support\Activity\Repository as ActivityRepository;

class ActivityServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(ActivityRepositoryContract::class, ActivityRepository::class);

    }

    public function boot()
    {
        
    }

}