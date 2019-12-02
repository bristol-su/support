<?php

namespace BristolSU\Support\ActivityInstance;

use BristolSU\Support\ActivityInstance\AuthenticationProvider\ActivityInstanceProvider;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository as ActivityInstanceRepositoryContract;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Contracts\DefaultActivityInstanceGenerator as DefaultActivityInstanceGeneratorContract;
use BristolSU\Support\ActivityInstance\Middleware\CheckActivityInstanceForActivity;
use BristolSU\Support\ActivityInstance\Middleware\CheckLoggedIntoActivityInstance;
use BristolSU\Support\ActivityInstance\Middleware\ClearActivityInstance;
use BristolSU\Support\ActivityInstance\Middleware\InjectActivityInstance;
use BristolSU\Support\ActivityInstance\Middleware\LogIntoActivityInstance;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class ActivityInstanceServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->call([$this, 'registerActivityInstanceResolver']);
        $this->app->bind(ActivityInstanceRepositoryContract::class, ActivityInstanceRepository::class);
        $this->app->bind(DefaultActivityInstanceGeneratorContract::class, DefaultActivityInstanceGenerator::class);
    }

    public function boot()
    {
        $this->app['router']->pushMiddlewareToGroup('activity', LogIntoActivityInstance::class);
        $this->app['router']->pushMiddlewareToGroup('activity', CheckLoggedIntoActivityInstance::class);
        $this->app['router']->pushMiddlewareToGroup('activity', CheckActivityInstanceForActivity::class);
        $this->app['router']->pushMiddlewareToGroup('activity', InjectActivityInstance::class);
        $this->app['router']->pushMiddlewareToGroup('nonmodule', ClearActivityInstance::class);

        Auth::provider('activity-instance-provider', function(Container $app, array $config) {
            return new ActivityInstanceProvider(app(ActivityInstanceRepositoryContract::class));
        });
    }

    public function registerActivityInstanceResolver(Request $request)
    {
        $this->app->bind(ActivityInstanceResolver::class, function($app) use ($request) {
            return ($request->is('api/*')?
                $app->make(ApiActivityInstanceResolver::class):
                $app->make(LaravelAuthActivityInstanceResolver::class));
        });
    }

}