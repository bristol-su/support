<?php


namespace BristolSU\Support\Tests\Permissions;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authentication\Contracts\UserAuthentication;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Permissions\Contracts\Models\Permission;
use BristolSU\Support\Permissions\Contracts\PermissionStore;
use BristolSU\Support\Permissions\Contracts\Tester;
use BristolSU\Support\Permissions\PermissionTester;
use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\User\User as DatabaseUser;
use Closure;
use Exception;
use Prophecy\Argument;

class PermissionTesterTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $permissionStore = $this->prophesize(PermissionStore::class);
        $permissionStore->get(Argument::any())->will(function ($args) {
            return new \BristolSU\Support\Permissions\Models\Permission($args[0], 'Permission Name', 'Permission Description');
        });
        $this->app->instance(PermissionStore::class, $permissionStore->reveal());
    }

    /** @test */
    public function getChain_throws_an_exception_if_no_testers()
    {
        $this->expectException(Exception::class);
        $permissionTester = new PermissionTester;
        $permissionTester->getChain();
    }

    /** @test */
    public function getChain_returns_a_single_tester_if_one_tester_registered()
    {
        $tester1 = $this->prophesize(Tester::class);
        $permissionTester = new PermissionTester();
        $permissionTester->register($tester1->reveal());

        $this->assertEquals($tester1->reveal(), $permissionTester->getChain());
    }

    /** @test */
    public function getChain_returns_a_single_tester_if_multiple_testers_registered()
    {
        $tester1 = $this->prophesize(Tester::class);
        $tester2 = $this->prophesize(Tester::class);
        $permissionTester = new PermissionTester();
        $permissionTester->register($tester1->reveal());
        $permissionTester->register($tester2->reveal());

        $this->assertInstanceOf(Tester::class, $permissionTester->getChain());
    }

    /** @test */
    public function getChain_sets_a_chain()
    {
        $tester1 = $this->prophesize(Tester::class);
        $tester2 = $this->prophesize(Tester::class);
        $tester3 = $this->prophesize(Tester::class);
        $tester4 = $this->prophesize(Tester::class);
        $tester1->setNext($tester2->reveal())->shouldBeCalled();
        $tester2->setNext($tester3->reveal())->shouldBeCalled();
        $tester3->setNext($tester4->reveal())->shouldBeCalled();

        $permissionTester = new PermissionTester();
        $permissionTester->register($tester1->reveal());
        $permissionTester->register($tester2->reveal());
        $permissionTester->register($tester3->reveal());
        $permissionTester->register($tester4->reveal());

        $this->assertEquals($tester1->reveal(), $permissionTester->getChain());
    }

    /** @test */
    public function evaluateFor_returns_true_if_the_tester_is_true()
    {
        $tester = (new DummyTester())->return(true);

        $permissionTester = new PermissionTester;
        $permissionTester->register($tester);

        $this->assertTrue(
            $permissionTester->evaluateFor('ability')
        );
    }

    /** @test */
    public function evaluateFor_returns_false_if_the_tester_is_false()
    {
        $tester = (new DummyTester())->return(false);

        $permissionTester = new PermissionTester;
        $permissionTester->register($tester);

        $this->assertFalse(
            $permissionTester->evaluateFor('ability')
        );
    }

    /** @test */
    public function evaluateFor_returns_false_if_null_returned_from_can()
    {
        $tester = (new DummyTester())->returnNull();

        $permissionTester = new PermissionTester;
        $permissionTester->register($tester);

        $this->assertFalse(
            $permissionTester->evaluateFor('ability')
        );
    }

    /** @test */
    public function evaluateFor_tries_each_class_until_one_returns_a_boolean()
    {
        $tester1 = (new DummyTester())->returnNull();
        $tester2 = (new DummyTester())->return(true);

        $permissionTester = new PermissionTester;
        $permissionTester->register($tester1);
        $permissionTester->register($tester2);

        $this->assertTrue(
            $permissionTester->evaluateFor('')
        );
    }

    /** @test */
    public function evaluateFor_passes_the_models_through_to_the_tester()
    {
        $user = new \BristolSU\ControlDB\Models\User(['id' => 1]);
        $group = new \BristolSU\ControlDB\Models\Group(['id' => 2]);
        $role = new \BristolSU\ControlDB\Models\Role(['id' => 3]);

        $tester = (new DummyTester())
            ->assertPermission(function ($arg) {
                $this->assertEquals('ability', $arg->getAbility());
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

        $permissionTester = new PermissionTester;
        $permissionTester->register($tester);

        $permissionTester->evaluateFor('ability', $user, $group, $role);
    }


    /** @test */
    public function evaluate_returns_true_if_the_tester_is_true()
    {
        $tester = (new DummyTester())->return(true);

        $permissionTester = new PermissionTester;
        $permissionTester->register($tester);

        $this->assertTrue(
            $permissionTester->evaluate('ability')
        );
    }

    /** @test */
    public function evaluate_returns_false_if_the_tester_is_false()
    {
        $tester = (new DummyTester())->return(false);

        $permissionTester = new PermissionTester;
        $permissionTester->register($tester);

        $this->assertFalse(
            $permissionTester->evaluate('ability')
        );
    }

    /** @test */
    public function evaluate_returns_false_if_null_returned_from_can()
    {
        $tester = (new DummyTester())->returnNull();

        $permissionTester = new PermissionTester;
        $permissionTester->register($tester);

        $this->assertFalse(
            $permissionTester->evaluate('ability')
        );
    }

    /** @test */
    public function evaluate_tries_each_class_until_one_returns_a_boolean()
    {
        $tester1 = (new DummyTester())->returnNull();
        $tester2 = (new DummyTester())->return(true);

        $permissionTester = new PermissionTester;
        $permissionTester->register($tester1);
        $permissionTester->register($tester2);

        $this->assertTrue(
            $permissionTester->evaluate('')
        );
    }

    /** @test */
    public function evaluate_takes_user_information_from_authentication_by_default()
    {

        $user = new \BristolSU\ControlDB\Models\User(['id' => 1]);
        $group = new \BristolSU\ControlDB\Models\Group(['id' => 2]);
        $role = new \BristolSU\ControlDB\Models\Role(['id' => 3]);

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn($user);
        $authentication->getGroup()->shouldBeCalled()->willReturn($group);
        $authentication->getRole()->shouldBeCalled()->willReturn($role);
        $this->app->instance(Authentication::class, $authentication->reveal());

        $tester = (new DummyTester())
            ->assertPermission(function ($arg) {
                $this->assertEquals('ability', $arg->getAbility());
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

        $permissionTester = new PermissionTester;
        $permissionTester->register($tester);

        $permissionTester->evaluate('ability');

    }

    /** @test */
    public function evaluate_finds_the_correct_user_from_the_database_user_if_no_user_in_authentication()
    {
        
        $user = new \BristolSU\ControlDB\Models\User(['id' => 1]);
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn(null);
        $authentication->getGroup()->shouldBeCalled()->willReturn(null);
        $authentication->getRole()->shouldBeCalled()->willReturn(null);
        $this->app->instance(Authentication::class, $authentication->reveal());

        $dbUserRepo = $this->prophesize(UserAuthentication::class);
        $dbUser = factory(DatabaseUser::class)->create(['control_id' => $user->id()]);
        $dbUserRepo->getUser()->shouldBeCalled()->willReturn($dbUser);
        $this->app->instance(UserAuthentication::class, $dbUserRepo->reveal());
        
        $userRepo = $this->prophesize(UserRepository::class);
        $userRepo->getById($user->id())->shouldBeCalled()->willReturn($user);
        $this->app->instance(UserRepository::class, $userRepo->reveal());
        

        $tester = (new DummyTester())
            ->assertPermission(function ($arg) {
                $this->assertEquals('ability', $arg->getAbility());
            })
            ->assertUser(function ($arg) use ($user) {
                $this->assertEquals($user, $arg);
            })
            ->assertGroup(function ($arg) {
                $this->assertNull($arg);
            })
            ->assertRole(function ($arg) {
                $this->assertNull($arg);
            });

        $permissionTester = new PermissionTester;
        $permissionTester->register($tester);

        $permissionTester->evaluate('ability');

    }

    /** @test */
    public function evaluate_passes_null_as_user_if_no_user_in_authentication_or_database()
    {
        $tester = (new DummyTester())
            ->assertPermission(function ($arg) {
                $this->assertEquals('ability', $arg->getAbility());
            })
            ->assertUser(function ($arg){
                $this->assertNull($arg);
            })
            ->assertGroup(function ($arg) {
                $this->assertNull($arg);
            })
            ->assertRole(function ($arg) {
                $this->assertNull($arg);
            });

        $permissionTester = new PermissionTester;
        $permissionTester->register($tester);

        $permissionTester->evaluate('ability');
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
