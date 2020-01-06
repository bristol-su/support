<?php


namespace BristolSU\Support\Connection\Contracts;


use BristolSU\Support\Connection\RegisteredConnector;

/**
 * Repository for handling connectors
 */
interface ConnectorRepository
{

    /**
     * Get a connector by alias
     * 
     * @param string $alias Alias of the connector
     * @return RegisteredConnector The connector
     */
    public function get(string $alias): RegisteredConnector;

    /**
     * Get all connectors for the given service
     * 
     * @param string $service Service to find connectors for
     * @return RegisteredConnector[]
     */
    public function forService(string $service): array;

    /**
     * Get all connectors registered
     *
     * @return RegisteredConnector[]
     */
    public function all(): array;
    
}