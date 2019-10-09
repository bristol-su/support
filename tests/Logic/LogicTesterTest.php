<?php

namespace BristolSU\Support\Tests\Logic;

use BristolSU\Support\Authentication\Contracts\Authentication as AuthenticationContract;
use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Filters\Contracts\FilterTester;
use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Logic\AuthenticationModelFactory;
use BristolSU\Support\Logic\FilterFactory;
use BristolSU\Support\Logic\Filters\GroupTagged;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Logic\LogicTester;
use Prophecy\Argument;
use BristolSU\Support\Tests\TestCase;

class LogicTesterTest extends TestCase
{
    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    private $filterTester;

    public function setUp(): void
    {
        parent::setUp();
        $this->filterTester = $this->prophesize(FilterTester::class);
        $this->instance(FilterTester::class, $this->filterTester->reveal());
    }

    public function createFilter($logicId, $type, $evaluated)
    {
        $filter = factory(FilterInstance::class)->create([
            'logic_id' => $logicId,
            'logic_type' => $type,
        ]);

        $this->filterTester->evaluate(Argument::that(function($arg) use ($filter) {
            return $arg->id === $filter->id;
        }))->willReturn($evaluated);
        return $filter;
    }

    /** @test */
    public function it_returns_true_when_all_fields_match_what_they_should_do(){

        $logic = factory(Logic::class)->create();
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'any_true', true);
        $this->createFilter($logic->id, 'any_true', false);
        $this->createFilter($logic->id, 'any_false', false);
        $this->createFilter($logic->id, 'any_false', true);

        $this->assertTrue(
            \BristolSU\Support\Logic\Facade\LogicTester::evaluate($logic)
        );
    }

    /** @test */
    public function it_returns_true_when_there_are_no_all_true_filters(){
        $logic = factory(Logic::class)->create();
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'any_true', true);
        $this->createFilter($logic->id, 'any_true', false);
        $this->createFilter($logic->id, 'any_false', false);
        $this->createFilter($logic->id, 'any_false', true);

        $this->assertTrue(
            \BristolSU\Support\Logic\Facade\LogicTester::evaluate($logic)
        );
    }

    /** @test */
    public function it_returns_true_when_there_are_no_all_false_filters(){
        $logic = factory(Logic::class)->create();
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'any_true', true);
        $this->createFilter($logic->id, 'any_true', false);
        $this->createFilter($logic->id, 'any_false', false);
        $this->createFilter($logic->id, 'any_false', true);

        $this->assertTrue(
            \BristolSU\Support\Logic\Facade\LogicTester::evaluate($logic)
        );
    }

    /** @test */
    public function it_returns_true_when_there_are_no_any_true_filters(){
        $logic = factory(Logic::class)->create();
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'any_false', false);
        $this->createFilter($logic->id, 'any_false', true);

        $this->assertTrue(
            \BristolSU\Support\Logic\Facade\LogicTester::evaluate($logic)
        );
    }

    /** @test */
    public function it_returns_true_when_there_are_no_any_false_filters(){
        $logic = factory(Logic::class)->create();
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'any_true', true);
        $this->createFilter($logic->id, 'any_true', false);

        $this->assertTrue(
            \BristolSU\Support\Logic\Facade\LogicTester::evaluate($logic)
        );
    }

    /** @test */
    public function it_returns_false_if_an_all_true_filter_is_false(){
        $logic = factory(Logic::class)->create();
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_true', false);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'any_true', true);
        $this->createFilter($logic->id, 'any_true', false);
        $this->createFilter($logic->id, 'any_false', false);
        $this->createFilter($logic->id, 'any_false', true);

        $this->assertFalse(
            \BristolSU\Support\Logic\Facade\LogicTester::evaluate($logic)
        );
    }

    /** @test */
    public function it_returns_false_if_all_any_true_filters_are_false(){
        $logic = factory(Logic::class)->create();
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'any_true', false);
        $this->createFilter($logic->id, 'any_true', false);
        $this->createFilter($logic->id, 'any_false', false);
        $this->createFilter($logic->id, 'any_false', true);

        $this->assertFalse(
            \BristolSU\Support\Logic\Facade\LogicTester::evaluate($logic)
        );
    }

    /** @test */
    public function it_returns_false_if_an_all_false_filter_is_true(){
        $logic = factory(Logic::class)->create();
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_false', true);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'any_true', true);
        $this->createFilter($logic->id, 'any_true', false);
        $this->createFilter($logic->id, 'any_false', false);
        $this->createFilter($logic->id, 'any_false', true);

        $this->assertFalse(
            \BristolSU\Support\Logic\Facade\LogicTester::evaluate($logic)
        );
    }

    /** @test */
    public function it_returns_false_if_all_any_false_filters_are_true(){
        $logic = factory(Logic::class)->create();
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_true', true);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'all_false', false);
        $this->createFilter($logic->id, 'any_true', true);
        $this->createFilter($logic->id, 'any_true', false);
        $this->createFilter($logic->id, 'any_false', true);
        $this->createFilter($logic->id, 'any_false', true);

        $this->assertFalse(
            \BristolSU\Support\Logic\Facade\LogicTester::evaluate($logic)
        );
    }

    /** @test */
    public function it_returns_true_if_no_filters_given(){
        $logic = factory(Logic::class)->create();

        $this->assertTrue(
            \BristolSU\Support\Logic\Facade\LogicTester::evaluate($logic)
        );
    }


}
