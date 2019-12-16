<?php


namespace BristolSU\Support\Connection\Contracts;

use BristolSU\Support\Connection\Contracts\Client\Client;
use FormSchema\Schema\Form;

abstract class Connector
{

    /**
     * @var Client
     */
    protected $client;
    
    /**
     * @var array
     */
    protected $settings;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Prepare and send a request.
     * 
     * You may add authentication, default fields or anything else here. 
     * Call $this->client->request and return the result when prepared.
     * 
     * @param $method
     * @param $uri
     * @param array $options
     * @return mixed
     */
    abstract public function request($method, $uri, array $options = []);

    /**
     * Check the connection is still connected
     * 
     * @return bool
     */
    abstract public function test(): bool;

    /**
     * Get the settings schema to use
     * 
     * @return Form
     */
    abstract static public function settingsSchema(): Form;

    /**
     * Set the settings on the connector
     * 
     * @param array $settings
     */
    public function setSettings(array $settings = []) {
        $this->settings = $settings;
    }

    /**
     * Get a setting value
     *
     * @param string $key
     * @param null $default
     * @return mixed
     * @throws \Exception
     */
    public function getSetting(string $key, $default = null)
    {
        if(array_key_exists($key, $this->settings)) {
            return $this->settings[$key];
        }
        return $default;
    }
    
}