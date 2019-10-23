<?php


namespace BristolSU\Support\Action\Contracts;


use BristolSU\Support\Action\ActionInstance;

/**
 * Interface ActionBuilder
 * @package BristolSU\Support\Action\Contracts
 */
interface ActionBuilder
{

    /**
     * @param ActionInstance $actionInstance
     * @param array $data
     * @return Action
     */
    public function build(ActionInstance $actionInstance, array $data = []): Action;
    
}