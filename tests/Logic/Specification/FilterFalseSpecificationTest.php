<?php

namespace BristolSU\Support\Tests\Logic\Specification;

use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Filters\Contracts\FilterInstance;
use BristolSU\Support\Filters\Contracts\FilterTester;
use BristolSU\Support\Logic\Specification\FilterFalseSpecification;
use BristolSU\Support\Tests\TestCase;

class FilterFalseSpecificationTest extends TestCase
{

    /** @test */
    public function isSatisfied_returns_true_if_filter_false_when_a_user_filter_is_given(){
        $user = new User(['id' => 1]);
        $group = new Group(['id' => 2]);
        $role = new Role(['id' => 3]);
        
        $filter = $this->prophesize(FilterInstance::class);
        $filter->for()->shouldBeCalled()->willReturn('user');

        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->evaluate($filter->reveal(), $user)->shouldBeCalled()->willReturn(false);
        $this->app->instance(FilterTester::class, $filterTester->reveal());
        
        $specification = new FilterFalseSpecification($filter->reveal(), $user, $group, $role);
        
        $this->assertTrue(
            $specification->isSatisfied()
        );
    }

    /** @test */
    public function isSatisfied_returns_false_if_filter_true_when_a_user_filter_is_given(){
        $user = new User(['id' => 1]);
        $group = new Group(['id' => 2]);
        $role = new Role(['id' => 3]);

        $filter = $this->prophesize(FilterInstance::class);
        $filter->for()->shouldBeCalled()->willReturn('user');

        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->evaluate($filter->reveal(), $user)->shouldBeCalled()->willReturn(true);
        $this->app->instance(FilterTester::class, $filterTester->reveal());

        $specification = new FilterFalseSpecification($filter->reveal(), $user, $group, $role);

        $this->assertFalse(
            $specification->isSatisfied()
        );
    }

    /** @test */
    public function isSatisfied_returns_true_if_filter_false_when_a_group_filter_is_given(){
        $user = new User(['id' => 1]);
        $group = new Group(['id' => 2]);
        $role = new Role(['id' => 3]);

        $filter = $this->prophesize(FilterInstance::class);
        $filter->for()->shouldBeCalled()->willReturn('group');

        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->evaluate($filter->reveal(), $group)->shouldBeCalled()->willReturn(false);
        $this->app->instance(FilterTester::class, $filterTester->reveal());

        $specification = new FilterFalseSpecification($filter->reveal(), $user, $group, $role);

        $this->assertTrue(
            $specification->isSatisfied()
        );
    }

    /** @test */
    public function isSatisfied_returns_false_if_filter_true_when_a_group_filter_is_given(){
        $user = new User(['id' => 1]);
        $group = new Group(['id' => 2]);
        $role = new Role(['id' => 3]);

        $filter = $this->prophesize(FilterInstance::class);
        $filter->for()->shouldBeCalled()->willReturn('group');

        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->evaluate($filter->reveal(), $group)->shouldBeCalled()->willReturn(true);
        $this->app->instance(FilterTester::class, $filterTester->reveal());

        $specification = new FilterFalseSpecification($filter->reveal(), $user, $group, $role);

        $this->assertFalse(
            $specification->isSatisfied()
        );
    }

    /** @test */
    public function isSatisfied_returns_true_if_filter_false_when_a_role_filter_is_given(){
        $user = new User(['id' => 1]);
        $group = new Group(['id' => 2]);
        $role = new Role(['id' => 3]);

        $filter = $this->prophesize(FilterInstance::class);
        $filter->for()->shouldBeCalled()->willReturn('role');

        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->evaluate($filter->reveal(), $role)->shouldBeCalled()->willReturn(false);
        $this->app->instance(FilterTester::class, $filterTester->reveal());

        $specification = new FilterFalseSpecification($filter->reveal(), $user, $group, $role);

        $this->assertTrue(
            $specification->isSatisfied()
        );
    }

    /** @test */
    public function isSatisfied_returns_false_if_filter_true_when_a_role_filter_is_given(){
        $user = new User(['id' => 1]);
        $group = new Group(['id' => 2]);
        $role = new Role(['id' => 3]);

        $filter = $this->prophesize(FilterInstance::class);
        $filter->for()->shouldBeCalled()->willReturn('role');

        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->evaluate($filter->reveal(), $role)->shouldBeCalled()->willReturn(true);
        $this->app->instance(FilterTester::class, $filterTester->reveal());

        $specification = new FilterFalseSpecification($filter->reveal(), $user, $group, $role);

        $this->assertFalse(
            $specification->isSatisfied()
        );
    }
    
    /** @test */
    public function isSatisfied_returns_false_if_wrong_filter_type_given(){
        $user = new User(['id' => 1]);
        $group = new Group(['id' => 2]);
        $role = new Role(['id' => 3]);

        $filter = $this->prophesize(FilterInstance::class);
        $filter->for()->shouldBeCalled()->willReturn('notatype');

        $specification = new FilterFalseSpecification($filter->reveal(), $user, $group, $role);

        $this->assertFalse(
            $specification->isSatisfied()
        );
    }

}
