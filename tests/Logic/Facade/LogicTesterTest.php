<?php


namespace BristolSU\Support\Tests\Logic\Facade;


use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use Prophecy\Argument;
use BristolSU\Support\Tests\TestCase;

class LogicTesterTest extends TestCase
{

    /** @test */
    public function evaluate_can_be_called(){
        $logic = factory(Logic::class)->create();
        $logicTester = $this->prophesize(LogicTester::class);
        $logicTester->evaluate(Argument::that(function($logicArg) use ($logic) {
            return $logicArg->id === $logic->id;
        }))->shouldBeCalled();
        $this->instance(LogicTester::class, $logicTester->reveal());

        \BristolSU\Support\Logic\Facade\LogicTester::evaluate($logic);
    }

}
