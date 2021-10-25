<?php

namespace BristolSU\Support\Tests\Logic\Traits;

use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Logic\LogicTester;
use BristolSU\Support\Logic\Traits\CachesLogic;
use BristolSU\Support\Tests\TestCase;
use Database\Factories\LogicFactory;
use Prophecy\Argument;

class CachesLogicTest extends TestCase
{
    use CachesLogic;

    /** @test */
    public function it_evaluates_the_logic_if_an_id_is_given(){
        $logic = Logic::factory()->create();
        $user = $this->newUser();
        $role = $this->newRole();
        LogicResult::factory()->forLogic($logic)->forUser($user)->forRole($role)->passing()->create();

        $logicTester = $this->prophesize(LogicTester::class);
        $logicTester->evaluate(
            Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($logic)),
            Argument::that(fn($arg) => $arg instanceof User && $arg->is($user)),
            Argument::that(fn($arg) => $arg instanceof Group && $arg->is($role->group())),
            Argument::that(fn($arg) => $arg instanceof Role && $arg->is($role)),
        )->shouldBeCalled()->willReturn(false);
        $this->app->instance(LogicTester::class, $logicTester->reveal());

        $this->assertEquals(1, LogicResult::where(['user_id' => $user->id(), 'group_id' => $role->groupId(), 'role_id' => $role->id(), 'logic_id' => $logic->id, 'result' => true])->count());
        $this->cacheLogic($logic->id, $user, $role->group(), $role);

        $this->assertEquals(0, LogicResult::where(['user_id' => $user->id(), 'group_id' => $role->groupId(), 'role_id' => $role->id(), 'logic_id' => $logic->id, 'result' => true])->count());
        $this->assertEquals(1, LogicResult::where(['user_id' => $user->id(), 'group_id' => $role->groupId(), 'role_id' => $role->id(), 'logic_id' => $logic->id, 'result' => false])->count());
    }

    /** @test */
    public function it_evaluates_the_logic_if_an_id_is_not_given(){
        $logic1 = Logic::factory()->create();
        $logic2 = Logic::factory()->create();
        $user = $this->newUser();
        $role = $this->newRole();
        LogicResult::factory()->forLogic($logic1)->forUser($user)->forRole($role)->passing()->create();
        LogicResult::factory()->forLogic($logic2)->forUser($user)->forRole($role)->passing()->create();

        $logicTester = $this->prophesize(LogicTester::class);
        $logicTester->evaluate(
            Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($logic1)),
            Argument::that(fn($arg) => $arg instanceof User && $arg->is($user)),
            Argument::that(fn($arg) => $arg instanceof Group && $arg->is($role->group())),
            Argument::that(fn($arg) => $arg instanceof Role && $arg->is($role)),
        )->shouldBeCalled()->willReturn(false);
        $logicTester->evaluate(
            Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($logic2)),
            Argument::that(fn($arg) => $arg instanceof User && $arg->is($user)),
            Argument::that(fn($arg) => $arg instanceof Group && $arg->is($role->group())),
            Argument::that(fn($arg) => $arg instanceof Role && $arg->is($role)),
        )->shouldBeCalled()->willReturn(true);

        $this->app->instance(LogicTester::class, $logicTester->reveal());

        $this->assertEquals(1, LogicResult::where(['user_id' => $user->id(), 'group_id' => $role->groupId(), 'role_id' => $role->id(), 'logic_id' => $logic1->id, 'result' => true])->count());
        $this->assertEquals(1, LogicResult::where(['user_id' => $user->id(), 'group_id' => $role->groupId(), 'role_id' => $role->id(), 'logic_id' => $logic2->id, 'result' => true])->count());

        $this->cacheLogic(null, $user, $role->group(), $role);

        $this->assertEquals(0, LogicResult::where(['user_id' => $user->id(), 'group_id' => $role->groupId(), 'role_id' => $role->id(), 'logic_id' => $logic1->id, 'result' => true])->count());
        $this->assertEquals(1, LogicResult::where(['user_id' => $user->id(), 'group_id' => $role->groupId(), 'role_id' => $role->id(), 'logic_id' => $logic1->id, 'result' => false])->count());

        $this->assertEquals(0, LogicResult::where(['user_id' => $user->id(), 'group_id' => $role->groupId(), 'role_id' => $role->id(), 'logic_id' => $logic2->id, 'result' => false])->count());
        $this->assertEquals(1, LogicResult::where(['user_id' => $user->id(), 'group_id' => $role->groupId(), 'role_id' => $role->id(), 'logic_id' => $logic2->id, 'result' => true])->count());
    }

}
