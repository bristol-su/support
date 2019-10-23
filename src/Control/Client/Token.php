<?php

namespace BristolSU\Support\Control\Client;

use BristolSU\Support\Control\Contracts\Client\Token as TokenContract;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Cache;

/**
 * Class Token
 * @package BristolSU\Support\Control\Client
 */
class Token implements TokenContract
{

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * Token constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param bool $refresh
     * @return mixed
     */
    public function token($refresh = false)
    {
        if (Cache::has(GuzzleClient::class . '@token') && !$refresh) {
            return Cache::get(GuzzleClient::class . '@token');
        }

        $response = $this->client->post(config('control.base_uri') . '/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => config('control.client_id'),
                'client_secret' => config('control.client_secret'),
                'username' => config('control.email'),
                'password' => config('control.password'),
                'scope' => '*',
            ],
        ]);

        $token = json_decode($response->getBody()->getContents(), true);
        Cache::put(GuzzleClient::class . '@token', $token['access_token'], ((int)$token['expires_in']) / 60);

        return $token['access_token'];
    }

}
