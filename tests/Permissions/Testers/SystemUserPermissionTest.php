<?php


namespace BristolSU\Support\Tests\Permissions\Testers;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Models\User;
use BristolSU\Support\Permissions\Contracts\Testers\Tester;
use BristolSU\Support\Permissions\Models\ModelPermission;
use BristolSU\Support\Permissions\Testers\SystemUserPermission;
use BristolSU\Support\Tests\TestCase;

class SystemUserPermissionTest extends TestCase
{

    /** @test */
    public function can_calls_successor_if_user_not_logged_in(){
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn(null);

        $tester = new SystemUserPermission($authentication->reveal());

        $fakeTester = $this->prophesize(Tester::class);
        $fakeTester->can('notloggedin')->shouldBeCalled();
        $tester->setNext($fakeTester->reveal());

        $tester->can('notloggedin');
    }

    /** @test */
    public function can_calls_successor_if_no_permission_found(){
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn(new User(['id' => 1]));
        $tester = new SystemUserPermission($authentication->reveal());

        $fakeTester = $this->prophesize(Tester::class);
        $fakeTester->can('notfound')->shouldBeCalled();
        $tester->setNext($fakeTester->reveal());

        $tester->can('notfound');
    }

    /** @test */
    public function can_returns_the_result_of_the_permission_if_found(){
        $user = new User(['id' => 1]);
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn($user);
        $tester = new SystemUserPermission($authentication->reveal());

        $permission = factory(ModelPermission::class)->state('user')->create(['model_id' => $user->id, 'result' => true]);

        $tester->can($permission->ability);
    }


}
