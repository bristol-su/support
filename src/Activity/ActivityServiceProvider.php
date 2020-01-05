<?php

namespace BristolSU\Support\Activity;

use BristolSU\Support\Activity\Middleware\InjectActivityInstance;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use BristolSU\Support\Activity\Contracts\Repository as ActivityRepositoryContract;
use BristolSU\Support\Activity\Repository as ActivityRepository;

/**
 * ActivityServiceProvider
 */
class ActivityServiceProvider extends ServiceProvider
{

    public function register()
    {
        // Bind contracts
        $this->app->bind(ActivityRepositoryContract::class, ActivityRepository::class);

    }

    public function boot()
    {
        // Inject the activity instance middleware
        $this->app['router']->pushMiddlewareToGroup('activity', InjectActivityInstance::class);

        // Set up route model binding
        Route::bind('activity_slug', function ($slug) {
            return Activity::where(['slug' => $slug])->firstOrFail();
        });
        
    }

}