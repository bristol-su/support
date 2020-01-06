<?php

namespace BristolSU\Support\Connection;

use BristolSU\Support\Connection\Contracts\ServiceRequest as ServiceRequestContract;

/**
 * Tool for requesting services for a module
 */
class ServiceRequest implements ServiceRequestContract
{
    
    /**
     * Holds all required services
     * 
     * [
     *      'module_alias' => ['service1', 'service2']
     * ]
     * @var array 
     */
    private $required = [];

    /**
     * Holds all optional services
     *
     * [
     *      'module_alias' => ['service1', 'service2']
     * ]
     * @var array
     */
    private $optional = [];

    /**
     * Request a required service.
     *
     * @param string $alias Alias of the module requesting the service
     * @param array $services An array of services that are required
     * @return void
     */
    public function required(string $alias, array $services = [])
    {
        $this->required[$alias] = $services;
    }

    /**
     * Request an optional service.
     *
     * @param string $alias Alias of the module requesting the service
     * @param array $services An array of services that are optional but useable
     * @return void
     */
    public function optional(string $alias, array $services = [])
    {
        $this->optional[$alias] = $services;
    }

    /**
     * Get all required services for a module
     *
     * @param string $alias Alias of the module
     * @return array Array of services required e.g. ['typeform', 'facebook']
     */
    public function getRequired(string $alias)
    {
        if(array_key_exists($alias, $this->required)) {
            return $this->required[$alias];
        }
        return [];
    }

    /**
     * Get all optional services for a module
     *
     * @param string $alias Alias of the module
     * @return array Array of optional services e.g. ['typeform', 'facebook']
     */
    public function getOptional(string $alias)
    {
        if(array_key_exists($alias, $this->optional)) {
            return $this->optional[$alias];
        }
        return [];
    }

    /**
     * Get all required services from all modules
     *
     * @return array ['module_alias' => ['typeform', 'facebook', ...], ...]
     */
    public function getAllRequired()
    {
        return $this->required;
    }

    /**
     * Get all optional services from all modules
     *
     * @return array ['module_alias' => ['typeform', 'facebook', ...], ...]
     */
    public function getAllOptional()
    {
        return $this->optional;
    }
}