<?php

namespace BristolSU\Support\Activity;

use BristolSU\Support\Activity\Middleware\CheckActivityFor;
use BristolSU\Support\Activity\Middleware\CheckAdminActivityFor;
use BristolSU\Support\Activity\Middleware\CheckLoggedIntoActivityForType;
use BristolSU\Support\Activity\Middleware\InjectActivityInstance;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use BristolSU\Support\Activity\Contracts\Repository as ActivityRepositoryContract;
use BristolSU\Support\Activity\Repository as ActivityRepository;

/**
 * Class ActivityServiceProvider
 * @package BristolSU\Support\Activity
 */
class ActivityServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(ActivityRepositoryContract::class, ActivityRepository::class);

    }

    public function boot()
    {
        $this->app['router']->pushMiddlewareToGroup('activity', InjectActivityInstance::class);
        $this->app['router']->pushMiddlewareToGroup('participant', CheckLoggedIntoActivityForType::class);
        $this->app['router']->pushMiddlewareToGroup('participant', CheckActivityFor::class);
        $this->app['router']->pushMiddlewareToGroup('administrator', CheckAdminActivityFor::class);
        Route::bind('activity_slug', function ($slug) {
            return Activity::where(['slug' => $slug])->firstOrFail();
        });
        
    }

}