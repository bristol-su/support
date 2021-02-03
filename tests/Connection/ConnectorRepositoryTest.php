<?php

namespace BristolSU\Support\Tests\Connection;

use BristolSU\Support\Connection\ConnectorRepository;
use BristolSU\Support\Connection\ConnectorStore;
use BristolSU\Support\Connection\RegisteredConnector;
use BristolSU\Support\Tests\TestCase;

class ConnectorRepositoryTest extends TestCase
{
    /** @test */
    public function get_returns_a_registered_connector_by_alias()
    {
        $registeredConnector1 = $this->prophesize(RegisteredConnector::class);

        $store = $this->prophesize(ConnectorStore::class);
        $store->get('alias1')->shouldBeCalled()->willReturn($registeredConnector1->reveal());
        
        $repository = new ConnectorRepository($store->reveal());
        $registeredConnector = $repository->get('alias1');

        $this->assertSame($registeredConnector1->reveal(), $registeredConnector);
    }

    /** @test */
    public function get_throws_an_exception_if_connector_not_registered()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Not Registered Message');

        $store = $this->prophesize(ConnectorStore::class);
        $store->get('alias1')->shouldBeCalled()->willThrow(new \Exception('Not Registered Message'));

        $repository = new ConnectorRepository($store->reveal());
        $repository->get('alias1');
    }
    
    /** @test */
    public function for_service_returns_all_connectors_belonging_to_a_service()
    {
        $registeredConnector1 = $this->prophesize(RegisteredConnector::class);
        $registeredConnector1->getService()->shouldBeCalled()->willReturn('service1');
        $registeredConnector2 = $this->prophesize(RegisteredConnector::class);
        $registeredConnector2->getService()->shouldBeCalled()->willReturn('service2');
        $registeredConnector3 = $this->prophesize(RegisteredConnector::class);
        $registeredConnector3->getService()->shouldBeCalled()->willReturn('service1');

        $store = $this->prophesize(ConnectorStore::class);
        $store->all()->shouldBeCalled()->willReturn([
            'alias1' => $registeredConnector1->reveal(),
            'alias2' => $registeredConnector2->reveal(),
            'alias3' => $registeredConnector3->reveal(),
        ]);
        $repository = new ConnectorRepository($store->reveal());
        $registeredConnectors = $repository->forService('service1');

        $this->assertCount(2, $registeredConnectors);
        $this->assertSame($registeredConnector1->reveal(), $registeredConnectors[0]);
        $this->assertSame($registeredConnector3->reveal(), $registeredConnectors[1]);
    }
    
    /** @test */
    public function all_returns_all_registered_connectors()
    {
        $registeredConnector1 = $this->prophesize(RegisteredConnector::class);
        $registeredConnector2 = $this->prophesize(RegisteredConnector::class);
        $registeredConnector3 = $this->prophesize(RegisteredConnector::class);

        $store = $this->prophesize(ConnectorStore::class);
        $store->all()->shouldBeCalled()->willReturn([
            'alias1' => $registeredConnector1->reveal(),
            'alias2' => $registeredConnector2->reveal(),
            'alias3' => $registeredConnector3->reveal(),
        ]);
        $repository = new ConnectorRepository($store->reveal());
        $registeredConnectors = $repository->all();
        
        $this->assertCount(3, $registeredConnectors);
        $this->assertSame($registeredConnector1->reveal(), $registeredConnectors['alias1']);
        $this->assertSame($registeredConnector2->reveal(), $registeredConnectors['alias2']);
        $this->assertSame($registeredConnector3->reveal(), $registeredConnectors['alias3']);
    }
}
