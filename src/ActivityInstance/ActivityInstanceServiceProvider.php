<?php

namespace BristolSU\Support\ActivityInstance;

use BristolSU\Support\ActivityInstance\AuthenticationProvider\ActivityInstanceProvider;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository as ActivityInstanceRepositoryContract;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Middleware\CheckActivityInstanceForActivity;
use BristolSU\Support\ActivityInstance\Middleware\CheckLoggedIntoActivityInstance;
use BristolSU\Support\ActivityInstance\Middleware\CheckLoggedIntoActivityInstanceResource;
use BristolSU\Support\ActivityInstance\Middleware\InjectActivityInstance;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class ActivityInstanceServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(ActivityInstanceResolver::class, LaravelAuthActivityInstanceResolver::class);
        $this->app->bind(ActivityInstanceRepositoryContract::class, ActivityInstanceRepository::class);
    }

    public function boot()
    {
        $this->app['router']->pushMiddlewareToGroup('activity', CheckLoggedIntoActivityInstance::class);
        $this->app['router']->pushMiddlewareToGroup('activity', CheckActivityInstanceForActivity::class);
        $this->app['router']->pushMiddlewareToGroup('activity', CheckLoggedIntoActivityInstanceResource::class);
        $this->app['router']->pushMiddlewareToGroup('activity', InjectActivityInstance::class);

        Auth::provider('activity-instance-provider', function(Container $app, array $config) {
            return new ActivityInstanceProvider(app(ActivityInstanceRepositoryContract::class));
        });
    }

}