<?php

namespace BristolSU\Support\Connection;

use BristolSU\Support\Connection\Contracts\ConnectorStore as ConnectorStoreContract;

/**
 * Stores connectors.
 */
class ConnectorStore implements ConnectorStoreContract
{
    /**
     * Connectors registered.
     *
     * @var array
     */
    private $connectors = [];

    /**
     * Register a connector class directly.
     *
     * @param RegisteredConnector $connector Connector to register
     */
    public function registerConnector(RegisteredConnector $connector): void
    {
        $this->connectors[$connector->getAlias()] = $connector;
    }

    /**
     * Register a new connector from its attributes.
     *
     * @param string $name Name of the connector
     * @param string $description Description for the connector
     * @param string $alias Unique alias of the connector
     * @param string $service Alias of the service
     * @param string $connector Connector class name
     *
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
     * Get a registered connector by alias.
     *
     * @param string $alias Alias of the registered connector
     * @throws \Exception If not found
     * @return RegisteredConnector
     */
    public function get(string $alias): RegisteredConnector
    {
        if (array_key_exists($alias, $this->connectors)) {
            return $this->connectors[$alias];
        }

        throw new \Exception('Connector ' . $alias . ' not registered');
    }

    /**
     * Return all registered connectors.
     *
     * @return RegisteredConnector[]
     */
    public function all(): array
    {
        return $this->connectors;
    }
}
