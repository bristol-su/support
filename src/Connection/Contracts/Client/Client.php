<?php


namespace BristolSU\Support\Connection\Contracts\Client;


interface Client
{
// TODO Add helper methods for manipulating requests
    /**
     * @param $method
     * @param $uri
     * @param array $options
     * @return mixed
     */
    public function request($method, $uri, array $options = []);
    
}