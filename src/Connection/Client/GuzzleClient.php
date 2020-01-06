<?php


namespace BristolSU\Support\Connection\Client;


use BristolSU\Support\Connection\Contracts\Client\Client;
use GuzzleHttp\ClientInterface as BaseGuzzleInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * Guzzle implementation of the API client
 */
class GuzzleClient implements Client
{
    /**
     * Holds the guzzle client instance
     * 
     * @var BaseGuzzleInterface
     */
    private $client;

    /**
     * @param BaseGuzzleInterface $client Client to send the request to
     */
    public function __construct(BaseGuzzleInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Send a request and return the response
     * 
     * @param string $method Method to use for the request
     * @param string $uri URL of the request
     * @param array $options Options for the request. See Guzzle options for more information
     * 
     * @return \Psr\Http\Message\ResponseInterface Response from the request
     * @throws GuzzleException if the request failed
     */
    public function request($method, $uri, array $options = []): ResponseInterface
    {
        return $this->client->request($method, $uri, $options);
    }
    
}
