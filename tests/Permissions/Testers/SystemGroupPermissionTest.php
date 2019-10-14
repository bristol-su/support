<?php


namespace BristolSU\Support\Tests\Permissions\Testers;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Permissions\Contracts\Testers\Tester;
use BristolSU\Support\Permissions\Models\ModelPermission;
use BristolSU\Support\Permissions\Testers\SystemGroupPermission;
use BristolSU\Support\Tests\TestCase;

class SystemGroupPermissionTest extends TestCase
{

    /** @test */
    public function can_calls_successor_if_group_not_logged_in(){
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getGroup()->shouldBeCalled()->willReturn(null);

        $tester = new SystemGroupPermission($authentication->reveal());

        $fakeTester = $this->prophesize(Tester::class);
        $fakeTester->can('notloggedin')->shouldBeCalled();
        $tester->setNext($fakeTester->reveal());

        $tester->can('notloggedin');
    }

    /** @test */
    public function can_calls_successor_if_no_permission_found(){
        $authentication = $this->prophesize(Authentication::class);
        $group = new Group(['id' => 1]);
        $authentication->getGroup()->shouldBeCalled()->willReturn($group);
        $tester = new SystemGroupPermission($authentication->reveal());

        $fakeTester = $this->prophesize(Tester::class);
        $fakeTester->can('notfound')->shouldBeCalled();
        $tester->setNext($fakeTester->reveal());

        $tester->can('notfound');
    }

    /** @test */
    public function can_returns_the_result_of_the_permission_if_found(){
        $group = new Group(['id' => 1]);
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getGroup()->shouldBeCalled()->willReturn($group);
        $tester = new SystemGroupPermission($authentication->reveal());

        $permission = factory(ModelPermission::class)->state('group')->create(['model_id' => $group->id, 'result' => true]);

        $tester->can($permission->ability);
    }


}
