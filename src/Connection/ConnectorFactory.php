<?php

namespace BristolSU\Support\Connection;

use BristolSU\Support\Connection\Contracts\Connector;
use BristolSU\Support\Connection\Contracts\ConnectorFactory as ConnectorFactoryContract;

/**
 * Create a connector.
 */
class ConnectorFactory implements ConnectorFactoryContract
{
    /**
     * Create a connector from a connection.
     *
     * @param Connection $connection Connection to create the connector from
     * @return Connector Connector created
     */
    public function createFromConnection(Connection $connection): Connector
    {
        $connector = app($connection->connector()->getConnector());
        $connector->setSettings($connection->settings);

        return $connector;
    }
}
