<?php

namespace BristolSU\Support\Connection;

use BristolSU\Support\Connection\Contracts\ConnectionRepository as ConnectionRepositoryContract;
use Illuminate\Support\Collection;

/**
 * Handles connections
 */
class ConnectionRepository implements ConnectionRepositoryContract
{

    /**
     * Get all connections
     * 
     * @return Connection[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return Connection::all();
    }

    /**
     * Get a connection by ID
     * 
     * @param int $id ID of the connection
     * @return Connection
     */
    public function get(int $id): Connection
    {
        return Connection::findOrFail($id);
    }

    /**
     * Delete a connection
     * 
     * @param int $id ID of the connection
     * @return bool|void|null
     * @throws \Exception If the connection could not be deleted
     */
    public function delete(int $id) {
        return $this->get($id)->delete();
    }

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
    public function create(array $attributes): Connection
    {
        return Connection::create($attributes);
    }

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
     * @throws \Exception If the connection could not be updated
     */
    public function update(int $id, array $attributes): Connection
    {
        $connection = $this->get($id);
        $connection->fill($attributes);
        $connection->save();
        return $connection;
    }

    /**
     * Get all connections for a service. These may be from multiple connectors.
     *
     * @param string $service Service to get the connections for
     * @return Connection[]|Collection
     */
    public function getAllForService(string $service)
    {
        return collect(app(\BristolSU\Support\Connection\Contracts\ConnectorRepository::class)->forService($service))->map(function(RegisteredConnector $connector) {
            return $this->getAllForConnector($connector->getAlias());
        })->flatten(1)->values();
    }

    /**
     * Get all connections for a specific connector
     *
     * @param string $alias Connector to return connections for
     * @return Connection[]
     */
    public function getAllForConnector(string $alias)
    {
        return Connection::where('alias', $alias)->get();
    }
}