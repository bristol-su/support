<?php

namespace BristolSU\Support\Tests\Connection;

use BristolSU\Support\Connection\Connection;
use BristolSU\Support\Connection\ConnectionRepository;
use BristolSU\Support\Connection\Contracts\ConnectorRepository;
use BristolSU\Support\Connection\RegisteredConnector;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class ConnectionRepositoryTest extends TestCase
{
    /** @test */
    public function all_returns_all_available_connections()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $connections = factory(Connection::class, 5)->create(['user_id' => $user->id()]);

        $repository = new ConnectionRepository();
        $allConnections = $repository->all();

        $this->assertInstanceOf(Collection::class, $allConnections);
        $this->assertEquals(5, $allConnections->count());
        foreach ($connections as $connection) {
            $this->assertModelEquals($connection, $allConnections->shift());
        }
    }

    /** @test */
    public function get_returns_a_connection_by_id()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $connection = factory(Connection::class)->create(['user_id' => $user->id()]);

        $repository = new ConnectionRepository();
        $retrievedConnection = $repository->get($connection->id);

        $this->assertInstanceOf(Connection::class, $retrievedConnection);
        $this->assertModelEquals($connection, $retrievedConnection);
    }

    /** @test */
    public function delete_deletes_a_connection()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $connection = factory(Connection::class)->create(['user_id' => $user->id()]);

        $this->assertDatabaseHas('connection_instances', ['id' => $connection->id]);

        $repository = new ConnectionRepository();
        $repository->delete($connection->id);

        $this->assertDatabaseMissing('connection_instances', ['id' => $connection->id]);
    }

    /** @test */
    public function create_creates_and_returns_a_connection()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $repository = new ConnectionRepository();
        $connection = $repository->create([
            'name' => 'Name1',
            'description' => 'Description1',
            'alias' => 'conn-alias',
            'settings' => [
                'setting1' => 'value1'
            ]
        ]);

        $this->assertDatabaseHas('connection_instances', [
            'name' => 'Name1',
            'description' => 'Description1',
            'alias' => 'conn-alias',
            'settings' => '{"setting1":"value1"}',
            'id' => $connection->id
        ]);
    }

    /** @test */
    public function update_updates_the_given_connection()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $connection = factory(Connection::class)->create([
            'name' => 'Name1',
            'description' => 'Description1',
            'alias' => 'conn-alias',
            'settings' => [
                'setting1' => 'value1'
            ]
        ]);

        $repository = new ConnectionRepository();
        $repository->update($connection->id, [
            'name' => 'Name2',
            'description' => 'Description2',
            'alias' => 'conn-alias',
            'settings' => [
                'setting2' => 'value2'
            ]
        ]);

        $this->assertDatabaseHas('connection_instances', [
            'name' => 'Name2',
            'description' => 'Description2',
            'alias' => 'conn-alias',
            'settings' => '{"setting2":"value2"}',
            'id' => $connection->id
        ]);
    }

    /** @test */
    public function get_all_for_service_gets_all_connections_for_a_service()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $connector1 = $this->prophesize(RegisteredConnector::class);
        $connector2 = $this->prophesize(RegisteredConnector::class);
        $connector1->getAlias()->willReturn('alias1');
        $connector2->getAlias()->willReturn('alias2');

        $connectorRepository = $this->prophesize(ConnectorRepository::class);
        $connectorRepository->forService('service-alias')->willReturn([$connector1->reveal(), $connector2->reveal()]);
        $this->instance(ConnectorRepository::class, $connectorRepository->reveal());

        $connection1 = factory(Connection::class)->create(['alias' => 'alias1', 'user_id' => $user->id()]);
        $connection2 = factory(Connection::class)->create(['alias' => 'alias1', 'user_id' => $user->id()]);
        $connection3 = factory(Connection::class)->create(['alias' => 'alias2', 'user_id' => $user->id()]);
        $connection4 = factory(Connection::class)->create(['alias' => 'alias2', 'user_id' => $user->id()]);

        $repository = new ConnectionRepository();
        $connections = $repository->getAllForService('service-alias');

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $connections);
        $this->assertEquals(4, $connections->count());
        $this->assertModelEquals($connection1, $connections->shift());
        $this->assertModelEquals($connection2, $connections->shift());
        $this->assertModelEquals($connection3, $connections->shift());
        $this->assertModelEquals($connection4, $connections->shift());
    }

    /** @test */
    public function get_all_for_connector_returns_all_connections_with_an_alias()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $connection1 = factory(Connection::class)->create(['alias' => 'alias1', 'user_id' => $user->id()]);
        $connection2 = factory(Connection::class)->create(['alias' => 'alias1', 'user_id' => $user->id()]);

        $repository = new ConnectionRepository();
        $connections = $repository->getAllForConnector('alias1');

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $connections);
        $this->assertEquals(2, $connections->count());
        $this->assertModelEquals($connection1, $connections->shift());
        $this->assertModelEquals($connection2, $connections->shift());
    }
}
