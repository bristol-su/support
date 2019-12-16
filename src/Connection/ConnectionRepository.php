<?php

namespace BristolSU\Support\Connection;

use BristolSU\Support\Connection\Contracts\ConnectionRepository as ConnectionRepositoryContract;

class ConnectionRepository implements ConnectionRepositoryContract
{

    public function all()
    {
        return Connection::all();
    }

    public function get(int $id): Connection
    {
        return Connection::findOrFail($id);
    }
    
    public function delete(int $id) {
        return $this->get($id)->delete();
    }

    public function create(array $attributes): Connection
    {
        return Connection::create($attributes);
    }

    public function update(int $id, array $attributes): Connection
    {
        $connection = $this->get($id);
        $connection->fill($attributes);
        if($connection->save()) {
            return $connection;
        }
        throw new \Exception('Could not save connection');
    }

    public function getAllForService(string $service)
    {
        // TODO pass a RegisteredConnector into the map closure
        return collect(app(\BristolSU\Support\Connection\Contracts\ConnectorRepository::class)->forService($service))->map(function($connector) {
            return $this->getAllForConnector($connector['alias']);
        })->flatten(1)->values();
    }

    public function getAllForConnector(string $alias)
    {
        return Connection::where('alias', $alias)->get();
    }
}