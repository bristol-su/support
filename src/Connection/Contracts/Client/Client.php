<?php

namespace BristolSU\Support\Connection\Contracts\Client;

/**
 * Represents a request client to make requests
 */
interface Client
{
    
    /**
     * Make a request
     * 
     * @param string $method Method used for the request
     * @param string $uri URL of the request
     * @param array $options Options to pass to the request
     * @return mixed Response
     * @throws \Exception If the  request failed for any reason
     */
    public function request($method, $uri, array $options = []);
    
}