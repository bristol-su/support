<?php

namespace BristolSU\Support\Connection;

use BristolSU\Support\Connection\Contracts\ConnectorStore as ConnectorStoreContract;

/**
 * Class ConnectorStore
 * @package BristolSU\Support\Connector
 */
class ConnectorStore implements ConnectorStoreContract
{

    /**
     * @var array
     */
    private $connectors = [];

    /**
     * Register a connector class directly
     * 
     * @param RegisteredConnector $connector
     */
    public function registerConnector(RegisteredConnector $connector): void
    {
        $this->connectors[$connector->getAlias()] = $connector;
    }

    /**
     * Register a new connector
     * 
     * @param string $name
     * @param string $description
     * @param string $alias
     * @param string $service
     * @param string $connector
     */
    public function register(string $name, string $description, string $alias, string $service, string $connector): void
    {
        $registeredConnector = new RegisteredConnector();
        $registeredConnector->setName($name);
        $registeredConnector->setDescription($description);
        $registeredConnector->setAlias($alias);
        $registeredConnector->setService($service);
        $registeredConnector->setConnector($connector);
        $this->registerConnector($registeredConnector);
    }

    /**
     * @param string $alias
     * @return RegisteredConnector
     * @throws \Exception
     */
    public function get(string $alias): RegisteredConnector
    {
        if(array_key_exists($alias, $this->connectors)) {
            return $this->connectors[$alias];
        }
        throw new \Exception('Connector ' . $alias . ' not registered');
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->connectors;
    }
}
