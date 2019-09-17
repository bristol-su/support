<?php

namespace BristolSU\Support\Tests\Permissions\Contracts;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Permissions\Contracts\Models\Permission;
use BristolSU\Support\Permissions\Contracts\Tester;
use BristolSU\Support\Tests\TestCase;
use Closure;

class TesterTest extends TestCase
{

    /** @test */
    public function handle_calls_can_and_passes_the_correct_parameters()
    {
        $user = new \BristolSU\ControlDB\Models\User(['id' => 1]);
        $group = new \BristolSU\ControlDB\Models\Group(['id' => 2]);
        $role = new \BristolSU\ControlDB\Models\Role(['id' => 3]);

        $tester = (new DummyTester())
            ->assertPermission(function ($arg) {
                $this->assertEquals('ability1', $arg->getAbility());
            })
            ->assertUser(function ($arg) use ($user) {
                $this->assertEquals($user, $arg);
            })
            ->assertGroup(function ($arg) use ($group) {
                $this->assertEquals($group, $arg);
            })
            ->assertRole(function ($arg) use ($role) {
                $this->assertEquals($role, $arg);
            });

        $tester->handle(new \BristolSU\Support\Permissions\Models\Permission('ability1'), $user, $group, $role);
    }

    /** @test */
    public function handle_returns_true_if_can_is_true()
    {
        $user = new \BristolSU\ControlDB\Models\User(['id' => 1]);
        $group = new \BristolSU\ControlDB\Models\Group(['id' => 2]);
        $role = new \BristolSU\ControlDB\Models\Role(['id' => 3]);

        $tester = (new DummyTester())->return(true);

        $this->assertTrue(
            $tester->handle(new \BristolSU\Support\Permissions\Models\Permission('ability1'), $user, $group, $role)
        );
    }

    /** @test */
    public function handle_returns_false_if_can_is_false()
    {
        $user = new \BristolSU\ControlDB\Models\User(['id' => 1]);
        $group = new \BristolSU\ControlDB\Models\Group(['id' => 2]);
        $role = new \BristolSU\ControlDB\Models\Role(['id' => 3]);

        $tester = (new DummyTester())->return(false);
        $this->assertFalse(
            $tester->handle(new \BristolSU\Support\Permissions\Models\Permission('ability1'), $user, $group, $role)
        );
    }

    /** @test */
    public function handle_calls_handle_on_the_successor_if_the_result_is_null_and_a_successor_is_set()
    {
        $permission = new \BristolSU\Support\Permissions\Models\Permission('ability1');
        $user = new \BristolSU\ControlDB\Models\User(['id' => 1]);
        $group = new \BristolSU\ControlDB\Models\Group(['id' => 2]);
        $role = new \BristolSU\ControlDB\Models\Role(['id' => 3]);

        $successor = $this->prophesize(Tester::class);
        $successor->handle($permission, $user, $group, $role)->shouldBeCalled()->willReturn(true);

        $tester = (new DummyTester())->returnNull();
        $tester->setNext($successor->reveal());
        $this->assertTrue(
            $tester->handle($permission, $user, $group, $role)
        );
    }

    /** @test */
    public function handle_returns_null_if_the_result_is_null_and_no_successor_is_set()
    {
        $permission = new \BristolSU\Support\Permissions\Models\Permission('ability1');
        $user = new \BristolSU\ControlDB\Models\User(['id' => 1]);
        $group = new \BristolSU\ControlDB\Models\Group(['id' => 2]);
        $role = new \BristolSU\ControlDB\Models\Role(['id' => 3]);

        $tester = (new DummyTester())->returnNull();
        $this->assertNull(
            $tester->handle($permission, $user, $group, $role)
        );
    }

}

class DummyTester extends Tester
{

    private $return = null;
    private $user = null;
    private $group = null;
    private $role = null;
    private $permission = null;

    public function returnNull()
    {
        $this->return = null;
        return $this;
    }

    public function return($bool)
    {
        $this->return = $bool;
        return $this;
    }

    public function assertPermission(Closure $closure)
    {
        $this->permission = $closure;
        return $this;
    }

    public function assertUser(Closure $closure)
    {
        $this->user = $closure;
        return $this;
    }

    public function assertGroup(Closure $closure)
    {
        $this->group = $closure;
        return $this;
    }

    public function assertRole(Closure $closure)
    {
        $this->role = $closure;
        return $this;
    }

    /**
     * Do the given models have the ability?
     *
     * @param Permission $permission
     * @param User|null $user
     * @param Group|null $group
     * @param Role|null $role
     * @return bool|null
     */
    public function can(Permission $permission, ?User $user, ?Group $group, ?Role $role): ?bool
    {
        if ($this->permission !== null) {
            ($this->permission)($permission);
        }
        if ($this->user !== null) {
            ($this->user)($user);
        }
        if ($this->group !== null) {
            ($this->group)($group);
        }
        if ($this->role !== null) {
            ($this->role)($role);
        }
        return $this->return;
    }
}

