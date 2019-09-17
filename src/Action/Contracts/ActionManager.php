<?php

namespace BristolSU\Support\Action\Contracts;

/**
 * Stores action classes and raw metadata
 */
interface ActionManager
{

    /**
     * Register an action to be used.
     * 
     * An action is any class that implements the Action interface. Call the registerAction method in the service
     * provider to register an action.
     * 
     * @param string $class Class name of the action.
     * @param string $name A name for the action.
     * @param string $description A description of the action.
     * 
     * @return void
     */
    public function registerAction(string $class, string $name, string $description): void;

    /**
     * Return all registered actions.
     * 
     * @return array
     */
    public function all(): array;

    /**
     * Return an action registered with the given class.
     * 
     * @param string $class Class of the action
     * 
     * @throws \Exception Throws an exception if the action has not been registered
     * @return array
     */
    public function fromClass(string $class): array;
    
}