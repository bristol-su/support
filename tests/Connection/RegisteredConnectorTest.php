<?php

namespace BristolSU\Support\Tests\Connection;

use BristolSU\Support\Connection\Contracts\Connector;
use BristolSU\Support\Connection\RegisteredConnector;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Generator\Group;
use FormSchema\Schema\Form;
use FormSchema\Transformers\Transformer;
use FormSchema\Transformers\VFGTransformer;

class RegisteredConnectorTest extends TestCase
{
    /** @test */
    public function name_can_be_set_and_retrieved()
    {
        $registeredConnector = new RegisteredConnector();
        $registeredConnector->setName('name1');
        $this->assertEquals('name1', $registeredConnector->getName());
    }

    /** @test */
    public function description_can_be_set_and_retrieved()
    {
        $registeredConnector = new RegisteredConnector();
        $registeredConnector->setDescription('description1');
        $this->assertEquals('description1', $registeredConnector->getDescription());
    }

    /** @test */
    public function service_can_be_set_and_retrieved()
    {
        $registeredConnector = new RegisteredConnector();
        $registeredConnector->setService('service1');
        $this->assertEquals('service1', $registeredConnector->getService());
    }

    /** @test */
    public function alias_can_be_set_and_retrieved()
    {
        $registeredConnector = new RegisteredConnector();
        $registeredConnector->setAlias('alias1');
        $this->assertEquals('alias1', $registeredConnector->getAlias());
    }

    /** @test */
    public function connector_can_be_set_and_retrieved()
    {
        $registeredConnector = new RegisteredConnector();
        $registeredConnector->setConnector('connector1');
        $this->assertEquals('connector1', $registeredConnector->getConnector());
    }

    /** @test */
    public function __to_string_to_array_and_to_json_returns_the_connector()
    {
        $registeredConnector = new RegisteredConnector();
        $registeredConnector->setName('name1');
        $registeredConnector->setDescription('description1');
        $registeredConnector->setService('service1');
        $registeredConnector->setAlias('alias1');
        $registeredConnector->setConnector(DummyConnector_RegisteredConnector::class);

        $this->assertEquals([
            'name' => 'name1',
            'description' => 'description1',
            'service' => 'service1',
            'alias' => 'alias1',
            'settings' => app(Transformer::class)->transformToArray(DummyConnector_RegisteredConnector::settingsSchema())
        ], $registeredConnector->toArray());

        $this->assertEquals(json_encode([
            'name' => 'name1',
            'description' => 'description1',
            'service' => 'service1',
            'alias' => 'alias1',
            'settings' => app(Transformer::class)->transformToArray(DummyConnector_RegisteredConnector::settingsSchema())
        ]), $registeredConnector->toJson());

        $this->assertEquals(json_encode([
            'name' => 'name1',
            'description' => 'description1',
            'service' => 'service1',
            'alias' => 'alias1',
            'settings' => app(Transformer::class)->transformToArray(DummyConnector_RegisteredConnector::settingsSchema())
        ]), (string) $registeredConnector);
    }
}

class DummyConnector_RegisteredConnector extends Connector
{
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
    public static function settingsSchema(): Form
    {
        return \FormSchema\Generator\Form::make()->withGroup(
            Group::make('legend1')
        )->getSchema();
    }
}
