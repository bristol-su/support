<?php

namespace BristolSU\Support\Tests\Testing;

use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Testing\FakesLogicTesters;
use BristolSU\Support\Tests\TestCase;

class FakesLogicTestersTest extends TestCase
{
    use FakesLogicTesters;

    /** @test */
    public function new_logic_tester_returns_a_new_logic_tester_if_not_called_before()
    {
        $logicTester = $this->logicTester();

        $this->assertInstanceOf(LogicTester::class, $logicTester);
    }

    /** @test */
    public function new_logic_tester_returns_the_same_logic_tester_if_called_before()
    {
        $this->assertSame($this->logicTester(), $this->logicTester());
    }

    /** @test */
    public function logic_tester_can_be_bound_and_used_in_the_system()
    {
        $logic = Logic::factory()->create();
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $role = Role::factory()->create();

        $this->logicTester()->forLogic($logic)
            ->fail([$user, $group, $role]);
        $this->logicTester()->bind();

        $this->assertFalse(
            \BristolSU\Support\Logic\Facade\LogicTester::evaluate($logic, $user, $group, $role)
        );
    }
}
