<?php

namespace BristolSU\Support\Activity;

use BristolSU\Support\Activity\Middleware\InjectActivityInstance;
use Illuminate\Support\Facades\Route;
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
        $this->app['router']->pushMiddlewareToGroup('module', InjectActivityInstance::class);
        Route::bind('activity_slug', function ($slug) {
            return Activity::where(['slug' => $slug])->firstOrFail();
        });
        
    }

}