<?php


namespace BristolSU\Support\Tests\Permissions;

use BristolSU\Support\Permissions\Contracts\PermissionStore;
use BristolSU\Support\Permissions\Facade\Permission as PermissionFacade;
use BristolSU\Support\Permissions\Models\Permission;
use BristolSU\Support\Permissions\PermissionRepository;
use BristolSU\Support\Tests\TestCase;

class PermissionRepositoryTest extends TestCase
{
    /** @test */
    public function it_calls_the_get_function_on_permission_store()
    {
        $ability = 'ability';
        $name = 'name';
        $description = 'description';
        $type = 'global';
        $permission = new Permission($ability, $name, $description, $type);

        $permissionStore = $this->prophesize(PermissionStore::class);
        $permissionStore->get($ability)->shouldBeCalled()->willReturn($permission);

        $permissionRepository = new PermissionRepository($permissionStore->reveal());

        $this->assertEquals($ability, $permissionRepository->get($ability)->getAbility());
        $this->assertEquals($name, $permissionRepository->get($ability)->getName());
        $this->assertEquals($description, $permissionRepository->get($ability)->getDescription());
        $this->assertEquals($type, $permissionRepository->get($ability)->getType());
    }

    /** @test */
    public function it_gets_a_permission_by_ability()
    {
        PermissionFacade::registerSitePermission('a1', 'n1', 'd1');
        PermissionFacade::registerSitePermission('a2', 'n2', 'd2');

        $permissionRepository = new PermissionRepository($this->app->make(PermissionStore::class));

        $this->assertEquals('a1', $permissionRepository->get('a1')->getAbility());
        $this->assertEquals('n1', $permissionRepository->get('a1')->getName());
        $this->assertEquals('d1', $permissionRepository->get('a1')->getDescription());
        $this->assertEquals('global', $permissionRepository->get('a1')->getType());
        $this->assertEquals('a2', $permissionRepository->get('a2')->getAbility());
        $this->assertEquals('n2', $permissionRepository->get('a2')->getName());
        $this->assertEquals('d2', $permissionRepository->get('a2')->getDescription());
        $this->assertEquals('global', $permissionRepository->get('a1')->getType());
    }

    /** @test */
    public function for_module_returns_all_permissions_for_the_given_module_alias()
    {
        $permission1 = new Permission('a1', 'n1', 'd1', 'module', 'al1');
        $permission2 = new Permission('a2', 'n2', 'd2', 'module', 'al1');
        $permission3 = new Permission('a3', 'n3', 'd3', 'module', 'al2');
        $permission4 = new Permission('a4', 'n4', 'd4', 'module', 'al1');
        $permission5 = new Permission('a5', 'n5', 'd5', 'module', 'al3');

        $permissionStore = $this->prophesize(PermissionStore::class);
        $permissionStore->all()->shouldBeCalled()->willReturn([
            $permission1, $permission2, $permission3, $permission4, $permission5
        ]);

        $repository = new PermissionRepository($permissionStore->reveal());
        $modulePermissions = $repository->forModule('al1');

        $this->assertCount(3, $modulePermissions);
        $this->assertEquals($permission1->toArray(), $modulePermissions[0]);
        $this->assertEquals($permission2->toArray(), $modulePermissions[1]);
        $this->assertEquals($permission4->toArray(), $modulePermissions[2]);
    }

    /** @test */
    public function all_returns_all_permissions()
    {
        $permission1 = new Permission('a1', 'n1', 'd1', 'module', 'al1');
        $permission2 = new Permission('a2', 'n2', 'd2', 'module', 'al1');
        $permission3 = new Permission('a3', 'n3', 'd3', 'module', 'al2');
        $permission4 = new Permission('a4', 'n4', 'd4', 'module', 'al1');
        $permission5 = new Permission('a5', 'n5', 'd5', 'module', 'al3');

        $permissionStore = $this->prophesize(PermissionStore::class);
        $permissionStore->all()->shouldBeCalled()->willReturn([
            $permission1, $permission2, $permission3, $permission4, $permission5
        ]);

        $repository = new PermissionRepository($permissionStore->reveal());
        $modulePermissions = $repository->all();

        $this->assertCount(5, $modulePermissions);
        $this->assertEquals($permission1, $modulePermissions[0]);
        $this->assertEquals($permission2, $modulePermissions[1]);
        $this->assertEquals($permission3, $modulePermissions[2]);
        $this->assertEquals($permission4, $modulePermissions[3]);
        $this->assertEquals($permission5, $modulePermissions[4]);
    }
}
