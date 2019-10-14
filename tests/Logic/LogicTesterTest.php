<?php

namespace BristolSU\Support\Tests\Logic;

use BristolSU\Support\Control\Contracts\Models\Group as GroupContract;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Filters\Contracts\FilterTester;
use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Logic\LogicTester;
use BristolSU\Support\User\User;
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
    
    /** @test */
    public function it_sets_a_user_when_given(){
        $logic = factory(Logic::class)->create();
        $user = factory(User::class)->create();
        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->setUser(Argument::that(function($arg) use ($user) {
            return $user->id === $arg->id;
        }))->shouldBeCalled();

        $logicTester = new LogicTester($filterTester->reveal());
        $logicTester->evaluate($logic, $user);
    }

    /** @test */
    public function it_does_not_set_a_user_when_not_given(){
        $logic = factory(Logic::class)->create();
        $user = factory(User::class)->create();
        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->setUser(Argument::that(function($arg) use ($user) {
            return $user->id === $arg->id;
        }))->shouldNotBeCalled();
        
        $logicTester = new LogicTester($filterTester->reveal());
        $logicTester->evaluate($logic);
    }

    /** @test */
    public function it_sets_a_group_when_given(){
        $logic = factory(Logic::class)->create();
        $group = new Group(['id' => 1]);
        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->setGroup($group)->shouldBeCalled();

        $logicTester = new LogicTester($filterTester->reveal());
        $logicTester->evaluate($logic, null, $group);
    }

    /** @test */
    public function it_does_not_set_a_group_when_not_given(){
        $logic = factory(Logic::class)->create();
        $group = new Group(['id' => 1]);
        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->setGroup($group)->shouldNotBeCalled();

        $logicTester = new LogicTester($filterTester->reveal());
        $logicTester->evaluate($logic);
    }

    /** @test */
    public function it_sets_a_role_when_given(){
        $logic = factory(Logic::class)->create();
        $role = new Role(['id' => 1]);
        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->setRole($role)->shouldBeCalled();

        $logicTester = new LogicTester($filterTester->reveal());
        $logicTester->evaluate($logic, null, null, $role);
    }

    /** @test */
    public function it_does_not_set_a_role_when_not_given(){
        $logic = factory(Logic::class)->create();
        $role = new Role(['id' => 1]);
        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->setRole($role)->shouldNotBeCalled();

        $logicTester = new LogicTester($filterTester->reveal());
        $logicTester->evaluate($logic);
    }
}
