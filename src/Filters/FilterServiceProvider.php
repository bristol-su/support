<?php

namespace BristolSU\Support\Filters;

use BristolSU\Support\Filters\Contracts\FilterFactory as FilterFactoryContract;
use BristolSU\Support\Filters\Contracts\FilterInstance as FilterInstanceContract;
use BristolSU\Support\Filters\Contracts\FilterInstanceRepository as FilterInstanceRepositoryContract;
use BristolSU\Support\Filters\Contracts\FilterManager as FilterManagerContract;
use BristolSU\Support\Filters\Contracts\FilterRepository as FilterRepositoryContract;
use BristolSU\Support\Filters\Contracts\FilterTester as FilterTesterContract;
use BristolSU\Support\Filters\Filters\Group\GroupTagged;
use BristolSU\Support\Filters\Filters\Role\RoleHasPosition;
use BristolSU\Support\Filters\Filters\User\UserEmailIs;
use BristolSU\Support\Filters\Commands\CacheFilters;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

/**
 * Class FilterServiceProvider
 * @package BristolSU\Support\Filters
 */
class FilterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FilterRepositoryContract::class, FilterRepository::class);
        $this->app->bind(FilterFactoryContract::class, FilterFactory::class);
        $this->app->bind(FilterTesterContract::class, FilterTester::class);

        $this->app->bind(FilterInstanceContract::class, FilterInstance::class);
        $this->app->bind(FilterInstanceRepositoryContract::class, FilterInstanceRepository::class);
        $this->app->singleton(FilterManagerContract::class, FilterManager::class);

        $this->app->extend(FilterTesterContract::class, function(FilterTesterContract $filterTester) {
            return new CachedFilterTesterDecorator($filterTester, $this->app->make(Cache::class));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $this->app->make(FilterManagerContract::class)->register('group_tagged', GroupTagged::class);
        $this->app->make(FilterManagerContract::class)->register('user_email_is', UserEmailIs::class);
        $this->app->make(FilterManagerContract::class)->register('role_has_position', RoleHasPosition::class);
        $this->commands([CacheFilters::class]);

    }
}
