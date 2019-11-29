<?php

namespace BristolSU\Support\Action;

use BristolSU\Support\Action\Contracts\ActionManager as ActionManagerContract;

/**
 * Class ActionManager
 * @package BristolSU\Support\Action
 */
class ActionManager implements ActionManagerContract
{

    /**
     * @var array
     */
    protected $actions = [];

    /**
     * @param $class
     * @param $name
     * @param $description
     * @return mixed|void
     */
    public function registerAction($class, $name, $description)
    {
        $this->actions[$class] = [
            'name' => $name,
            'class' => $class,
            'description' => $description
        ];
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->actions;
    }

    /**
     * @param $class
     * @return mixed
     * @throws \Exception
     */
    public function fromClass($class)
    {
        if (!array_key_exists($class, $this->actions)) {
            throw new \Exception(sprintf('Action [%s] not found', $class));
        }
        return $this->actions[$class];
    }
    
}