<?php

namespace BristolSU\Support\Connection\Contracts;

/**
 * An object for making requests to get a service
 */
interface ServiceRequest
{

    /**
     * Request a required service.
     * 
     * @param string $alias Alias of the module requesting the service
     * @param array $services An array of services that are required
     * @return void
     */
    public function required(string $alias, array $services = []);

    /**
     * Request an optional service.
     *
     * @param string $alias Alias of the module requesting the service
     * @param array $services An array of services that are optional but useable
     * @return void
     */
    public function optional(string $alias, array $services = []);

    /**
     * Get all required services for a module
     * 
     * @param string $alias Alias of the module
     * @return array Array of services required e.g. ['typeform', 'facebook']
     */
    public function getRequired(string $alias);

    /**
     * Get all optional services for a module
     *
     * @param string $alias Alias of the module
     * @return array Array of optional services e.g. ['typeform', 'facebook']
     */
    public function getOptional(string $alias);

    /**
     * Get all required services from all modules
     * 
     * @return array ['module_alias' => ['typeform', 'facebook', ...], ...]
     */
    public function getAllRequired();

    /**
     * Get all optional services from all modules
     *
     * @return array ['module_alias' => ['typeform', 'facebook', ...], ...]
     */
    public function getAllOptional();
    
    // TODO Refactor the service request to include scopes
}