<?php

namespace BristolSU\Support\Tests\Testing\LogicTester;

use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Testing\LogicTester\LogicTesterFake;
use BristolSU\Support\Testing\LogicTester\LogicTesterResult;
use BristolSU\Support\Tests\TestCase;

class LogicTesterFakeTest extends TestCase
{

    /** @test */
    public function forLogic_returns_a_logic_tester_result(){
        $logic = factory(Logic::class)->create();
        $logicTester = new LogicTesterFake();
        $logicTesterResult = $logicTester->forLogic($logic);
        
        $this->assertInstanceOf(LogicTesterResult::class, $logicTesterResult);
    }
    
    /** @test */
    public function forLogic_returns_a_previous_logic_tester_result_class_if_the_same_logic_group_is_used(){
        $logic = factory(Logic::class)->create();
        $logicTester = new LogicTesterFake();
        $logicTesterResult1 = $logicTester->forLogic($logic);
        $logicTesterResult2 = $logicTester->forLogic($logic);

        $this->assertSame($logicTesterResult1, $logicTesterResult2);
    }

    /** @test */
    public function bind_binds_the_logic_tester_as_a_singleton()
    {
        $logicTester = new LogicTesterFake();
        $logicTester->bind();
        
        $this->assertSame($logicTester, app()->make(LogicTester::class));
    }
    
    /** @test */
    public function evaluate_returns_false_if_forLogic_has_not_been_called()
    {
        $logic = factory(Logic::class)->create();
        $this->assertFalse(
            $this->logicTester()->evaluate($logic)
        );
    }
    
    /** @test */
    public function evaluate_passes_the_evaluate_test_onto_the_logic_tester_result(){
        $logic = factory(Logic::class)->create();
        
        $logicTesterResult = $this->prophesize(LogicTesterResult::class);
        $logicTesterResult->evaluate(null, null, null)->shouldBeCalled()->willReturn(true);
        
        $resultsProperty = (new \ReflectionClass(LogicTesterFake::class))->getProperty('results');
        $resultsProperty->setAccessible(true);
        $resultsProperty->setValue($this->logicTester(), [$logic->id => $logicTesterResult->reveal()]);
        $this->assertTrue(
            $this->logicTester()->evaluate($logic)
        );
    }
    
}