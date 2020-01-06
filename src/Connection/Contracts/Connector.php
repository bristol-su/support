<?php

namespace BristolSU\Support\Connection\Contracts;

use BristolSU\Support\Connection\Contracts\Client\Client;
use FormSchema\Schema\Form;
use Psr\Http\Message\ResponseInterface;

/**
 * Represents a third party connector
 */
abstract class Connector
{

    /**
     * Underlying client instance
     * 
     * @var Client
     */
    protected $client;
    
    /**
     * Settings requested by the connector and given by the user
     * 
     * @var array
     */
    protected $settings;

    /**
     * @param Client $client Client to make the request through
     */
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
     * @param string $method Method to use for the request
     * @param string $uri URI to use for the request
     * @param array $options Options to use for the request. See Guzzle options for more information
     * @return ResponseInterface
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
     * @return Form Setting schema representation
     */
    abstract static public function settingsSchema(): Form;

    /**
     * Set the settings on the connector
     * 
     * @param array $settings Settings for the connector
     */
    public function setSettings(array $settings = []) {
        $this->settings = $settings;
    }

    /**
     * Get a setting value
     *
     * @param string $key Key of the setting
     * @param null $default Default value if the setting is not found. Defaults to null
     * 
     * @return mixed
     */
    public function getSetting(string $key, $default = null)
    {
        if(array_key_exists($key, $this->settings)) {
            return $this->settings[$key];
        }
        return $default;
    }
    
}