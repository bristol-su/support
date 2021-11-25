<?php

namespace BristolSU\Support\Tests\Logic\DatabaseDecorator;

use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\DatabaseDecorator\LogicDatabaseDecorator;
use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;
use Prophecy\Argument;

class LogicDatabaseDecoratorTest extends TestCase
{

    /** @test */
    public function it_returns_a_matching_logic_result_if_it_exists_in_the_database(){
        $logic = Logic::factory()->create();
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();
        $baseTester = $this->prophesize(LogicTester::class);

        LogicResult::factory()->forLogic($logic)->forUser($user)->forRole($role)->passing()->create();
        $decorator = new LogicDatabaseDecorator($baseTester->reveal());
        $this->assertTrue($decorator->evaluate($logic, $user, $role->group(), $role));

        LogicResult::factory()->forLogic($logic)->forUser($user)->forGroup($group)->rejecting()->create();
        $decorator = new LogicDatabaseDecorator($baseTester->reveal());
        $this->assertFalse($decorator->evaluate($logic, $user, $group, null));
    }

    /** @test */
    public function it_calculates_the_result_from_the_base_tester_if_not_in_database(){
        $logic = Logic::factory()->create();
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();
        $baseTester = $this->prophesize(LogicTester::class);

        $baseTester->evaluate(
            Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($logic)),
            Argument::that(fn($arg) => $arg instanceof User && $arg->is($user)),
            Argument::that(fn($arg) => $arg instanceof Group && $arg->is($group)),
            Argument::that(fn($arg) => $arg instanceof Role && $arg->is($role))
        )->shouldBeCalled()->willReturn(false);
        $decorator = new LogicDatabaseDecorator($baseTester->reveal());
        $this->assertFalse($decorator->evaluate($logic, $user, $group, $role));
    }

    /** @test */
    public function it_saves_the_results_in_the_database(){
        $logic = Logic::factory()->create();
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();
        $baseTester = $this->prophesize(LogicTester::class);

        $baseTester->evaluate(
            Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($logic)),
            Argument::that(fn($arg) => $arg instanceof User && $arg->is($user)),
            Argument::that(fn($arg) => $arg instanceof Group && $arg->is($group)),
            Argument::that(fn($arg) => $arg instanceof Role && $arg->is($role))
        )->shouldBeCalled()->willReturn(false);
        $decorator = new LogicDatabaseDecorator($baseTester->reveal());
        $decorator->evaluate($logic, $user, $group, $role);
        $this->assertDatabaseHas('logic_results', [
            'logic_id' => $logic->id,
            'user_id' => $user->id(),
            'group_id' => $group->id(),
            'role_id' => $role->id(),
            'result' => false
        ]);
    }

    /** @test */
    public function it_saves_results_with_missing_models_in_the_database(){
        $logic = Logic::factory()->create();
        $user = $this->newUser();
        $group = $this->newGroup();
        $baseTester = $this->prophesize(LogicTester::class);

        $baseTester->evaluate(
            Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($logic)),
            Argument::that(fn($arg) => $arg instanceof User && $arg->is($user)),
            Argument::that(fn($arg) => $arg instanceof Group && $arg->is($group)),
            null
        )->shouldBeCalled()->willReturn(true);
        $decorator = new LogicDatabaseDecorator($baseTester->reveal());
        $decorator->evaluate($logic, $user, $group, null);
        $this->assertDatabaseHas('logic_results', [
            'logic_id' => $logic->id,
            'user_id' => $user->id(),
            'group_id' => $group->id(),
            'role_id' => null,
            'result' => true
        ]);
    }

    /** @test */
    public function it_ignores_database_results_if_the_call_is_missing_a_resource(){
        $logic = Logic::factory()->create();
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();

        $baseTester = $this->prophesize(LogicTester::class);
        $baseTester->evaluate(
            Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($logic)),
            Argument::that(fn($arg) => $arg instanceof User && $arg->is($user)),
            Argument::that(fn($arg) => $arg instanceof Group && $arg->is($group)),
            null
        )->shouldBeCalled()->willReturn(false);


        LogicResult::factory()->forLogic($logic)->forUser($user)->forGroup($group)->forRole($role)->passing()->create();
        $decorator = new LogicDatabaseDecorator($baseTester->reveal());
        $this->assertFalse($decorator->evaluate($logic, $user, $group, null));
    }

    /** @test */
    public function it_ignores_the_database_results_if_it_has_too_many_resources(){
        $logic = Logic::factory()->create();
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();

        $baseTester = $this->prophesize(LogicTester::class);
        $baseTester->evaluate(
            Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($logic)),
            Argument::that(fn($arg) => $arg instanceof User && $arg->is($user)),
            Argument::that(fn($arg) => $arg instanceof Group && $arg->is($group)),
            Argument::that(fn($arg) => $arg instanceof Role && $arg->is($role))
        )->shouldBeCalled()->willReturn(false);


        LogicResult::factory()->forLogic($logic)->forUser($user)->forGroup($group)->passing()->create();
        $decorator = new LogicDatabaseDecorator($baseTester->reveal());
        $this->assertFalse($decorator->evaluate($logic, $user, $group, $role));
    }

}
