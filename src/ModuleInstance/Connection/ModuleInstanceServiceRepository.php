<?php

namespace BristolSU\Support\ModuleInstance\Connection;

use BristolSU\Support\Connection\Contracts\Connector;
use BristolSU\Support\Connection\Contracts\ConnectorFactory;
use BristolSU\Support\ModuleInstance\Contracts\Connection\ModuleInstanceServiceRepository as ModuleInstanceServiceRepositoryContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interact with connections belonging to module instances.
 */
class ModuleInstanceServiceRepository implements ModuleInstanceServiceRepositoryContract
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
    public function getConnectorForService(string $service, int $moduleInstanceId): Connector
    {
        try {
            $moduleInstanceService = ModuleInstanceService::where('service', $service)->where('module_instance_id', $moduleInstanceId)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new NoConnectionAvailable(sprintf('No connection has been found for %s', $service));
        }

        return app(ConnectorFactory::class)->createFromConnection($moduleInstanceService->connection);
    }

    /**
     * Return all module instance service assignments.
     *
     * @return ModuleInstanceService[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return ModuleInstanceService::all();
    }
}
