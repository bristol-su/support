<?php

namespace BristolSU\Support\Action\Events;

use BristolSU\Support\Action\Contracts\Events\EventManager as EventManagerContract;

class EventManager implements EventManagerContract
{

    protected $events = [];
    
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

    public function all()
    {
        return $this->events;
    }
    
    public function allForModule($alias)
    {
        return (array_key_exists($alias, $this->events)?$this->events[$alias]:[]);
    }
    
}