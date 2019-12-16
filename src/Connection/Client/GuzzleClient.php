<?php


namespace BristolSU\Support\Connection\Client;


use BristolSU\Support\Connection\Contracts\Client\Client;
use GuzzleHttp\ClientInterface as BaseGuzzleInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class GuzzleClient
 */
class GuzzleClient implements Client
{
    /**
     * @var BaseGuzzleInterface
     */
    private $client;

    /**
     * GuzzleClient constructor.
     * @param BaseGuzzleInterface $client
     */
    public function __construct(BaseGuzzleInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param $method
     * @param $uri
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     * @throws GuzzleException
     */
    public function request($method, $uri, array $options = []): ResponseInterface
    {
        return $this->client->request($method, $uri, $options);
    }
}
