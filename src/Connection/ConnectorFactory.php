<?php

namespace BristolSU\Support\Connection;

use BristolSU\Support\Connection\Contracts\Connector;
use BristolSU\Support\Connection\Contracts\ConnectorFactory as ConnectorFactoryContract;

class ConnectorFactory implements ConnectorFactoryContract
{

    public function createFromConnection(Connection $connection): Connector
    {
        $connector = app($connection->connector()->getConnector());
        $connector->setSettings($connection->settings);
        return $connector;
    }
}