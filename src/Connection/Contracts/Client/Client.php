<?php


namespace BristolSU\Support\Connection\Contracts\Client;


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