<?php

namespace BristolSU\Support\Action\Contracts;

/**
 * Retrieves information from the ActionManager.
 */
interface ActionRepository
{
    /**
     * Get all registered actions.
     *
     * This method should transform all the registered actions to the RegisteredAction interface.
     *
     * @return RegisteredAction[]
     */
    public function all();

    /**
     * Return an action from its class.
     *
     * @param string $class Class of the action
     * @return RegisteredAction Action in the RegisteredAction interface structure
     */
    public function fromClass($class);
}
