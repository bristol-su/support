<?php

namespace BristolSU\Support\Connection\Contracts;

use BristolSU\Support\Connection\Connection;

/**
 * Create a connector
 */
interface ConnectorFactory
{

    /**
     * Create a connector from a connection
     * 
     * @param Connection $connection The connection to create a connector from
     * @return Connector A connector with settings instantiated, ready to use to make requests
     */
    public function createFromConnection(Connection $connection): Connector;

}