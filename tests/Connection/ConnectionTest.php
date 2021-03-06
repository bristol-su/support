<?php

namespace BristolSU\Support\Tests\Connection;

use BristolSU\Support\Connection\Connection;
use BristolSU\Support\Connection\Contracts\ConnectorRepository;
use BristolSU\Support\Connection\RegisteredConnector;
use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\User\User;
use Illuminate\Database\Eloquent\Collection;

class ConnectionTest extends TestCase
{
    /** @test */
    public function connector_gets_the_connector_for_the_connection()
    {
        $connector = $this->prophesize(RegisteredConnector::class)->reveal();
        $connectorRepository = $this->prophesize(ConnectorRepository::class);
        $connectorRepository->get('connector-alias')->shouldBeCalled()->willReturn($connector);
        $this->instance(ConnectorRepository::class, $connectorRepository->reveal());
        
        $connection = factory(Connection::class)->create(['alias' => 'connector-alias']);
        $this->assertSame($connector, $connection->connector());
    }

    /** @test */
    public function connector_attribute_gets_the_connector_for_the_connection()
    {
        $connector = $this->prophesize(RegisteredConnector::class)->reveal();
        $connectorRepository = $this->prophesize(ConnectorRepository::class);
        $connectorRepository->get('connector-alias')->shouldBeCalled()->willReturn($connector);
        $this->instance(ConnectorRepository::class, $connectorRepository->reveal());

        $connection = factory(Connection::class)->create(['alias' => 'connector-alias']);
        $this->assertSame($connector, $connection->connector);
    }
    
    /** @test */
    public function the_global_scope_is_applied()
    {
        $user1 = $this->newUser();
        $user2 = $this->newUser();

        $databaseUser = factory(User::class)->create(['control_id' => $user1->id()]);
        $this->be($databaseUser);
        
        $connection1 = factory(Connection::class)->create(['user_id' => $user1->id()]);
        factory(Connection::class)->create(['user_id' => $user2->id()]);
        
        $availableConnections = Connection::all();
        $this->assertInstanceOf(Collection::class, $availableConnections);
        $this->assertEquals(1, $availableConnections->count());
        $this->assertInstanceOf(Connection::class, $availableConnections->offsetGet(0));
        $this->assertModelEquals($connection1, $availableConnections->offsetGet(0));
    }
    
    /** @test */
    public function user_id_is_automatically_set_if_left_null()
    {
        $user1 = $this->newUser();
        $databaseUser = factory(User::class)->create(['control_id' => $user1->id()]);
        $this->be($databaseUser);

        $connection = factory(Connection::class)->create(['user_id' => null]);

        $this->assertTrue($connection->exists());
        $this->assertEquals($user1->id(), $connection->user_id);
    }

    /** @test */
    public function revisions_are_saved()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $connection = factory(Connection::class)->create(['name' => 'OldName']);

        $connection->name = 'NewName';
        $connection->save();

        $this->assertEquals(1, $connection->revisionHistory->count());
        $this->assertEquals($connection->id, $connection->revisionHistory->first()->revisionable_id);
        $this->assertEquals(Connection::class, $connection->revisionHistory->first()->revisionable_type);
        $this->assertEquals('name', $connection->revisionHistory->first()->key);
        $this->assertEquals('OldName', $connection->revisionHistory->first()->old_value);
        $this->assertEquals('NewName', $connection->revisionHistory->first()->new_value);
    }
}
