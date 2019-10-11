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
    
}