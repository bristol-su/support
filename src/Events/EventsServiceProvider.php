<?php

namespace BristolSU\Support\Events;

use BristolSU\Support\Events\Contracts\EventManager as EventManagerContract;
use BristolSU\Support\Events\Contracts\EventRepository as EventRepositoryContract;
use Illuminate\Support\ServiceProvider;

class EventsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(EventRepositoryContract::class, EventRepository::class);
        $this->app->singleton(EventManagerContract::class, function($app) {
            return new EventManager;
        });
    }
}