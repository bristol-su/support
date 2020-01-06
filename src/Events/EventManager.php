<?php

namespace BristolSU\Support\Events;

use BristolSU\Support\Events\Contracts\EventManager as EventManagerContract;

/**
 * Class EventManager
 */
class EventManager implements EventManagerContract
{

    /**
     * Stores registered events
     * 
     * Stores events in the form
     * [
     *      'name' => 'Event Name',
     *      'description' => 'Event Description',
     *      'event' => 'Event Class Name'
     * ]
     * 
     * @var array
     */
    protected $events = [];

    /**
     * Register an event
     *
     * @param string $alias Module alias registering the event
     * @param string $name Event Name
     * @param string $class Event Class
     * @param string $description Event Description
     * 
     * @return void
     */
    public function registerEvent($alias, $name, $class, $description)
    {
        if(!isset($this->events[$alias])) {
            $this->events[$alias] = [];
        }
        $this->events[$alias][] = [
            'name' => $name,
            'description' => $description,
            'event' => $class,
        ];
    }

    /**
     * Get all events
     *
     * Returns all events, each one in the form
     * [
     *      'name' => 'Event Name',
     *      'description' => 'Event Description',
     *      'event' => 'Event Class Name'
     * ]
     * 
     * @return array All events
     */
    public function all()
    {
        return $this->events;
    }

    /**
     * Get all events for a given module
     *
     * Returns all events, each one in the form
     * [
     *      'name' => 'Event Name',
     *      'description' => 'Event Description',
     *      'event' => 'Event Class Name'
     * ]
     *
     * @param string $alias Module alias
     * @return array All events fired by the module
     */
    public function allForModule($alias)
    {
        return (array_key_exists($alias, $this->events)?$this->events[$alias]:[]);
    }
    
}