<?php

namespace BristolSU\Support\Action;

use BristolSU\Support\Action\Contracts\ActionManager as ActionManagerContract;

/**
 * Action Manager.
 * 
 * Holds actions when registered
 */
class ActionManager implements ActionManagerContract
{

    /**
     * Holds the action metadata.
     * 
     * The index of each array element is the action class, and the element is another array of the form
     * [
     *      'name' => 'Action Name', 'description' => 'Action Description', 'class' => 'Namespaced\Action::class'
     * ]
     * 
     * @var array
     */
    protected $actions = [];

    /**
     * Register a new action
     *
     * @param string $class Class name of the action.
     * @param string $name A name for the action.
     * @param string $description A description of the action.
     *
     * @return void
     */
    public function registerAction(string $class, string $name, string $description): void
    {
        $this->actions[$class] = [
            'name' => $name,
            'class' => $class,
            'description' => $description
        ];
    }

    /**
     * Return all registered actions.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->actions;
    }

    /**
     * Return an action registered with the given class.
     *
     * @param string $class Class of the action
     *
     * @throws \Exception Throws an exception if the action has not been registered
     * @return array
     */
    public function fromClass(string $class): array
    {
        if (!array_key_exists($class, $this->actions)) {
            throw new \Exception(sprintf('Action [%s] not found', $class));
        }
        return $this->actions[$class];
    }
    
}