<?php


namespace BristolSU\Support\Control\Client;


use BristolSU\Support\Control\Contracts\Client\Client as ControlClientContract;
use BristolSU\Support\Control\Contracts\Client\Token;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
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
     * @var Repository
     */
    private $cache;

    /**
     * GuzzleClient constructor.
     * @param ClientInterface $client
     * @param Token $token
     */
    public function __construct(ClientInterface $client, Token $token, Repository $cache)
    {
        $this->client = $client;
        $this->token = $token;
        $this->cache = $cache;
    }

    /**
     * @param $method
     * @param $uri
     * @param array $options
     * @return mixed
     * @throws GuzzleException
     */
    public function request($method, $uri, array $options = [])
    {
        if ($method === 'get' && $this->cache->has(self::class . $uri . json_encode($options))) {
            return $this->cache->get(self::class . $uri . json_encode($options));
        }
        $response = $this->client->request($method, $uri, array_merge(
            [
                'base_uri' => config('control.base_uri') . '/api/',
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token->token()
                ],
            ], $options
        ));
        $content = json_decode($response->getBody()->getContents(), true);
        if ($method === 'get') {
            $this->cache->put(self::class . $uri . json_encode($options), $content, 30);
        }
        return $content;
    }
}
