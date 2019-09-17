<?php

namespace BristolSU\Support\Connection\Contracts;

use BristolSU\Support\Connection\Connection;

/**
 * Handle getting and creating connections (instances of a connector)
 */
interface ConnectionRepository
{

    /**
     * Return all connections
     * 
     * @return Connection[]
     */
    public function all();

    /**
     * Get a connection by ID
     * 
     * @param int $id ID of the connection
     * @return Connection
     */
    public function get(int $id): Connection;

    /**
     * Delete a connection
     * 
     * @param int $id ID of the connection to delete
     * @return void
     */
    public function delete(int $id);

    /**
     * Create a connection
     * 
     * Attributes should contain
     * [
     *      'name' => 'Connection Name',
     *      'description' => 'Connection Description',
     *      'alias' => 'connector_alias',
     *      'settings' => [
     *          'api_key' => 'abc123',
     *          ...
     *      ]  
     * ]
     * The settings are the settings specific to the connector being used.
     * 
     * @param array $attributes Attributes to make the connection
     * @return Connection New connection
     */
    public function create(array $attributes): Connection;

    /**
     * Update a connection
     * 
     * Attributes can contain
     * [
     *      'name' => 'Connection Name',
     *      'description' => 'Connection Description',
     *      'alias' => 'connector_alias',
     *      'settings' => [
     *          'api_key' => 'abc123',
     *          ...
     *      ]
     * ]
     * 
     * @param int $id ID of the connection to update
     * @param array $attributes Attributes to change
     * @return Connection Edited connection
     */
    public function update(int $id, array $attributes): Connection;

    /**
     * Get all connections for a service. These may be from multiple connectors.
     * 
     * @param string $service Service to get the connections for
     * @return Connection[]
     */
    public function getAllForService(string $service);

    /**
     * Get all connections for a specific connector
     * 
     * @param string $alias Connector to return connections for
     * @return Connection[]
     */
    public function getAllForConnector(string $alias);
    
}
