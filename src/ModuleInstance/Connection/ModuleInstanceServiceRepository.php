<?php

namespace BristolSU\Support\ModuleInstance\Connection;

use BristolSU\Support\Connection\Contracts\Connector;
use BristolSU\Support\Connection\Contracts\ConnectorFactory;
use BristolSU\Support\ModuleInstance\Contracts\Connection\ModuleInstanceServiceRepository as ModuleInstanceServiceRepositoryContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ModuleInstanceServiceRepository implements ModuleInstanceServiceRepositoryContract
{

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

    public function all()
    {
        return ModuleInstanceService::all();
    }
}