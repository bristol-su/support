<?php

namespace BristolSU\Support\ModuleInstance\Contracts\Connection;

use BristolSU\Support\Connection\Contracts\Connector;
use BristolSU\Support\ModuleInstance\Connection\ModuleInstanceService;
use BristolSU\Support\ModuleInstance\Connection\NoConnectionAvailable;

/**
 * Handle interactions with connections assigned to services.
 */
interface ModuleInstanceServiceRepository
{
    /**
     * Get a connector for a module instance given the service.
     *
     * @param string $service Name of the service required
     * @param int $moduleInstanceId Module instance ID using the connector
     * @throws NoConnectionAvailable If no available connection has been found
     * @return Connector Authenticated connector for use in making requests
     *
     */
    public function getConnectorForService(string $service, int $moduleInstanceId): Connector;

    /**
     * Return all module instance service assignments.
     *
     * @return ModuleInstanceService[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all();
}
