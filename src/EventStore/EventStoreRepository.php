<?php


namespace BristolSU\Support\EventStore;


use BristolSU\Support\EventStore\Contracts\EventStoreRepository as EventStoreRepositoryContract;

class EventStoreRepository implements EventStoreRepositoryContract
{
    public function create(array $attributes)
    {
        return EventStore::create($attributes);
    }

    public function has(array $attributes): bool
    {
        return EventStore::where($attributes)->count() > 0;
    }
}
