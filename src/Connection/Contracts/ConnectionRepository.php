<?php

namespace BristolSU\Support\Connection\Contracts;

use BristolSU\Support\Connection\Connection;

interface ConnectionRepository
{

    public function all();
    
    public function get(int $id): Connection;

    public function delete(int $id);

    public function create(array $attributes): Connection;
    
    public function update(int $id, array $attributes): Connection;

    public function getAllForService(string $service);

    public function getAllForConnector(string $alias);
    
}
