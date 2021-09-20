<?php


namespace BristolSU\Support\Tests\Activity;

use BristolSU\ControlDB\Contracts\Repositories\User;
use BristolSU\Support\Action\ActionInstance;
use BristolSU\Support\Action\ActionInstanceField;
use BristolSU\Support\Action\History\ActionHistory;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\Connection\ModuleInstanceService;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\ModuleInstance\ModuleInstanceGrouping;
use BristolSU\Support\ModuleInstance\Settings\ModuleInstanceSetting;
use BristolSU\Support\Permissions\Models\ModuleInstancePermission;
use BristolSU\Support\Progress\Handlers\Database\Models\ModuleInstanceProgress;
use BristolSU\Support\Tests\TestCase;
use Carbon\Carbon;
use Exception;

class ActivityTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_many_module_instances()
    {
        $activity = Activity::factory()->create();
        $moduleInstances = ModuleInstance::factory()->count(10)->make();
        $activity->moduleInstances()->saveMany($moduleInstances);

        for ($i = 0; $i < $moduleInstances->count(); $i++) {
            $this->assertTrue(
                $moduleInstances[$i]->is(
                    $activity->moduleInstances[$i]
                )
            );
        }
    }

    /**
     * @test
     */
    public function active_retrieves_always_active_events()
    {
        $activity = Activity::factory()->create([
            'start_date' => null, 'end_date' => null
        ]);
        $retrieved = Activity::active()->get();

        $this->assertModelEquals($activity, $retrieved->first());
    }

    /**
     * @test
     */
    public function active_retrieves_an_active_activity_in_a_date_range()
    {
        $activity = Activity::factory()->create([
            'start_date' => Carbon::now()->subDays(5), 'end_date' => Carbon::now()->addDays(5)
        ]);

        $retrieved = Activity::active()->get();

        $this->assertModelEquals($activity, $retrieved->first());
    }

    /**
     * @test
     */
    public function active_does_not_retrieve_an_activity_if_the_activity_is_not_in_the_date_range()
    {
        $activity = Activity::factory()->create([
            'start_date' => Carbon::now()->subDays(5), 'end_date' => Carbon::now()->subdays(1)
        ]);
        $retrieved = Activity::active()->get();

        $this->assertCount(0, $retrieved);
    }

    /** @test */
    public function enabled_does_not_retrieve_an_activity_if_it_is_not_enabled()
    {
        $activity = Activity::factory()->create(['enabled' => false]);

        $retrieved = Activity::enabled()->get();

        $this->assertCount(0, $retrieved);
    }

    /** @test */
    public function enabled_retrieves_an_activity_if_it_is_enabled()
    {
        $activity = Activity::factory()->create(['enabled' => true]);

        $retrieved = Activity::enabled()->get();

        $this->assertCount(1, $retrieved);
        $this->assertModelEquals($activity, $retrieved->first());
    }

    /** @test */
    public function it_has_a_for_logic()
    {
        $activity = Activity::factory()->create();
        $this->assertInstanceOf(Logic::class, $activity->forLogic);
        $this->assertEquals($activity->for_logic, $activity->forLogic->id);
    }

    /** @test */
    public function it_has_an_admin_logic()
    {
        $activity = Activity::factory()->create();
        $this->assertInstanceOf(Logic::class, $activity->adminLogic);
        $this->assertEquals($activity->admin_logic, $activity->adminLogic->id);
    }

    /** @test */
    public function it_creates_a_slug_when_being_created()
    {
        $activity = Activity::factory()->make(['name' => 'A Sluggable Name']);
        $activity->slug = null;
        $activity->save();
        $this->assertEquals($activity->slug, 'a-sluggable-name');
    }

    /** @test */
    public function it_does_not_create_a_slug_if_the_slug_is_given()
    {
        $activity = Activity::factory()->make(['name' => 'A Sluggable Name']);
        $activity->slug = 'a-sluggable-name-two';
        $activity->save();
        $this->assertEquals($activity->slug, 'a-sluggable-name-two');
    }

    /** @test */
    public function is_completable_returns_true_if_the_activity_is_a_multicompletable_activity()
    {
        $activity = Activity::factory()->create(['type' => 'multi-completable']);
        $this->assertTrue($activity->isCompletable());
    }

    /** @test */
    public function is_completable_returns_true_if_the_activity_is_a_completable_activity()
    {
        $activity = Activity::factory()->create(['type' => 'completable']);
        $this->assertTrue($activity->isCompletable());
    }

    /** @test */
    public function is_completable_returns_false_if_the_activity_is_an_open_activity()
    {
        $activity = Activity::factory()->create(['type' => 'open']);
        $this->assertFalse($activity->isCompletable());
    }

    /** @test */
    public function it_has_many_activity_instances()
    {
        $activity = Activity::factory()->create();
        $activityInstance1 = ActivityInstance::factory()->create(['activity_id' => $activity->id]);
        $activityInstance2 = ActivityInstance::factory()->create(['activity_id' => $activity->id]);

        $activityInstances = $activity->activityInstances;

        $this->assertModelEquals($activityInstance1, $activityInstances->offsetGet(0));
        $this->assertModelEquals($activityInstance2, $activityInstances->offsetGet(1));
    }

    /** @test */
    public function user_returns_a_user_with_the_correct_id()
    {
        $user = $this->newUser();
        $userRepository = $this->prophesize(User::class);
        $userRepository->getById($user->id())->shouldBeCalled()->willReturn($user);
        $this->instance(User::class, $userRepository->reveal());

        $activity = Activity::factory()->create(['user_id' => $user->id()]);
        $this->assertInstanceOf(\BristolSU\ControlDB\Models\User::class, $activity->user());
        $this->assertModelEquals($user, $activity->user());
    }

    /** @test */
    public function user_throws_an_exception_if_user_id_is_null()
    {
        $activity = Activity::factory()->create(['user_id' => null, 'id' => 2000]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Activity #2000 is not owned by a user');

        $activity->user();
    }

    /** @test */
    public function user_id_is_automatically_added_on_creation()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $logic = Logic::factory()->create();
        $activity = Activity::create([
            'name' => 'name1',
            'description' => 'desc1',
            'activity_for' => 'user',
            'for_logic' => $logic->id,
            'admin_logic' => $logic->id,
            'type' => 'open',
            'start_date' => null,
            'end_date' => null,
            'enabled' => true
        ]);

        $this->assertNotNull($activity->user_id);
        $this->assertEquals($user->id(), $activity->user_id);
    }

    /** @test */
    public function user_id_is_not_overridden_if_given()
    {
        $user = $this->newUser();

        $logic = Logic::factory()->create();
        $activity = Activity::create([
            'name' => 'name1',
            'description' => 'desc1',
            'activity_for' => 'user',
            'for_logic' => $logic->id,
            'admin_logic' => $logic->id,
            'type' => 'open',
            'start_date' => null,
            'end_date' => null,
            'enabled' => true,
            'user_id' => $user->id()
        ]);

        $this->assertNotNull($activity->user_id);
        $this->assertEquals($user->id(), $activity->user_id);
    }

    /** @test */
    public function revisions_are_saved()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $activity = Activity::factory()->create(['name' => 'OldName']);

        $activity->name = 'NewName';
        $activity->save();

        $this->assertEquals(1, $activity->revisionHistory->count());
        $this->assertEquals($activity->id, $activity->revisionHistory->first()->revisionable_id);
        $this->assertEquals(Activity::class, $activity->revisionHistory->first()->revisionable_type);
        $this->assertEquals('name', $activity->revisionHistory->first()->key);
        $this->assertEquals('OldName', $activity->revisionHistory->first()->old_value);
        $this->assertEquals('NewName', $activity->revisionHistory->first()->new_value);
    }

    /** @test */
    public function activities_have_a_module_url()
    {
        $activity = Activity::factory()->create([
            'image_url' => 'https://testimage.com/image-1'
        ]);

        $this->assertEquals('https://testimage.com/image-1', $activity->image_url);
    }

    /** @test */
    public function an_activity_can_be_soft_deleted()
    {
        $activity = Activity::factory()->create();

        $activity->delete();

        $this->assertNotEmpty($activity->deleted_at);
    }

    public function setupModuleInstance($moduleId, $activityId)
    {
        $actionInstance = ActionInstance::factory(['module_instance_id' => $moduleId])->create()->id;

        return [
            'moduleInstanceSetting' => ModuleInstanceSetting::factory(['module_instance_id' => $moduleId])->create()->id,
            'moduleInstancePermission' => ModuleInstancePermission::factory(['module_instance_id' => $moduleId])->create()->id,
            'moduleInstanceProgress' => ModuleInstanceProgress::factory(['module_instance_id' => $moduleId])->create()->id,
            'moduleInstanceService' => ModuleInstanceService::factory(['module_instance_id' => $moduleId])->create()->id,
            'actionHistory' => ActionHistory::factory(['action_instance_id' => $actionInstance])->create()->id,
            'actionInstance' => $actionInstance,
            'actionInstanceField' => ActionInstanceField::factory(['action_instance_id' => $actionInstance])->create()->id
        ];
    }

    /** @test */
    public function when_an_activity_is_deleted_it_cascades_to_instances_and_modules_and_actions()
    {
        $activity = Activity::factory()->create();
        $activityInstance = ActivityInstance::factory(['activity_id' => $activity->id])->create();
        $moduleInstance = ModuleInstance::factory()->activityId($activity->id)->create();
        $setup = $this->setupModuleInstance($moduleInstance->id, $activity->id);

        Activity::find($activity->id)->delete();

        $deletedActivity = Activity::withTrashed()->find($activity->id);

        // Activity:
        $this->assertNotEmpty($deletedActivity->deleted_at);

        // Activity Instances:
        $this->assertEmpty($deletedActivity->activityInstances()->get());
        $this->assertCount(1, $deletedActivity->activityInstances()->withTrashed()->get());

        // Module Instances:
        $this->assertEmpty($deletedActivity->moduleInstances()->get());
        $this->assertCount(1, $deletedActivity->moduleInstances()->withTrashed()->get());

        // Module Instance Groups:
        $this->assertEmpty(ModuleInstanceGrouping::where('activity_id', '=', $activity->id)->get());
        $this->assertCount(1, ModuleInstanceGrouping::where('activity_id', '=', $activity->id)->withTrashed()->get());

        // Module Instance Settings:
        $this->assertEmpty(ModuleInstanceSetting::where('module_instance_id', '=', $moduleInstance->id)->get());
        $this->assertCount(1, ModuleInstanceSetting::where('module_instance_id', '=', $moduleInstance->id)->withTrashed()->get());

        // Module Instance Permissions:
        $this->assertEmpty(ModuleInstancePermission::where('module_instance_id', '=', $moduleInstance->id)->get());
        $this->assertCount(1, ModuleInstancePermission::where('module_instance_id', '=', $moduleInstance->id)->withTrashed()->get());

        // Module Instance Progress:
        $this->assertEmpty(ModuleInstanceProgress::where('module_instance_id', '=', $moduleInstance->id)->get());
        $this->assertCount(1, ModuleInstanceProgress::where('module_instance_id', '=', $moduleInstance->id)->withTrashed()->get());

        // Module Instance Services
        $this->assertEmpty(ModuleInstanceService::where('module_instance_id', '=', $moduleInstance->id)->get());
        $this->assertCount(1, ModuleInstanceService::where('module_instance_id', '=', $moduleInstance->id)->withTrashed()->get());

        // Module Instance Actions:
        $this->assertEmpty(ActionInstance::where('module_instance_id', '=', $moduleInstance->id)->get());
        $this->assertCount(1, ActionInstance::where('module_instance_id', '=', $moduleInstance->id)->withTrashed()->get());

        // Action Histories:
        $this->assertEmpty(ActionHistory::where('action_instance_id', '=', $setup['actionInstance'])->get());
        $this->assertCount(1, ActionHistory::where('action_instance_id', '=', $setup['actionInstance'])->withTrashed()->get());

        // Action Instance Fields:
        $this->assertEmpty(ActionInstanceField::where('action_instance_id', '=', $setup['actionInstance'])->get());
        $this->assertCount(1, ActionInstanceField::where('action_instance_id', '=', $setup['actionInstance'])->withTrashed()->get());
    }
}
