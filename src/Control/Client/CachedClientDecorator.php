<?php

namespace BristolSU\Support\Control\Client;

use BristolSU\Support\Control\Contracts\Client\Client;
use Illuminate\Contracts\Cache\Repository as Cache;

class   CachedClientDecorator implements Client
{

    /**
     * @var Client
     */
    private $client;
    /**
     * @var Cache
     */
    private $cache;

    public function __construct(Client $client, Cache $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    /**
     * @param $method
     * @param $uri
     * @param array $options
     * @return mixed
     */
    public function request($method, $uri, array $options = [])
    {
        if($this->isCacheable($method)) {
            return $this->cache->remember($this->getKey($method, $uri, $options), 7200, function() use ($method, $uri, $options){
                return $this->forwardCall($method, $uri, $options);
            });
        }
        return $this->forwardCall($method, $uri, $options);
    }

    private function getKey($method, $uri, $options)
    {
        return self::class . $method . $uri . json_encode($options);
    }

    private function isCacheable($method)
    {
        return in_array(strtoupper($method), ['GET', 'HEAD', 'OPTIONS', 'TRACE']);
    }

    private function forwardCall($method, $uri, $options)
    {
        return $this->client->request($method, $uri, $options);
    }
}