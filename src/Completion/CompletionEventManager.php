<?php

namespace BristolSU\Support\Completion;

use BristolSU\Support\Completion\Contracts\CompletionEventManager as CompletionEventManagerContract;

class CompletionEventManager implements CompletionEventManagerContract
{

    protected $events = [];
    
    public function registerEvent($alias, $name, $class, $description)
    {
        if(!isset($this->events[$alias])) {
            $this->events[$alias] = [];
        }
        $this->events[$alias][] = [
            'name' => $name,
            'event' => $class,
            'description' => $description
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