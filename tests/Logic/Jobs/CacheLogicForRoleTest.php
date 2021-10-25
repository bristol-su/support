<?php

namespace BristolSU\Support\Tests\Logic\Jobs;

use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\User;
use BristolSU\ControlDB\Models\Role;
use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;
use BristolSU\Support\Logic\Jobs\CacheLogicForRole;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Tests\TestCase;
use Prophecy\Argument;

class CacheLogicForRoleTest extends TestCase
{

    /** @test */
    public function it_caches_every_given_role(){
        $role1 = $this->newRole();
        $role2 = $this->newRole();

        $user1 = $this->newUser();
        $user1->addRole($role1);
        $user1->addRole($role2);

        $logic = Logic::factory()->create();

        LogicResult::all()->each(fn($result) => $result->delete());

        $logicTester = $this->prophesize(LogicTester::class);
        $logicTester->evaluate(
            Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($logic)),
            Argument::that(fn($arg) => $arg instanceof User && $arg->is($user1)),
            Argument::that(fn($arg) => $arg instanceof Group && $arg->is($role1->group())),
            Argument::that(fn($arg) => $arg instanceof Role && $arg->is($role1)),
        )->shouldBeCalled()->willReturn(true);
        $logicTester->evaluate(
            Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($logic)),
            Argument::that(fn($arg) => $arg instanceof User && $arg->is($user1)),
            Argument::that(fn($arg) => $arg instanceof Group && $arg->is($role2->group())),
            Argument::that(fn($arg) => $arg instanceof Role && $arg->is($role2)),
        )->shouldBeCalled()->willReturn(true);
        $logicTester->evaluate(
            Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($logic)),
            Argument::that(fn($arg) => $arg instanceof User && $arg->is($user1)),
            null, null
        )->shouldBeCalled()->willReturn(true);
        $this->instance(LogicTester::class, $logicTester->reveal());

        $job = new CacheLogicForRole([$role1,$role2], $logic->id);
        $job->handle();
    }

}
