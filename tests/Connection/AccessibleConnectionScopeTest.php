<?php

namespace BristolSU\Support\Tests\Connection;

use BristolSU\Support\Connection\AccessibleConnectionScope;
use BristolSU\Support\Connection\Connection;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;

class AccessibleConnectionScopeTest extends TestCase
{
    /** @test */
    public function apply_applies_a_where_constraint_for_user_id()
    {
        $connection = factory(Connection::class)->create();
        $user = $this->newUser();
        $this->beUser($user);

        $builder = $this->prophesize(Builder::class);
        $builder->where('user_id', $user->id())->shouldBeCalled()->willReturn($builder->reveal());

        $scope = new AccessibleConnectionScope();
        $scope->apply($builder->reveal(), $connection);
    }
}
