<?php


namespace BristolSU\Support\Tests\Module\ModuleInstance;


use BristolSU\ControlDB\Contracts\Repositories\User;
use BristolSU\Module\Tests\UploadFile\Integration\Http\Controllers\ParticipantPageControllerTest;
use BristolSU\Support\Action\ActionInstance;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\Connection\ModuleInstanceService;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\ModuleInstance\ModuleInstanceGrouping;
use BristolSU\Support\ModuleInstance\Settings\ModuleInstanceSetting;
use BristolSU\Support\Permissions\Models\ModuleInstancePermission;
use BristolSU\Support\Progress\Handlers\Database\Models\ModuleInstanceProgress;
use BristolSU\Support\Progress\Handlers\Database\Models\Progress;
use BristolSU\Support\User\Contracts\UserAuthentication;
use Illuminate\Support\Facades\DB;
use BristolSU\Support\Tests\TestCase;

class ModuleInstanceTest extends TestCase
{

    /** @test */
    public function it_has_module_instance_settings()
    {
        $moduleInstance = factory(ModuleInstance::class)->create();
        $settings = factory(ModuleInstanceSetting::class, 5)->create(['module_instance_id' => $moduleInstance->id]);

        $moduleInstanceSettings = $moduleInstance->moduleInstanceSettings;
        foreach($settings as $setting) {
            $this->assertModelEquals($setting, $moduleInstanceSettings->shift());
        }
    }

    /** @test */
    public function it_has_an_activity()
    {
        $activity = factory(Activity::class)->create();
        $moduleInstances = factory(ModuleInstance::class, 10)->make();
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
        $moduleInstance = factory(ModuleInstance::class)->create();
        $permissions = factory(ModuleInstancePermission::class, 5)->create(['module_instance_id' => $moduleInstance->id]);

        $moduleInstancePermissions = $moduleInstance->moduleInstancePermissions;
        foreach($permissions as $permission) {
            $this->assertModelEquals($permission, $moduleInstancePermissions->shift());
        }
    }

    /** @test */
    public function it_has_active_logic(){
        $logic = factory(Logic::class)->create();
        $moduleInstance = factory(ModuleInstance::class)->create([
            'active' => $logic->id
        ]);

        $this->assertModelEquals($logic, $moduleInstance->activeLogic);
    }

    /** @test */
    public function it_has_visible_logic(){
        $logic = factory(Logic::class)->create();
        $moduleInstance = factory(ModuleInstance::class)->create([
            'visible' => $logic->id
        ]);

        $this->assertModelEquals($logic, $moduleInstance->visibleLogic);
    }

    /** @test */
    public function it_has_mandatory_logic(){
        $logic = factory(Logic::class)->create();
        $moduleInstance = factory(ModuleInstance::class)->create([
            'mandatory' => $logic->id
        ]);

        $this->assertModelEquals($logic, $moduleInstance->mandatoryLogic);
    }

    /** @test */
    public function id_returns_the_id()
    {
        $moduleInstance = factory(ModuleInstance::class)->create();
        $this->assertEquals($moduleInstance->id, $moduleInstance->id());
    }


    /** @test */
    public function alias_returns_the_alias()
    {
        $moduleInstance = factory(ModuleInstance::class)->create();
        $this->assertEquals($moduleInstance->alias, $moduleInstance->alias());
    }

    /** @test */
    public function it_creates_a_slug_when_being_created()
    {
        $moduleInstance = factory(ModuleInstance::class)->make(['name' => 'A Sluggable Name']);
        $moduleInstance->slug = null;
        $moduleInstance->save();
        $this->assertEquals($moduleInstance->slug, 'a-sluggable-name');
    }

    /** @test */
    public function it_does_not_create_a_slug_if_the_slug_is_given()
    {
        $moduleInstance = factory(ModuleInstance::class)->make(['name' => 'A Sluggable Name']);
        $moduleInstance->slug = 'a-sluggable-name-two';
        $moduleInstance->save();
        $this->assertEquals($moduleInstance->slug, 'a-sluggable-name-two');
    }

    /** @test */
    public function it_has_actions_associated_with_it(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        $actions = factory(ActionInstance::class, 5)->create(['module_instance_id' => $moduleInstance->id]);

        $moduleInstanceActions = $moduleInstance->actionInstances;

        foreach($actions as $action) {
            $this->assertModelEquals($action, $moduleInstanceActions->shift());
        }
    }

    /** @test */
    public function it_has_many_module_instance_services(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        $moduleInstanceServices = factory(ModuleInstanceService::class, 7)->create(['module_instance_id' => $moduleInstance->id]);

        $foundModuleInstanceServices = $moduleInstance->moduleInstanceServices;

        $this->assertEquals(7, $foundModuleInstanceServices->count());
        $this->assertContainsOnlyInstancesOf(ModuleInstanceService::class, $foundModuleInstanceServices);
        foreach($moduleInstanceServices as $service) {
            $this->assertModelEquals($service, $foundModuleInstanceServices->shift());
        }
    }

    /** @test */
    public function setting_returns_a_setting_if_found(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        factory(ModuleInstanceSetting::class)->create(['key' => 'asetting', 'value' => 'thevalue', 'module_instance_id' => $moduleInstance->id]);

        $this->assertEquals('thevalue', $moduleInstance->setting('asetting'));
    }

    /** @test */
    public function setting_returns_the_given_default_if_not_found(){
        $moduleInstance = factory(ModuleInstance::class)->create();

        $this->assertEquals('thedefault', $moduleInstance->setting('setting1', 'thedefault'));
    }

    /** @test */
    public function enabled_only_returns_enabled_module_instances(){
        $enabledModuleInstances = factory(ModuleInstance::class, 6)->create(['enabled' => true]);
        $disabledModuleInstances = factory(ModuleInstance::class, 5)->create(['enabled' => false]);

        $foundModuleInstances = ModuleInstance::enabled()->get();

        $this->assertEquals(6, $foundModuleInstances->count());
        foreach($enabledModuleInstances as $moduleInstance) {
            $this->assertModelEquals($moduleInstance, $foundModuleInstances->shift());
        }
    }


    /** @test */
    public function user_returns_a_user_with_the_correct_id(){
        $user = $this->newUser();
        $userRepository = $this->prophesize(User::class);
        $userRepository->getById($user->id())->shouldBeCalled()->willReturn($user);
        $this->instance(User::class, $userRepository->reveal());

        $moduleInstance = factory(ModuleInstance::class)->create(['user_id' => $user->id()]);
        $this->assertInstanceOf(\BristolSU\ControlDB\Models\User::class, $moduleInstance->user());
        $this->assertModelEquals($user, $moduleInstance->user());
    }

    /** @test */
    public function user_throws_an_exception_if_user_id_is_null(){
        $moduleInstance = factory(ModuleInstance::class)->create(['user_id' => null, 'id' => 2000]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Module Instance #2000 is not owned by a user');

        $moduleInstance->user();
    }

    /** @test */
    public function user_id_is_automatically_added_on_creation(){
        $user = $this->newUser();
        $dbUser = factory(\BristolSU\Support\User\User::class)->create(['control_id' => $user->id()]);
        $authentication = $this->prophesize(UserAuthentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn($dbUser);
        $this->instance(UserAuthentication::class, $authentication->reveal());

        $logic = factory(Logic::class)->create();
        $moduleInstance = factory(ModuleInstance::class)->create(['user_id' => null]);

        $this->assertNotNull($moduleInstance->user_id);
        $this->assertEquals($user->id(), $moduleInstance->user_id);
    }

    /** @test */
    public function user_id_is_not_overridden_if_given(){
        $user = $this->newUser();

        $logic = factory(Logic::class)->create();
        $moduleInstance = factory(ModuleInstance::class)->create(['user_id' => $user->id()]);


        $this->assertNotNull($moduleInstance->user_id);
        $this->assertEquals($user->id(), $moduleInstance->user_id);
    }

    /** @test */
    public function revisions_are_saved()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $moduleInstance = factory(ModuleInstance::class)->create(['name' => 'OldName']);

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
    public function grouping_returns_the_group(){
        $moduleInstanceGrouping = factory(ModuleInstanceGrouping::class)->create();
        $moduleInstance = factory(ModuleInstance::class)->create(['grouping_id' => $moduleInstanceGrouping]);

        $groupingFromModuleInstance = $moduleInstance->grouping;

        $this->assertInstanceOf(ModuleInstanceGrouping::class, $groupingFromModuleInstance);
        $this->assertTrue($moduleInstanceGrouping->is($groupingFromModuleInstance));
    }

    /** @test */
    public function grouping_returns_null_if_module_instance_has_no_grouping(){
        $moduleInstance = factory(ModuleInstance::class)->create(['grouping_id' => null]);
        $this->assertNull($moduleInstance->grouping);
    }

    /** @test */
    public function the_order_is_accessible_through_the_module_instance_array(){
        $moduleInstance = factory(ModuleInstance::class)->create(['order' => 5]);
        $array = $moduleInstance->toArray();

        $this->assertArrayHasKey('order', $array);
        $this->assertEquals(5, $array['order']);
    }

    /** @test */
    public function it_has_many_progresses(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        factory(ModuleInstanceProgress::class, 5)->create();
        $progresses = factory(ModuleInstanceProgress::class, 2)->create(['module_instance_id' => $moduleInstance->id]);
        factory(ModuleInstanceProgress::class, 5)->create();

        $retrievedProgresses = $moduleInstance->moduleInstanceProgress;
        $this->assertCount(2, $retrievedProgresses);
        $this->assertModelEquals($progresses[0], $retrievedProgresses[0]);
        $this->assertModelEquals($progresses[1], $retrievedProgresses[1]);
    }
}
