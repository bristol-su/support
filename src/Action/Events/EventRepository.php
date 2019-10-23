<?php


namespace BristolSU\Support\Action\Events;


use BristolSU\Support\Action\Contracts\Events\EventManager as EventManagerContract;
use BristolSU\Support\Action\Contracts\Events\EventRepository as EventRepositoryContract;

/**
 * Class EventRepository
 * @package BristolSU\Support\Action\Events
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
