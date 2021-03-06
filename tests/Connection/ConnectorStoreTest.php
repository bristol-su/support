<?php

namespace BristolSU\Support\Tests\Connection;

use BristolSU\Support\Connection\ConnectorStore;
use BristolSU\Support\Connection\RegisteredConnector;
use BristolSU\Support\Tests\TestCase;

class ConnectorStoreTest extends TestCase
{
    /** @test */
    public function registered_connectors_can_be_registered_and_retrieved()
    {
        $registeredConnector = $this->prophesize(RegisteredConnector::class);
        $registeredConnector->getAlias()->shouldBeCalled()->willReturn('alias1');
        
        $store = new ConnectorStore();
        $store->registerConnector($registeredConnector->reveal());
        
        $this->assertSame($registeredConnector->reveal(), $store->get('alias1'));
    }
    
    /** @test */
    public function an_exception_is_thrown_if_a_retrieved_registered_connector_does_not_exist()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Connector alias1 not registered');

        $store = new ConnectorStore();
        $store->get('alias1');
    }
    
    /** @test */
    public function all_registered_connectors_can_be_retrieved()
    {
        $registeredConnector1 = $this->prophesize(RegisteredConnector::class);
        $registeredConnector1->getAlias()->shouldBeCalled()->willReturn('alias1');
        $registeredConnector2 = $this->prophesize(RegisteredConnector::class);
        $registeredConnector2->getAlias()->shouldBeCalled()->willReturn('alias2');
        $registeredConnector3 = $this->prophesize(RegisteredConnector::class);
        $registeredConnector3->getAlias()->shouldBeCalled()->willReturn('alias3');

        $store = new ConnectorStore();
        $store->registerConnector($registeredConnector1->reveal());
        $store->registerConnector($registeredConnector3->reveal());
        $store->registerConnector($registeredConnector2->reveal());
        
        $this->assertCount(3, $store->all());
        $this->assertSame($registeredConnector1->reveal(), $store->all()['alias1']);
        $this->assertSame($registeredConnector2->reveal(), $store->all()['alias2']);
        $this->assertSame($registeredConnector3->reveal(), $store->all()['alias3']);
    }
    
    /** @test */
    public function registered_connectors_can_be_registered_using_attributes()
    {
        $store = new ConnectorStore();
        $store->register('name1', 'description1', 'alias1', 'service1', 'connector1');

        $registeredConnector = $store->get('alias1');
        $this->assertEquals('name1', $registeredConnector->getName());
        $this->assertEquals('description1', $registeredConnector->getDescription());
        $this->assertEquals('alias1', $registeredConnector->getAlias());
        $this->assertEquals('service1', $registeredConnector->getService());
        $this->assertEquals('connector1', $registeredConnector->getConnector());
    }
}
