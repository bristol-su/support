<?php


namespace BristolSU\Support\Tests\Permissions\Testers;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Permissions\Contracts\Testers\Tester;
use BristolSU\Support\Permissions\Models\ModelPermission;
use BristolSU\Support\Permissions\Testers\SystemRolePermission;
use BristolSU\Support\Tests\TestCase;

class SystemRolePermissionTest extends TestCase
{

    /** @test */
    public function can_calls_successor_if_role_not_logged_in(){
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getRole()->shouldBeCalled()->willReturn(null);

        $tester = new SystemRolePermission($authentication->reveal());

        $fakeTester = $this->prophesize(Tester::class);
        $fakeTester->can('notloggedin')->shouldBeCalled();
        $tester->setNext($fakeTester->reveal());

        $tester->can('notloggedin');
    }

    /** @test */
    public function can_calls_successor_if_no_permission_found(){
        $authentication = $this->prophesize(Authentication::class);
        $role = new Role(['id' => 1]);
        $authentication->getRole()->shouldBeCalled()->willReturn($role);
        $tester = new SystemRolePermission($authentication->reveal());

        $fakeTester = $this->prophesize(Tester::class);
        $fakeTester->can('notfound')->shouldBeCalled();
        $tester->setNext($fakeTester->reveal());

        $tester->can('notfound');
    }

    /** @test */
    public function can_returns_the_result_of_the_permission_if_found(){
        $role = new Role(['id' => 1]);
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getRole()->shouldBeCalled()->willReturn($role);
        $tester = new SystemRolePermission($authentication->reveal());

        $permission = factory(ModelPermission::class)->state('role')->create(['model_id' => $role->id, 'result' => true]);

        $tester->can($permission->ability);
    }


}
