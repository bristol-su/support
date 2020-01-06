<?php


namespace BristolSU\Support\Events;


use BristolSU\Support\Events\Contracts\EventManager as EventManagerContract;
use BristolSU\Support\Events\Contracts\EventRepository as EventRepositoryContract;

/**
 * Event repository using the event manager to resolve events
 */
class EventRepository implements EventRepositoryContract
{
    /**
     * Holds the event manager to retrieve events from
     * 
     * @var EventManagerContract
     */
    private $manager;

    /**
     * @param EventManagerContract $manager
     */
    public function __construct(EventManagerContract $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Get all events a module has registered with the manager
     *
     * Returns events in the form
     * [
     *      'name' => 'Event Name',
     *      'description' => 'Event Description',
     *      'event' => 'EventClassName'
     * ]
     *
     * @param string $alias Module alias
     *
     * @return array
     */
    public function allForModule(string $alias)
    {
        return $this->manager->allForModule($alias);
    }
}
