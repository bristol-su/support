<?php

namespace BristolSU\Support\Action\Events;

use BristolSU\Support\Action\Contracts\Events\EventManager as EventManagerContract;

/**
 * Class EventManager
 * @package BristolSU\Support\Action\Events
 */
class EventManager implements EventManagerContract
{

    /**
     * @var array
     */
    protected $events = [];

    /**
     * @param $alias
     * @param $name
     * @param $class
     * @param $description
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
     * @return array
     */
    public function all()
    {
        return $this->events;
    }

    /**
     * @param $alias
     * @return array|mixed
     */
    public function allForModule($alias)
    {
        return (array_key_exists($alias, $this->events)?$this->events[$alias]:[]);
    }
    
}