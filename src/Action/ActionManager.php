<?php

namespace BristolSU\Support\Action;

use BristolSU\Support\Action\Contracts\ActionManager as ActionManagerContract;

class ActionManager implements ActionManagerContract
{

    protected $actions = [];
    
    public function registerAction($class, $name, $description)
    {
        $this->actions[$class] = [
            'name' => $name,
            'class' => $class,
            'description' => $description
        ];
    }

    public function all()
    {
        return $this->actions;
    }

    public function fromClass($class)
    {
        if(!array_key_exists($class, $this->actions)) {
            throw new \Exception(sprintf('Action [%s] not found', $class));
        }
        return $this->actions[$class];
    }
    
}