<?php

namespace BristolSU\Support\ActivityInstance;

use BristolSU\Support\ActivityInstance\AuthenticationProvider\ActivityInstanceProvider;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository as ActivityInstanceRepositoryContract;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Contracts\DefaultActivityInstanceGenerator as DefaultActivityInstanceGeneratorContract;
use BristolSU\Support\ActivityInstance\Middleware\CheckActivityInstanceAccessible;
use BristolSU\Support\ActivityInstance\Middleware\CheckActivityInstanceForActivity;
use BristolSU\Support\ActivityInstance\Middleware\CheckLoggedIntoActivityInstance;
use BristolSU\Support\ActivityInstance\Middleware\ClearActivityInstance;
use BristolSU\Support\ActivityInstance\Middleware\InjectActivityInstance;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

/**
 * ActivityInstanceServiceProvider.
 */
class ActivityInstanceServiceProvider extends ServiceProvider
{
    /**
     * Register.
     *
     * - Register the activity instance resolver, API or Web
     * - Bind the activity instance repository contract to an implementation
     * - Bind the activity instance generator contract to an implementation
     */
    public function register()
    {
        $this->app->call([$this, 'registerActivityInstanceResolver']);
        $this->app->bind(ActivityInstanceRepositoryContract::class, ActivityInstanceRepository::class);
        $this->app->bind(DefaultActivityInstanceGeneratorContract::class, DefaultActivityInstanceGenerator::class);
    }

    /**
     * Register.
     *
     * - Push middleware to a middleware group
     * - Set up activity instance authentication provider
     */
    public function boot()
    {
        $this->app['router']->pushMiddlewareToGroup('participant', CheckLoggedIntoActivityInstance::class);
        $this->app['router']->pushMiddlewareToGroup('participant', CheckActivityInstanceForActivity::class);
        $this->app['router']->pushMiddlewareToGroup('participant', CheckActivityInstanceAccessible::class);
        $this->app['router']->pushMiddlewareToGroup('participant', InjectActivityInstance::class);
        $this->app['router']->pushMiddlewareToGroup('nonmodule', ClearActivityInstance::class);

        Auth::provider('activity-instance-provider', function (Container $app, array $config) {
            return new ActivityInstanceProvider(app(ActivityInstanceRepositoryContract::class));
        });
    }

    /**
     * Register the activity instance resolver.
     *
     * Registers the api activitiy instance resoolver for an API route, or a web resolver otherwise.
     *
     * @param Request $request
     */
    public function registerActivityInstanceResolver(Request $request)
    {
        $this->app->bind(ActivityInstanceResolver::class, function ($app) use ($request) {
            return ($request->is('api/*') ?
                $app->make(ApiActivityInstanceResolver::class) : $app->make(WebRequestActivityInstanceResolver::class));
        });
    }
}
