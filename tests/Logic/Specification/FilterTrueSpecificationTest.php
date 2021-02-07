<?php

namespace BristolSU\Support\Tests\Logic\Specification;

use BristolSU\Support\Filters\Contracts\FilterInstance;
use BristolSU\Support\Filters\Contracts\FilterTester;
use BristolSU\Support\Logic\Specification\FilterTrueSpecification;
use BristolSU\Support\Tests\TestCase;

class FilterTrueSpecificationTest extends TestCase
{
    /** @test */
    public function is_satisfied_returns_true_if_filter_true_when_a_user_filter_is_given()
    {
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();

        $filter = $this->prophesize(FilterInstance::class);
        $filter->for()->shouldBeCalled()->willReturn('user');

        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->evaluate($filter->reveal(), $user)->shouldBeCalled()->willReturn(true);
        $this->app->instance(FilterTester::class, $filterTester->reveal());

        $specification = new FilterTrueSpecification($filter->reveal(), $user, $group, $role);

        $this->assertTrue(
            $specification->isSatisfied()
        );
    }

    /** @test */
    public function is_satisfied_returns_false_if_filter_false_when_a_user_filter_is_given()
    {
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();

        $filter = $this->prophesize(FilterInstance::class);
        $filter->for()->shouldBeCalled()->willReturn('user');

        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->evaluate($filter->reveal(), $user)->shouldBeCalled()->willReturn(false);
        $this->app->instance(FilterTester::class, $filterTester->reveal());

        $specification = new FilterTrueSpecification($filter->reveal(), $user, $group, $role);

        $this->assertFalse(
            $specification->isSatisfied()
        );
    }

    /** @test */
    public function is_satisfied_returns_true_if_filter_true_when_a_group_filter_is_given()
    {
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();

        $filter = $this->prophesize(FilterInstance::class);
        $filter->for()->shouldBeCalled()->willReturn('group');

        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->evaluate($filter->reveal(), $group)->shouldBeCalled()->willReturn(true);
        $this->app->instance(FilterTester::class, $filterTester->reveal());

        $specification = new FilterTrueSpecification($filter->reveal(), $user, $group, $role);

        $this->assertTrue(
            $specification->isSatisfied()
        );
    }

    /** @test */
    public function is_satisfied_returns_false_if_filter_false_when_a_group_filter_is_given()
    {
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();

        $filter = $this->prophesize(FilterInstance::class);
        $filter->for()->shouldBeCalled()->willReturn('group');

        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->evaluate($filter->reveal(), $group)->shouldBeCalled()->willReturn(false);
        $this->app->instance(FilterTester::class, $filterTester->reveal());

        $specification = new FilterTrueSpecification($filter->reveal(), $user, $group, $role);

        $this->assertFalse(
            $specification->isSatisfied()
        );
    }

    /** @test */
    public function is_satisfied_returns_true_if_filter_true_when_a_role_filter_is_given()
    {
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();

        $filter = $this->prophesize(FilterInstance::class);
        $filter->for()->shouldBeCalled()->willReturn('role');

        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->evaluate($filter->reveal(), $role)->shouldBeCalled()->willReturn(true);
        $this->app->instance(FilterTester::class, $filterTester->reveal());

        $specification = new FilterTrueSpecification($filter->reveal(), $user, $group, $role);

        $this->assertTrue(
            $specification->isSatisfied()
        );
    }

    /** @test */
    public function is_satisfied_returns_false_if_filter_false_when_a_role_filter_is_given()
    {
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();

        $filter = $this->prophesize(FilterInstance::class);
        $filter->for()->shouldBeCalled()->willReturn('role');

        $filterTester = $this->prophesize(FilterTester::class);
        $filterTester->evaluate($filter->reveal(), $role)->shouldBeCalled()->willReturn(false);
        $this->app->instance(FilterTester::class, $filterTester->reveal());

        $specification = new FilterTrueSpecification($filter->reveal(), $user, $group, $role);

        $this->assertFalse(
            $specification->isSatisfied()
        );
    }

    /** @test */
    public function is_satisfied_returns_false_if_wrong_filter_type_given()
    {
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();

        $filter = $this->prophesize(FilterInstance::class);
        $filter->for()->shouldBeCalled()->willReturn('notatype');

        $specification = new FilterTrueSpecification($filter->reveal(), $user, $group, $role);

        $this->assertFalse(
            $specification->isSatisfied()
        );
    }
}
