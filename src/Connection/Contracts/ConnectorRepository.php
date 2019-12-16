<?php


namespace BristolSU\Support\Connection\Contracts;


use BristolSU\Support\Connection\RegisteredConnector;

interface ConnectorRepository
{

    /**
     * @param string $ability
     * @return RegisteredConnector
     * @throws \Exception
     */
    public function get(string $alias): RegisteredConnector;

    /**
     * @param string $service
     * @return array
     */
    public function forService(string $service): array;

    /**
     * Get all connectors registered
     *
     * @return array
     */
    public function all(): array;
    
}