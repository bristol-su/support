<?php


namespace BristolSU\Support\Control\Client;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClientContract;
use BristolSU\Support\Control\Contracts\Client\Token;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;

class GuzzleClient implements ControlClientContract
{
    /**
     * @var Client
     */
    private $client;

    private $defaultOptions = [];
    /**
     * @var Token
     */
    private $token;

    public function __construct(ClientInterface $client, Token $token)
    {
        $this->client = $client;
        $this->token = $token;
    }

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
