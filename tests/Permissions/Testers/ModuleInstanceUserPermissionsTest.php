<?php


namespace BristolSU\Support\Tests\Permissions\Testers;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Control\Models\User;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Contracts\Testers\Tester;
use BristolSU\Support\Permissions\Models\ModuleInstancePermissions;
use BristolSU\Support\Permissions\Testers\ModuleInstanceAdminPermissions;
use BristolSU\Support\Permissions\Testers\ModuleInstanceUserPermissions;
use Illuminate\Contracts\Container\Container;
use BristolSU\Support\Tests\TestCase;

class ModuleInstanceUserPermissionsTest extends TestCase
{

    /** @test */
    public function can_calls_successor_if_module_instance_does_not_exist()
    {
        $app = $this->prophesize(Container::class);
        $app->make(ModuleInstance::class)->shouldBeCalled()->willReturn(new ModuleInstance);

        $tester = new ModuleInstanceUserPermissions($app->reveal(), resolve(LogicTester::class), $this->prophesize(Authentication::class)->reveal());

        $fakeTester = $this->prophesize(Tester::class);
        $fakeTester->can('xyz')->shouldBeCalled();
        $tester->setNext($fakeTester->reveal());

        $tester->can('xyz');

    }
    /** @test */
    public function can_calls_successor_if_ability_not_in_module_instance_participant_permissions(){
        $logic = factory(Logic::class)->create();
        $miPermissions = factory(ModuleInstancePermissions::class)->create([
            'participant_permissions' => ['permission1' => $logic->id]
        ]);
        $moduleInstance = factory(ModuleInstance::class)->create(['module_instance_permissions_id' => $miPermissions->id]);

        $app = $this->prophesize(Container::class);
        $app->make(ModuleInstance::class)->shouldBeCalled()->willReturn($moduleInstance);

        $fakeTester = $this->prophesize(Tester::class);
        $fakeTester->can('notpermission1')->shouldBeCalled();

        $tester = new ModuleInstanceUserPermissions($app->reveal(), resolve(LogicTester::class), $this->prophesize(Authentication::class)->reveal());
        $tester->setNext($fakeTester->reveal());

        $tester->can('notpermission1');
    }

    /** @test */
    public function can_returns_false_if_the_logic_is_not_found_by_id(){
        $miPermissions = factory(ModuleInstancePermissions::class)->create([
            'participant_permissions' => ['permission1' => 100]
        ]);
        $moduleInstance = factory(ModuleInstance::class)->create(['module_instance_permissions_id' => $miPermissions->id]);

        $app = $this->prophesize(Container::class);
        $app->make(ModuleInstance::class)->shouldBeCalled()->willReturn($moduleInstance);

        $tester = new ModuleInstanceUserPermissions($app->reveal(), resolve(LogicTester::class), $this->prophesize(Authentication::class)->reveal());

        $this->assertFalse(
            $tester->can('permission1')
        );
    }

    /** @test */
    public function can_returns_the_logic_evaluation_if_logic_in_module_instance(){
        $logic = factory(Logic::class)->create();
        $miPermissions = factory(ModuleInstancePermissions::class)->create([
            'participant_permissions' => ['permission1' => $logic->id]
        ]);
        $moduleInstance = factory(ModuleInstance::class)->create(['module_instance_permissions_id' => $miPermissions->id]);

        $app = $this->prophesize(Container::class);
        $app->make(ModuleInstance::class)->shouldBeCalled()->willReturn($moduleInstance);

        $this->createLogicTester([$logic]);

        $tester = new ModuleInstanceUserPermissions($app->reveal(), resolve(LogicTester::class), $this->prophesize(Authentication::class)->reveal());

        $this->assertTrue(
            $tester->can('permission1')
        );
    }

    /** @test */
    public function can_passes_the_authentication_attributes_to_the_logic_tester(){
        $logic = factory(Logic::class)->create();
        $miPermissions = factory(ModuleInstancePermissions::class)->create([
            'participant_permissions' => ['permission1' => $logic->id]
        ]);
        $moduleInstance = factory(ModuleInstance::class)->create(['module_instance_permissions_id' => $miPermissions->id]);

        $app = $this->prophesize(Container::class);
        $app->make(ModuleInstance::class)->shouldBeCalled()->willReturn($moduleInstance);

        $user = new User(['id' => 1]);
        $group = new Group(['id' => 1]);
        $role = new Role(['id' => 2]);
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn($user);
        $authentication->getGroup()->shouldBeCalled()->willReturn($group);
        $authentication->getRole()->shouldBeCalled()->willReturn($role);

        $this->createLogicTester([$logic], [], $user, $group, $role);

        $tester = new ModuleInstanceUserPermissions($app->reveal(), resolve(LogicTester::class), $authentication->reveal());

        $this->assertTrue(
            $tester->can('permission1')
        );
    }

}
