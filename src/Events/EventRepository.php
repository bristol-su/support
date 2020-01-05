<?php


namespace BristolSU\Support\Events;


use BristolSU\Support\Events\Contracts\EventManager as EventManagerContract;
use BristolSU\Support\Events\Contracts\EventRepository as EventRepositoryContract;

/**
 * Class EventRepository
 */
class EventRepository implements EventRepositoryContract
{
    /**
     * @var EventManagerContract
     */
    private $manager;

    /**
     * EventRepository constructor.
     * @param EventManagerContract $manager
     */
    public function __construct(EventManagerContract $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param string $alias
     * @return mixed
     */
    public function allForModule(string $alias)
    {
        return $this->manager->allForModule($alias);
    }
}
