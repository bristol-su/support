<?php


namespace BristolSU\Support\Action\Contracts;


use BristolSU\Support\Action\ActionInstance;

interface ActionBuilder
{

    public function build(ActionInstance $actionInstance, array $data = []): Action;
    
}