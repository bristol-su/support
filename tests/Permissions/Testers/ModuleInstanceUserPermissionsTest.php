<?php


namespace BristolSU\Support\Tests\Permissions\Testers;


use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Contracts\Testers\Tester;
use BristolSU\Support\Permissions\Models\ModuleInstancePermissions;
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

        $tester = new ModuleInstanceUserPermissions($app->reveal(), resolve(LogicTester::class));

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

        $tester = new ModuleInstanceUserPermissions($app->reveal(), resolve(LogicTester::class));
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

        $tester = new ModuleInstanceUserPermissions($app->reveal(), resolve(LogicTester::class));

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

        $tester = new ModuleInstanceUserPermissions($app->reveal(), resolve(LogicTester::class));

        $this->assertTrue(
            $tester->can('permission1')
        );
    }

}
