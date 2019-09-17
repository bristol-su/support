<?php

namespace BristolSU\Support\Tests\Logic;

use BristolSU\ControlDB\Contracts\Models\Group as GroupContract;
use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\Support\Filters\Contracts\FilterRepository;
use BristolSU\Support\Filters\Contracts\Filters\GroupFilter;
use BristolSU\Support\Filters\Contracts\Filters\RoleFilter;
use BristolSU\Support\Filters\Contracts\Filters\UserFilter;
use BristolSU\Support\Filters\Contracts\FilterTester;
use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Logic\LogicTester;
use BristolSU\ControlDB\Models\User;
use Prophecy\Argument;
use BristolSU\Support\Tests\TestCase;

class LogicTesterTest extends TestCase
{
    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    private $filterTester;
    /**
     * @var User
     */
    private $fakeUser;
    /**
     * @var Group
     */
    private $fakeGroup;
    /**
     * @var Role
     */
    private $fakeRole;
    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    private $filterRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->filterTester = $this->prophesize(FilterTester::class);
        $this->filterRepository = $this->prophesize(FilterRepository::class);
        $this->instance(FilterTester::class, $this->filterTester->reveal());
        $this->instance(FilterRepository::class, $this->filterRepository->reveal());
        $this->fakeUser = $this->newUser(['id' => 1]);
        $this->fakeGroup = $this->newGroup(['id' => 2]);
        $this->fakeRole = $this->newRole(['id' => 3]);
    }

    public function createFilter($logicId, $type, $evaluated, $filterType = 'user')
    {
        $filter = factory(FilterInstance::class)->create([
            'logic_id' => $logicId,
            'logic_type' => $type,
        ]);

        $this->filterRepository->getByAlias($filter->alias)->willReturn(([
            'user' => $this->prophesize(UserFilter::class),
            'group' => $this->prophesize(GroupFilter::class),
            'role' => $this->prophesize(RoleFilter::class)
        ][$filterType])->reveal());
        
        $modelKey = 'fake' . ucfirst($filterType);
        $this->filterTester->evaluate(Argument::that(function($arg) use ($filter) {
            return $arg->id === $filter->id;
        }), $this->{$modelKey})->willReturn($evaluated);
        return $filter;
    }

    public function evaluateLogic($logic)
    {
        return (new LogicTester)->evaluate($logic, $this->fakeUser, $this->fakeGroup, $this->fakeRole);
    }
   
    /** @test */
    public function it_returns_true_when_all_fields_match_what_they_should_do()
    {
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
            $this->evaluateLogic($logic)
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
            $this->evaluateLogic($logic)
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
            $this->evaluateLogic($logic)
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
            $this->evaluateLogic($logic)
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
            $this->evaluateLogic($logic)
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
            $this->evaluateLogic($logic)
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
            $this->evaluateLogic($logic)
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
            $this->evaluateLogic($logic)
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
            $this->evaluateLogic($logic)
        );
    }

    /** @test */
    public function it_returns_true_if_no_filters_given(){
        $logic = factory(Logic::class)->create();

        $this->assertTrue(
            $this->evaluateLogic($logic)
        );
    }
}
