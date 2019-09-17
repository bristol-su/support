<?php

namespace BristolSU\Support\Connection\Client;

use BristolSU\Support\Connection\Contracts\Client\Client;
use Illuminate\Contracts\Cache\Repository as Cache;
use Psr\Http\Message\ResponseInterface;

/**
 * Cache decorator to cache requests
 */
class CachedClientDecorator implements Client
{

    /** Holds the normal client implementation
     * 
     * @var Client
     */
    private $client;
    
    /**
     * Holds the cache store
     * 
     * @var Cache
     */
    private $cache;

    /**
     * @param Client $client Client to forward calls onto
     * @param Cache $cache Cache repository to store requests in
     */
    public function __construct(Client $client, Cache $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    /**
     * Cache a request if cacheable, and return the request response
     * 
     * @param string $method Request method to use
     * @param string $uri URL to make the request too
     * @param array $options Options as specified in Guzzle
     * 
     * @return ResponseInterface 
     */
    public function request($method, $uri, array $options = [])
    {
        if ($this->isCacheable($method)) {
            return $this->cache->remember($this->getKey($method, $uri, $options), 7200, function() use ($method, $uri, $options){
                return $this->forwardCall($method, $uri, $options);
            });
        }
        return $this->forwardCall($method, $uri, $options);
    }

    /**
     * Get a unique key for the request
     * 
     * @param string $method Method for the request
     * @param string $uri URL for the request
     * @param array $options Options for the request
     * 
     * @return string A key unique to the request
     */
    private function getKey($method, $uri, $options)
    {
        return self::class.$method.$uri.json_encode($options);
    }

    /**
     * Is the request cacheable?
     * 
     * @param string $method Method of the request
     * @return bool Can the request be cached?
     */
    private function isCacheable($method)
    {
        return in_array(strtoupper($method), ['GET', 'HEAD', 'OPTIONS', 'TRACE']);
    }


    /**
     * Forward the call onto the underlying client instance
     * 
     * @param string $method Method of the request
     * @param string $uri URL for the request
     * @param array $options Guzzle options for the request
     * 
     * @return ResponseInterface
     */
    private function forwardCall($method, $uri, $options)
    {
        return $this->client->request($method, $uri, $options);
    }
}