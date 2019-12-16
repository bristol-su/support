<?php

namespace BristolSU\Support\Connection;

use BristolSU\Support\Connection\Contracts\alias;
use BristolSU\Support\Connection\Contracts\ServiceRequest as ServiceRequestContract;

class ServiceRequest implements ServiceRequestContract
{
    
    private $required = [];
    
    private $optional = [];

    public function required(string $alias, array $services = [])
    {
        $this->required[$alias] = $services;
    }

    public function optional(string $alias, array $services = [])
    {
        $this->optional[$alias] = $services;
    }

    public function getRequired(string $alias)
    {
        if(array_key_exists($alias, $this->required)) {
            return $this->required[$alias];
        }
        return [];
    }

    public function getOptional(string $alias)
    {
        if(array_key_exists($alias, $this->optional)) {
            return $this->optional[$alias];
        }
        return [];
    }

    public function getAllRequired()
    {
        return $this->required;
    }

    public function getAllOptional()
    {
        return $this->optional;
    }
}