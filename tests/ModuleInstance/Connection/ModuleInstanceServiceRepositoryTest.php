<?php

namespace BristolSU\Support\Tests\ModuleInstance\Connection;

use BristolSU\Support\Connection\Connection;
use BristolSU\Support\Connection\ConnectorFactory;
use BristolSU\Support\Connection\Contracts\Connector;
use BristolSU\Support\ModuleInstance\Connection\ModuleInstanceService;
use BristolSU\Support\ModuleInstance\Connection\ModuleInstanceServiceRepository;
use BristolSU\Support\ModuleInstance\Connection\NoConnectionAvailable;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;
use Prophecy\Argument;

class ModuleInstanceServiceRepositoryTest extends TestCase
{

    /** @test */
    public function getConnectorForService_throws_an_exception_if_no_service_found(){

        $this->expectException(NoConnectionAvailable::class);
        $this->expectExceptionMessage('No connection has been found for test-service-alias');

        $repository = new ModuleInstanceServiceRepository();
        $repository->getConnectorForService('test-service-alias', 1);
    }

    /** @test */
    public function getConnectorForService_creates_and_returns_a_connector_from_a_found_connection(){
        $user = $this->newUser();
        $this->beUser($user);

        $moduleInstance = factory(ModuleInstance::class)->create();
        $connection = factory(Connection::class)->create();
        $moduleInstanceService = factory(ModuleInstanceService::class)->create([
            'module_instance_id' => $moduleInstance->id, 'service' => 'test-service-alias', 'connection_id' => $connection->id
        ]);

        $connectorFactory = $this->prophesize(ConnectorFactory::class);
        $connector = $this->prophesize(Connector::class);
        $connectorFactory->createFromConnection(Argument::that(function($arg) use ($connection) {
            return $arg instanceof Connection && $connection->id === $arg->id;
        }))->shouldBeCalled()->willReturn($connector->reveal());
        $this->instance(ConnectorFactory::class, $connectorFactory->reveal());

        $repository = new ModuleInstanceServiceRepository();
        $newConnector = $repository->getConnectorForService('test-service-alias', $moduleInstance->id);

        $this->assertSame($connector->reveal(), $newConnector);
    }

    /** @test */
    public function all_returns_all_module_instance_services(){
        $moduleInstanceServices = factory(ModuleInstanceService::class, 10)->create();

        $repository = new ModuleInstanceServiceRepository();
        $foundServices = $repository->all();

        $this->assertEquals(10, $foundServices->count());
        $this->assertContainsOnlyInstancesOf(ModuleInstanceService::class, $foundServices);
        foreach($moduleInstanceServices as $service) {
            $this->assertModelEquals($service, $foundServices->shift());
        }
    }

}
