<?php


namespace BristolSU\Support\Connection;

use BristolSU\Support\Connection\Contracts\ConnectorRepository as ConnectorRepositoryContract;
use BristolSU\Support\Connection\Contracts\ConnectorStore as ConnectorStoreContract;

/**
 * Connector repository.
 */
class ConnectorRepository implements ConnectorRepositoryContract
{
    /**
     * Holds the connector store.
     *
     * @var ConnectorStoreContract
     */
    private $connectorStore;

    /**
     * @param ConnectorStoreContract $connectorStore Store to retrieve connectors from
     */
    public function __construct(ConnectorStoreContract $connectorStore)
    {
        $this->connectorStore = $connectorStore;
    }

    /**
     * Get a registered connector by alias.
     *
     * @param string $alias Alias of the connector
     * @throws \Exception If the connector is not found
     * @return RegisteredConnector The registered connector
     */
    public function get(string $alias): RegisteredConnector
    {
        return $this->connectorStore->get($alias);
    }

    /**
     * Get all connectors for a given service.
     *
     * @param string $service Service to get connectors for
     * @return RegisteredConnector[]|array
     */
    public function forService(string $service): array
    {
        return array_values(array_filter($this->connectorStore->all(), function (RegisteredConnector $connector) use ($service) {
            return $connector->getService() === $service;
        }));
    }

    /**
     * Get all connectors registered.
     *
     * @return RegisteredConnector[]|array
     */
    public function all(): array
    {
        return $this->connectorStore->all();
    }
}
