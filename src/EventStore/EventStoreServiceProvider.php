<?php


namespace BristolSU\Support\EventStore;


use BristolSU\Support\EventStore\Contracts\EventStoreRepository as EventStoreRepositoryContract;
use BristolSU\Support\EventStore\Contracts\StorableEvent;
use BristolSU\Support\EventStore\EventStoreRepository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventStoreServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(EventStoreRepositoryContract::class, EventStoreRepository::class);
    }

    public function boot()
    {
        Event::listen(StorableEvent::class, EventStoreListener::class);
    }


}
