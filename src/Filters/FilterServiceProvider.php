<?php

namespace BristolSU\Support\Filters;

use BristolSU\Support\Filters\FilterRepository;
use BristolSU\Support\Filters\Contracts\FilterFactory as FilterFactoryContract;
use BristolSU\Support\Filters\Contracts\FilterInstance as FilterInstanceContract;
use BristolSU\Support\Filters\Contracts\FilterInstanceRepository as FilterInstanceRepositoryContract;
use BristolSU\Support\Filters\Contracts\FilterManager as FilterManagerContract;
use BristolSU\Support\Filters\Contracts\FilterRepository as FilterRepositoryContract;
use BristolSU\Support\Filters\Contracts\FilterTester as FilterTesterContract;
use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Filters\FilterInstanceRepository;
use BristolSU\Support\Filters\Filters\GroupTagged;
use BristolSU\Support\Filters\Filters\RoleHasPosition;
use BristolSU\Support\Filters\Filters\UserEmailIs;
use BristolSU\Support\Filters\FilterTester;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(FilterFactoryContract::class, \BristolSU\Support\Filters\FilterFactory::class);
        $this->app->bind(FilterTesterContract::class, FilterTester::class);

        $this->app->bind(FilterInstanceContract::class, FilterInstance::class);
        $this->app->bind(FilterInstanceRepositoryContract::class, FilterInstanceRepository::class);
        $this->app->singleton(FilterManagerContract::class, FilterManager::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make(FilterManagerContract::class)->register('group_tagged', GroupTagged::class);
        $this->app->make(FilterManagerContract::class)->register('user_email_is', UserEmailIs::class);
        $this->app->make(FilterManagerContract::class)->register('role_has_position', RoleHasPosition::class);
    }
}
