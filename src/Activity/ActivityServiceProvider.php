<?php

namespace BristolSU\Support\Activity;

use BristolSU\Support\Activity\Contracts\Repository as ActivityRepositoryContract;
use BristolSU\Support\Activity\Middleware\InjectActivity;
use BristolSU\Support\Activity\Repository as ActivityRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * ActivityServiceProvider.
 */
class ActivityServiceProvider extends ServiceProvider
{
    /**
     * Register.
     *
     * - Bind the activity repository contract to an implementation
     */
    public function register()
    {
        $this->app->bind(ActivityRepositoryContract::class, ActivityRepository::class);
    }

    /**
     * Boot.
     *
     * - Inject the activity
     * - Set up route model binding
     */
    public function boot()
    {
        $this->app['router']->pushMiddlewareToGroup('activity', InjectActivity::class);

        Route::bind('activity_slug', function ($slug) {
            return Activity::where(['slug' => $slug])->firstOrFail();
        });
    }
}
