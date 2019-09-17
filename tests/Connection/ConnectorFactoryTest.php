<?php

namespace BristolSU\Support\Tests\Connection;

use BristolSU\Support\Connection\Connection;
use BristolSU\Support\Connection\ConnectorFactory;
use BristolSU\Support\Connection\Contracts\Client\Client;
use BristolSU\Support\Connection\Contracts\Connector;
use BristolSU\Support\Connection\Contracts\ConnectorRepository;
use BristolSU\Support\Connection\RegisteredConnector;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Form;
use Psr\Http\Message\ResponseInterface;

class ConnectorFactoryTest extends TestCase
{

    /** @test */
    public function createFromConnection_sets_the_settings_on_the_connector(){
        $connection = factory(Connection::class)->create(['alias' => 'alias1', 'settings' => ['test1' => 'val1']]);
        
        $this->instance(Client::class, $this->prophesize(Client::class)->reveal());
        
        $registeredConnector = $this->prophesize(RegisteredConnector::class);
        $registeredConnector->getConnector()->shouldBeCalled()->willReturn(DummyConnector::class);
        
        $connectorRepository = $this->prophesize(ConnectorRepository::class);
        $connectorRepository->get('alias1')->shouldBeCalled()->willReturn($registeredConnector->reveal());
        $this->instance(ConnectorRepository::class, $connectorRepository->reveal());
        
        $factory = new ConnectorFactory();
        $builtConnector = $factory->createFromConnection($connection);
        
        $this->assertEquals('val1', $builtConnector->getSetting('test1'));
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
}