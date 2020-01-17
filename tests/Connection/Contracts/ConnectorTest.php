<?php

namespace BristolSU\Support\Tests\Connection\Contracts;

use BristolSU\Support\Connection\Contracts\Client\Client;
use BristolSU\Support\Connection\Contracts\Connector;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Form;
use Psr\Http\Message\ResponseInterface;

class ConnectorTest extends TestCase
{

    /** @test */
    public function settings_can_be_set_and_retrieved(){
        $connector = new DummyConnector($this->prophesize(Client::class)->reveal());
        $connector->setSettings(['test1' => 'value1', 'test2' => 'value2']);
        $this->assertEquals('value1', $connector->getSetting('test1'));
        $this->assertEquals('value2', $connector->getSetting('test2'));
    }

    /** @test */
    public function getSetting_returns_the_given_default_value_if_setting_not_found(){
        $connector = new DummyConnector($this->prophesize(Client::class)->reveal());
        $this->assertEquals('default1', $connector->getSetting('test1', 'default1'));
    }

    /** @test */
    public function getSetting_returns_null_if_setting_not_found_and_no_default_given(){
        $connector = new DummyConnector($this->prophesize(Client::class)->reveal());
        $this->assertNull($connector->getSetting('test1'));
    }
    
    /** @test */
    public function client_can_be_retrieved(){
        $client = $this->prophesize(Client::class)->reveal();
        $connector = new DummyConnector($client);
        
        $this->assertSame($client, $connector->getTestClient());
    }
    
}

class DummyConnector extends Connector {

    /**
     * @inheritDoc
     */
    public function request($method, $uri, array $options = [])
    {
    }

    /**
     * @inheritDoc
     */
    public function test(): bool
    {
    }

    /**
     * @inheritDoc
     */
    static public function settingsSchema(): Form
    {
    }

    public function getTestClient()
    {
        return $this->client;
    }
}