<?php


namespace BristolSU\Support\Control\Client;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClientContract;
use BristolSU\Support\Control\Contracts\Client\Token;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;

/**
 * Class GuzzleClient
 * @package BristolSU\Support\Control\Client
 */
class GuzzleClient implements ControlClientContract
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $defaultOptions = [];
    /**
     * @var Token
     */
    private $token;

    /**
     * GuzzleClient constructor.
     * @param ClientInterface $client
     * @param Token $token
     */
    public function __construct(ClientInterface $client, Token $token)
    {
        $this->client = $client;
        $this->token = $token;
    }

    /**
     * @param $method
     * @param $uri
     * @param array $options
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($method, $uri, array $options = [])
    {
        $response = $this->client->request($method, $uri, array_merge(
            [
                'base_uri' => config('control.base_uri') . '/api/',
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$this->token->token()
                ],
            ], $options
        ));
        return json_decode($response->getBody()->getContents(), true);
    }
}
