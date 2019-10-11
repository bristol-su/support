<?php


namespace BristolSU\Support\Action;


use BristolSU\Support\Action\Contracts\EventManager as EventManagerContract;
use BristolSU\Support\Action\Contracts\EventRepository as EventRepositoryContract;

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
