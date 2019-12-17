<?php


namespace BristolSU\Support\ModuleInstance\Contracts\Connection;


use BristolSU\Support\Connection\Contracts\Connector;

interface ModuleInstanceServiceRepository
{

    public function getConnectorForService(string $service, int $moduleInstanceId): Connector;

    public function all();
    
}