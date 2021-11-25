<?php

namespace BristolSU\Support\Tests\Logic\Jobs;

use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;
use BristolSU\Support\Logic\Jobs\CacheLogicForGroup;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Tests\TestCase;
use Prophecy\Argument;

class CacheLogicForGroupTest extends TestCase
{

    /** @test */
    public function it_caches_every_given_group(){
        $group1 = $this->newGroup();
        $group2 = $this->newGroup();

        $user1 = $this->newUser();
        $user1->addGroup($group1);
        $user1->addGroup($group2);

        $logic = Logic::factory()->create();

        LogicResult::all()->each(fn($result) => $result->delete());

        $logicTester = $this->prophesize(LogicTester::class);
        $logicTester->evaluate(
            Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($logic)),
            Argument::that(fn($arg) => $arg instanceof User && $arg->is($user1)),
            Argument::that(fn($arg) => $arg instanceof Group && $arg->is($group1)),
            null
        )->shouldBeCalled()->willReturn(true);
        $logicTester->evaluate(
            Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($logic)),
            Argument::that(fn($arg) => $arg instanceof User && $arg->is($user1)),
            Argument::that(fn($arg) => $arg instanceof Group && $arg->is($group2)),
            null
        )->shouldBeCalled()->willReturn(true);
        $logicTester->evaluate(
            Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($logic)),
            Argument::that(fn($arg) => $arg instanceof User && $arg->is($user1)),
            null, null
        )->shouldBeCalled()->willReturn(true);
        $this->instance(LogicTester::class, $logicTester->reveal());

        $job = new CacheLogicForGroup([$group1,$group2], $logic->id);
        $job->handle();
    }

}
