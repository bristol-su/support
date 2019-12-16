<?php


namespace BristolSU\Support\Connection\Contracts;


use BristolSU\Support\Connection\RegisteredConnector;

interface ConnectorStore
{

    public function registerConnector(RegisteredConnector $connector): void;

    /**
     * Register a new connector
     *
     * @param string $name Name of the connector
     * @param string $description Description for the connector
     * @param string $alias Unique alias of the connector
     * @param string $service Alias of the service
     * @param string $connector Connector class name
     */
    public function register(string $name, string $description, string $alias, string $service, string $connector): void;

    /**
     * @param string $alias
     * @return RegisteredConnector
     * @throws \Exception
     */
    public function get(string $alias): RegisteredConnector;

    /**
     * @return array
     */
    public function all(): array;
    
}