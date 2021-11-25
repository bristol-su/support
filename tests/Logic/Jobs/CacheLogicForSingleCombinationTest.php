<?php

namespace BristolSU\Support\Tests\Logic\Jobs;

use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;
use BristolSU\Support\Logic\Jobs\CacheLogicForGroup;
use BristolSU\Support\Logic\Jobs\CacheLogicForSingleCombination;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Tests\TestCase;
use Prophecy\Argument;

class CacheLogicForSingleCombinationTest extends TestCase
{

    /** @test */
    public function it_caches_the_given_combination(){
        $group = $this->newGroup();
        $user = $this->newUser();

        $logic = Logic::factory()->create();

        LogicResult::all()->each(fn($result) => $result->delete());

        $logicTester = $this->prophesize(LogicTester::class);
        $logicTester->evaluate(
            Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($logic)),
            Argument::that(fn($arg) => $arg instanceof User && $arg->is($user)),
            Argument::that(fn($arg) => $arg instanceof Group && $arg->is($group)),
            null
        )->shouldBeCalled()->willReturn(true);
        $this->instance(LogicTester::class, $logicTester->reveal());

        $job = new CacheLogicForSingleCombination($logic->id, $user, $group, null);
        $job->handle();
    }

}
