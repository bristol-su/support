<?php

namespace BristolSU\Support\Filters;

use BristolSU\Support\Filters\Contracts\FilterFactory as FilterFactoryContract;
use BristolSU\Support\Filters\Contracts\FilterInstance as FilterInstanceContract;
use BristolSU\Support\Filters\Contracts\FilterInstanceRepository as FilterInstanceRepositoryContract;
use BristolSU\Support\Filters\Contracts\FilterManager as FilterManagerContract;
use BristolSU\Support\Filters\Contracts\FilterRepository as FilterRepositoryContract;
use BristolSU\Support\Filters\Contracts\FilterTester as FilterTesterContract;
use BristolSU\Support\Filters\Filters\Group\GroupNameIs;
use BristolSU\Support\Filters\Filters\Group\GroupTagged;
use BristolSU\Support\Filters\Filters\Role\RoleHasPosition;
use BristolSU\Support\Filters\Filters\Role\RoleTagged;
use BristolSU\Support\Filters\Filters\User\UserEmailIs;
use BristolSU\Support\Filters\Filters\User\UserTagged;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

/**
 * Filter service provider.
 */
class FilterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * - Bind implementations of contracts
     * - Set up filter tester caching
     * - Set up the filter manager as a singleton
     */
    public function register()
    {
        $this->app->bind(FilterRepositoryContract::class, FilterRepository::class);
        $this->app->bind(FilterFactoryContract::class, FilterFactory::class);
        $this->app->bind(FilterTesterContract::class, FilterTester::class);

        $this->app->bind(FilterInstanceContract::class, FilterInstance::class);
        $this->app->bind(FilterInstanceRepositoryContract::class, FilterInstanceRepository::class);
        $this->app->singleton(FilterManagerContract::class, FilterManager::class);
        $this->app->extend(FilterManagerContract::class, fn (FilterManagerContract $filterManager) => new FilterManagerEventRegistrationDecorator($filterManager));
    }

    /**
     * Bootstrap services.
     *
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $this->app->call([$this, 'registerFilters']);
    }

    /**
     * Register all filters the SDK provides by default.
     *
     * This method may be overridden to stop any default filters being loaded
     *
     * @param FilterManagerContract $filterManager
     */
    public function registerFilters(FilterManagerContract $filterManager)
    {
        // User Filters
        $filterManager->register('user_email_is', UserEmailIs::class);
        $filterManager->register('user_tagged', UserTagged::class);

        // Group Filters
        $filterManager->register('group_name_is', GroupNameIs::class);
        $filterManager->register('group_tagged', GroupTagged::class);

        // Role Filters
        $filterManager->register('role_has_position', RoleHasPosition::class);
        $filterManager->register('role_tagged', RoleTagged::class);
    }
}
