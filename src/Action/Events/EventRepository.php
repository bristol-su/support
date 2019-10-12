<?php


namespace BristolSU\Support\Action\Events;


use BristolSU\Support\Action\Contracts\Events\EventManager as EventManagerContract;
use BristolSU\Support\Action\Contracts\Events\EventRepository as EventRepositoryContract;

class EventRepository implements EventRepositoryContract
{
    /**
     * @var EventManagerContract
     */
    private $manager;

    public function __construct(EventManagerContract $manager)
    {
        $this->manager = $manager;
    }

    public function allForModule(string $alias)
    {
        return $this->manager->allForModule($alias);
    }
}
