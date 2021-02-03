<?php


namespace BristolSU\Support\Action\Contracts;

use BristolSU\Support\Action\ActionInstance;

/**
 * Builds an action class.
 */
interface ActionBuilder
{
    /**
     * Build an action given an action instance and data to map.
     *
     * The data are the event fields, so need mapping to the action data before creation.
     *
     * @param ActionInstance $actionInstance
     * @param array $data
     * @return Action
     */
    public function build(ActionInstance $actionInstance, array $data = []): Action;
}
