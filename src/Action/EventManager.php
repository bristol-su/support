<?php

namespace BristolSU\Support\Action;

use BristolSU\Support\Action\Contracts\EventManager as EventManagerContract;

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
        return ($this->events[$alias]?:[]);
    }
    
}