<?php

namespace BristolSU\Support\Connection\Contracts;

use BristolSU\Support\Connection\RegisteredConnector;

/**
 * Stores information about registered connectors.
 */
interface ConnectorStore
{
    /**
     * Register a new connector from a RegisteredConnector class.
     *
     * @param RegisteredConnector $connector Registered connector
     */
    public function registerConnector(RegisteredConnector $connector): void;

    /**
     * Register a new connector.
     *
     * @param string $name Name of the connector
     * @param string $description Description for the connector
     * @param string $alias Unique alias of the connector
     * @param string $service Alias of the service
     * @param string $connector Connector class name
     */
    public function register(string $name, string $description, string $alias, string $service, string $connector): void;

    /**
     * Get a registered connector by alias.
     *
     * @param string $alias Alias of the registered connector
     * @throws \Exception If not found
     * @return RegisteredConnector
     */
    public function get(string $alias): RegisteredConnector;

    /**
     * Return all registered connectors.
     *
     * @return RegisteredConnector[]
     */
    public function all(): array;
}
