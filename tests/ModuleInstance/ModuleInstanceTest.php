<?php


namespace BristolSU\Support\Tests\ModuleInstance;

use BristolSU\ControlDB\Contracts\Repositories\User;
use BristolSU\Support\Action\ActionInstance;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\Connection\ModuleInstanceService;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\ModuleInstance\ModuleInstanceGrouping;
use BristolSU\Support\ModuleInstance\Settings\ModuleInstanceSetting;
use BristolSU\Support\Permissions\Models\ModuleInstancePermission;
use BristolSU\Support\Progress\Handlers\Database\Models\ModuleInstanceProgress;
use BristolSU\Support\Tests\TestCase;

class ModuleInstanceTest extends TestCase
{
    /** @test */
    public function it_has_module_instance_settings()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        $settings = ModuleInstanceSetting::factory()->count(5)->create(['module_instance_id' => $moduleInstance->id]);

        $moduleInstanceSettings = $moduleInstance->moduleInstanceSettings;
        foreach ($settings as $setting) {
            $this->assertModelEquals($setting, $moduleInstanceSettings->shift());
        }
    }

    /** @test */
    public function it_has_an_activity()
    {
        $activity = Activity::factory()->create();
        $moduleInstances = ModuleInstance::factory()->count(10)->make();
        $activity->moduleInstances()->saveMany($moduleInstances);

        foreach ($moduleInstances as $moduleInstance) {
            $this->assertTrue($activity->is(
                $moduleInstance->activity
            ));
        }
    }

    /** @test */
    public function it_has_a_module_instance_permission()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        $permissions = ModuleInstancePermission::factory()->count(5)->create(['module_instance_id' => $moduleInstance->id]);

        $moduleInstancePermissions = $moduleInstance->moduleInstancePermissions;
        foreach ($permissions as $permission) {
            $this->assertModelEquals($permission, $moduleInstancePermissions->shift());
        }
    }

    /** @test */
    public function it_has_active_logic()
    {
        $logic = Logic::factory()->create();
        $moduleInstance = ModuleInstance::factory()->create([
            'active' => $logic->id
        ]);

        $this->assertModelEquals($logic, $moduleInstance->activeLogic);
    }

    /** @test */
    public function it_has_visible_logic()
    {
        $logic = Logic::factory()->create();
        $moduleInstance = ModuleInstance::factory()->create([
            'visible' => $logic->id
        ]);

        $this->assertModelEquals($logic, $moduleInstance->visibleLogic);
    }

    /** @test */
    public function it_has_mandatory_logic()
    {
        $logic = Logic::factory()->create();
        $moduleInstance = ModuleInstance::factory()->create([
            'mandatory' => $logic->id
        ]);

        $this->assertModelEquals($logic, $moduleInstance->mandatoryLogic);
    }

    /** @test */
    public function id_returns_the_id()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        $this->assertEquals($moduleInstance->id, $moduleInstance->id());
    }

    /** @test */
    public function alias_returns_the_alias()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        $this->assertEquals($moduleInstance->alias, $moduleInstance->alias());
    }

    /** @test */
    public function it_creates_a_slug_when_being_created()
    {
        $moduleInstance = ModuleInstance::factory()->make(['name' => 'A Sluggable Name']);
        $moduleInstance->slug = null;
        $moduleInstance->save();
        $this->assertEquals($moduleInstance->slug, 'a-sluggable-name');
    }

    /** @test */
    public function it_does_not_create_a_slug_if_the_slug_is_given()
    {
        $moduleInstance = ModuleInstance::factory()->make(['name' => 'A Sluggable Name']);
        $moduleInstance->slug = 'a-sluggable-name-two';
        $moduleInstance->save();
        $this->assertEquals($moduleInstance->slug, 'a-sluggable-name-two');
    }

    /** @test */
    public function it_has_actions_associated_with_it()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        $actions = ActionInstance::factory()->count(5)->create(['module_instance_id' => $moduleInstance->id]);

        $moduleInstanceActions = $moduleInstance->actionInstances;

        foreach ($actions as $action) {
            $this->assertModelEquals($action, $moduleInstanceActions->shift());
        }
    }

    /** @test */
    public function it_has_many_module_instance_services()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        $moduleInstanceServices = ModuleInstanceService::factory()->count(7)->create(['module_instance_id' => $moduleInstance->id]);

        $foundModuleInstanceServices = $moduleInstance->moduleInstanceServices;

        $this->assertEquals(7, $foundModuleInstanceServices->count());
        $this->assertContainsOnlyInstancesOf(ModuleInstanceService::class, $foundModuleInstanceServices);
        foreach ($moduleInstanceServices as $service) {
            $this->assertModelEquals($service, $foundModuleInstanceServices->shift());
        }
    }

    /** @test */
    public function setting_returns_a_setting_if_found()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        ModuleInstanceSetting::factory()->create(['key' => 'asetting', 'value' => 'thevalue', 'module_instance_id' => $moduleInstance->id]);

        $this->assertEquals('thevalue', $moduleInstance->setting('asetting'));
    }

    /** @test */
    public function setting_returns_the_given_default_if_not_found()
    {
        $moduleInstance = ModuleInstance::factory()->create();

        $this->assertEquals('thedefault', $moduleInstance->setting('setting1', 'thedefault'));
    }

    /** @test */
    public function enabled_only_returns_enabled_module_instances()
    {
        $enabledModuleInstances = ModuleInstance::factory()->count(6)->create(['enabled' => true]);
        $disabledModuleInstances = ModuleInstance::factory()->count(5)->create(['enabled' => false]);

        $foundModuleInstances = ModuleInstance::enabled()->get();

        $this->assertEquals(6, $foundModuleInstances->count());
        foreach ($enabledModuleInstances as $moduleInstance) {
            $this->assertModelEquals($moduleInstance, $foundModuleInstances->shift());
        }
    }

    /** @test */
    public function user_returns_a_user_with_the_correct_id()
    {
        $user = $this->newUser();
        $userRepository = $this->prophesize(User::class);
        $userRepository->getById($user->id())->shouldBeCalled()->willReturn($user);
        $this->instance(User::class, $userRepository->reveal());

        $moduleInstance = ModuleInstance::factory()->create(['user_id' => $user->id()]);
        $this->assertInstanceOf(\BristolSU\ControlDB\Models\User::class, $moduleInstance->user());
        $this->assertModelEquals($user, $moduleInstance->user());
    }

    /** @test */
    public function user_throws_an_exception_if_user_id_is_null()
    {
        $moduleInstance = ModuleInstance::factory()->create(['user_id' => null, 'id' => 2000]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Module Instance #2000 is not owned by a user');

        $moduleInstance->user();
    }

    /** @test */
    public function user_id_is_automatically_added_on_creation()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $logic = Logic::factory()->create();
        $moduleInstance = ModuleInstance::factory()->create(['user_id' => null]);

        $this->assertNotNull($moduleInstance->user_id);
        $this->assertEquals($user->id(), $moduleInstance->user_id);
    }

    /** @test */
    public function user_id_is_not_overridden_if_given()
    {
        $user = $this->newUser();

        $logic = Logic::factory()->create();
        $moduleInstance = ModuleInstance::factory()->create(['user_id' => $user->id()]);


        $this->assertNotNull($moduleInstance->user_id);
        $this->assertEquals($user->id(), $moduleInstance->user_id);
    }

    /** @test */
    public function revisions_are_saved()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $moduleInstance = ModuleInstance::factory()->create(['name' => 'OldName']);

        $moduleInstance->name = 'NewName';
        $moduleInstance->save();

        $this->assertEquals(1, $moduleInstance->revisionHistory->count());
        $this->assertEquals($moduleInstance->id, $moduleInstance->revisionHistory->first()->revisionable_id);
        $this->assertEquals(ModuleInstance::class, $moduleInstance->revisionHistory->first()->revisionable_type);
        $this->assertEquals('name', $moduleInstance->revisionHistory->first()->key);
        $this->assertEquals('OldName', $moduleInstance->revisionHistory->first()->old_value);
        $this->assertEquals('NewName', $moduleInstance->revisionHistory->first()->new_value);
    }

    /** @test */
    public function grouping_returns_the_group()
    {
        $moduleInstanceGrouping = ModuleInstanceGrouping::factory()->create();
        $moduleInstance = ModuleInstance::factory()->create(['grouping_id' => $moduleInstanceGrouping]);

        $groupingFromModuleInstance = $moduleInstance->grouping;

        $this->assertInstanceOf(ModuleInstanceGrouping::class, $groupingFromModuleInstance);
        $this->assertTrue($moduleInstanceGrouping->is($groupingFromModuleInstance));
    }

    /** @test */
    public function grouping_returns_null_if_module_instance_has_no_grouping()
    {
        $moduleInstance = ModuleInstance::factory()->create(['grouping_id' => null]);
        $this->assertNull($moduleInstance->grouping);
    }

    /** @test */
    public function the_order_is_accessible_through_the_module_instance_array()
    {
        $moduleInstance = ModuleInstance::factory()->create(['order' => 1]);
        $array = $moduleInstance->toArray();

        $this->assertArrayHasKey('order', $array);
        $this->assertEquals(1, $array['order']);
    }

    /** @test */
    public function moduleInstances_are_ordered_by_default()
    {
        $activity = Activity::factory()->create();
        $moduleInstance1 = ModuleInstance::factory()->create(['activity_id' => $activity->id]);
        $moduleInstance2 = ModuleInstance::factory()->create(['activity_id' => $activity->id]);
        $moduleInstance3 = ModuleInstance::factory()->create(['activity_id' => $activity->id]);

        ModuleInstance::setNewOrder([$moduleInstance2->id, $moduleInstance3->id, $moduleInstance1->id]);

        $retrievedInstances = ModuleInstance::ordered()->get();
        $this->assertModelEquals($moduleInstance2, $retrievedInstances->shift());
        $this->assertModelEquals($moduleInstance3, $retrievedInstances->shift());
        $this->assertModelEquals($moduleInstance1, $retrievedInstances->shift());
    }

    /** @test */
    public function it_has_many_progresses()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        ModuleInstanceProgress::factory()->count(5)->create();
        $progresses = ModuleInstanceProgress::factory()->count(2)->create(['module_instance_id' => $moduleInstance->id]);
        ModuleInstanceProgress::factory()->count(5)->create();

        $retrievedProgresses = $moduleInstance->moduleInstanceProgress;
        $this->assertCount(2, $retrievedProgresses);
        $this->assertModelEquals($progresses[0], $retrievedProgresses[0]);
        $this->assertModelEquals($progresses[1], $retrievedProgresses[1]);
    }

    /** @test */
    public function module_instances_have_a_module_url()
    {
        $moduleInstance = ModuleInstance::factory()->create([
            'image_url' => 'https://testimage.com/image-1'
        ]);

        $this->assertEquals('https://testimage.com/image-1', $moduleInstance->image_url);
    }
}
