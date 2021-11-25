<?php

namespace BristolSU\Support\Tests\Logic\Jobs;

use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;
use BristolSU\Support\Logic\Jobs\CacheLogicForUser;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Tests\TestCase;
use Prophecy\Argument;

class CacheLogicForUserTest extends TestCase
{

    /** @test */
    public function it_caches_every_given_user(){
        $user1 = $this->newUser();
        $user2 = $this->newUser();

        $group1 = $this->newGroup();
        $group1->addUser($user1);

        $logic = Logic::factory()->create();

        LogicResult::all()->each(fn($result) => $result->delete());

        $logicTester = $this->prophesize(LogicTester::class);
        $logicTester->evaluate(
            Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($logic)),
            Argument::that(fn($arg) => $arg instanceof User && $arg->is($user1)),
            null, null
        )->shouldBeCalled()->willReturn(true);
        $logicTester->evaluate(
            Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($logic)),
            Argument::that(fn($arg) => $arg instanceof User && $arg->is($user2)),
            null, null
        )->shouldBeCalled()->willReturn(true);
        $logicTester->evaluate(
            Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($logic)),
            Argument::that(fn($arg) => $arg instanceof User && $arg->is($user1)),
            Argument::that(fn($arg) => $arg instanceof Group && $arg->is($group1)),
            null
        )->shouldBeCalled()->willReturn(true);
        $this->instance(LogicTester::class, $logicTester->reveal());

        $job = new CacheLogicForUser([$user1,$user2], $logic->id);
        $job->handle();
    }

}
