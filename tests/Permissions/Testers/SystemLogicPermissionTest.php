<?php


namespace BristolSU\Support\Tests\Permissions\Testers;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Control\Models\User;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Permissions\Contracts\Testers\Tester;
use BristolSU\Support\Permissions\Models\ModelPermission;
use BristolSU\Support\Permissions\Testers\ModuleInstanceLogicOverridePermission;
use BristolSU\Support\Tests\TestCase;

class SystemLogicPermissionTest extends TestCase
{

    /** @test */
    public function can_calls_successor_if_no_logic_permission_found(){
        $logicTester = $this->prophesize(LogicTester::class);
        $tester = new ModuleInstanceLogicOverridePermission($logicTester->reveal(), $this->prophesize(Authentication::class)->reveal());

        $fakeTester = $this->prophesize(Tester::class);
        $fakeTester->can('notfound')->shouldBeCalled();
        $tester->setNext($fakeTester->reveal());

        $tester->can('notfound');
    }

    /** @test */
    public function can_calls_successor_if_no_logic_permission_is_evaluated_as_true(){
        $falseLogic = factory(Logic::class)->create();
        $this->createLogicTester([], [$falseLogic]);

        $permission = factory(ModelPermission::class)->state('logic')->create(['model_id' => $falseLogic->id, 'ability' => 'nottrue']);

        $tester = new ModuleInstanceLogicOverridePermission($this->app->make(LogicTester::class), $this->prophesize(Authentication::class)->reveal());
        $fakeTester = $this->prophesize(Tester::class);
        $fakeTester->can('nottrue')->shouldBeCalled();
        $tester->setNext($fakeTester->reveal());

        $tester->can('nottrue');
    }

    /** @test */
    public function can_returns_the_result_of_the_permission_if_evaluated_as_true(){
        $trueLogic = factory(Logic::class)->create();
        $falseLogic = factory(Logic::class)->create();
        $this->createLogicTester([$trueLogic], [$falseLogic]);

        $permissionFalse = factory(ModelPermission::class)->state('logic')->create(['model_id' => $falseLogic->id, 'ability' => 'inlogicgroup']);
        $permissionTrue = factory(ModelPermission::class)->state('logic')->create(['model_id' => $trueLogic->id, 'ability' => 'inlogicgroup', 'result' => true]);

        $tester = new ModuleInstanceLogicOverridePermission($this->app->make(LogicTester::class), $this->prophesize(Authentication::class)->reveal());

        $this->assertTrue(
            $tester->can('inlogicgroup')
        );
    }

    /** @test */
    public function can_passes_the_user_group_and_role_to_the_logic_tester(){
        $trueLogic = factory(Logic::class)->create();
        $user = new User(['id' => 1]);
        $group = new Group(['id' => 1]);
        $role = new Role(['id' => 2]);
        $this->createLogicTester([$trueLogic], [], $user, $group, $role);

        $permissionTrue = factory(ModelPermission::class)->state('logic')->create(['model_id' => $trueLogic->id, 'ability' => 'inlogicgroup', 'result' => true]);

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn($user);
        $authentication->getGroup()->shouldBeCalled()->willReturn($group);
        $authentication->getRole()->shouldBeCalled()->willReturn($role);

        $tester = new ModuleInstanceLogicOverridePermission($this->app->make(LogicTester::class), $authentication->reveal());
        $this->assertTrue(
            $tester->can('inlogicgroup')
        );
    }


}
