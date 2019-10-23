<?php


namespace BristolSU\Support\Control\Contracts\Client;


use GuzzleHttp\ClientInterface;

/**
 * Interface Client
 * @package BristolSU\Support\Control\Contracts\Client
 */
interface Client
{
    /**
     * @param $method
     * @param $uri
     * @param array $options
     * @return mixed
     */
    public function request($method, $uri, array $options = []);
}
