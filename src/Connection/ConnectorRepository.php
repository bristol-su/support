<?php


namespace BristolSU\Support\Connection;


use BristolSU\Support\Connection\Contracts\ConnectorRepository as ConnectorRepositoryContract;
use BristolSU\Support\Connection\Contracts\ConnectorStore as ConnectorStoreContract;

/**
 * Class ConnectorRepository
 * @package BristolSU\Support\Connectors
 */
class ConnectorRepository implements ConnectorRepositoryContract
{

    /**
     * @var ConnectorStoreContract
     */
    private $connectorStore;

    /**
     * ConnectorRepository constructor.
     * @param ConnectorStoreContract $connectorStore
     */
    public function __construct(ConnectorStoreContract $connectorStore)
    {
        $this->connectorStore = $connectorStore;
    }

    /**
     * @param string $ability
     * @return RegisteredConnector
     * @throws \Exception
     */
    public function get(string $alias): RegisteredConnector
    {
        return $this->connectorStore->get($alias);
    }

    /**
     * @param string $service
     * @return array
     */
    public function forService(string $service): array
    {
        return collect($this->connectorStore->all())->filter(function(RegisteredConnector $connector) use ($service) {
            return $connector->getService() === $service;
        })->values()->toArray();
    }

    /**
     * Get all connectors registered 
     * 
     * @return array
     */
    public function all(): array
    {
        return $this->connectorStore->all();
    }
}
