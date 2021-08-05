<?php

namespace BristolSU\Support\Tests\Testing;

use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Testing\CreatesModuleEnvironment;
use BristolSU\Support\Tests\TestCase;

class CreatesModuleEnvironmentTest extends TestCase
{
    use CreatesModuleEnvironment;

    /** @test */
    public function create_module_environment_creates_and_binds_an_activity_with_the_correct_parameters()
    {
        $this->createModuleEnvironment('alias1');

        $this->assertInstanceOf(Activity::class, $this->app->make(Activity::class));
    }

    /** @test */
    public function create_module_environment_and_binds_a_given_activity()
    {
        $activity = Activity::factory()->create();
        $this->setActivity($activity);

        $this->createModuleEnvironment('alias1');

        $this->assertInstanceOf(Activity::class, $this->app->make(Activity::class));
        $this->assertModelEquals($activity, $this->app->make(Activity::class));
    }

    /** @test */
    public function create_module_environment_creates_and_logs_in_an_activity_instance_with_the_correct_parameters_for_a_user_activity()
    {
        $activity = Activity::factory()->create(['activity_for' => 'user']);
        $this->setActivity($activity);
        $this->setModuleIsFor('user');
        $this->createModuleEnvironment('alias1');

        $activityInstance = $this->app->make(ActivityInstanceResolver::class)->getActivityInstance();
        $this->assertInstanceOf(ActivityInstance::class, $activityInstance);
        $this->assertEquals($activity->id, $activityInstance->activity_id);
        $this->assertEquals($this->getControlUser()->id(), $activityInstance->resource_id);
        $this->assertEquals('user', $activityInstance->resource_type);
    }

    /** @test */
    public function create_module_environment_creates_and_logs_in_an_activity_instance_with_the_correct_parameters_for_a_group_activity()
    {
        $activity = Activity::factory()->create(['activity_for' => 'group']);
        $this->setActivity($activity);
        $this->setModuleIsFor('group');
        $this->createModuleEnvironment('alias1');

        $activityInstance = $this->app->make(ActivityInstanceResolver::class)->getActivityInstance();
        $this->assertInstanceOf(ActivityInstance::class, $activityInstance);
        $this->assertEquals($activity->id, $activityInstance->activity_id);
        $this->assertEquals($this->getControlGroup()->id(), $activityInstance->resource_id);
        $this->assertEquals('group', $activityInstance->resource_type);
    }

    /** @test */
    public function create_module_environment_creates_and_logs_in_an_activity_instance_with_the_correct_parameters_for_a_role_activity()
    {
        $activity = Activity::factory()->create(['activity_for' => 'role']);
        $this->setActivity($activity);
        $this->setModuleIsFor('role');
        $this->createModuleEnvironment('alias1');

        $activityInstance = $this->app->make(ActivityInstanceResolver::class)->getActivityInstance();
        $this->assertInstanceOf(ActivityInstance::class, $activityInstance);
        $this->assertEquals($activity->id, $activityInstance->activity_id);
        $this->assertEquals($this->getControlRole()->id(), $activityInstance->resource_id);
        $this->assertEquals('role', $activityInstance->resource_type);
    }

    /** @test */
    public function create_module_environment_logs_in_a_given_activity_instance()
    {
        $activity = Activity::factory()->create(['activity_for' => 'user']);
        $user = User::factory()->create();
        $activityInstance = ActivityInstance::factory()->create(
            [
                'resource_type' => 'user', 'resource_id'=> $user->id, 'activity_id' => $activity->id]
        );

        $this->setActivity($activity);
        $this->setActivityInstance($activityInstance);
        $this->setModuleIsFor('user');
        $this->createModuleEnvironment('alias1');

        $resovledActivityInstance = $this->app->make(ActivityInstanceResolver::class)->getActivityInstance();
        $this->assertInstanceOf(ActivityInstance::class, $resovledActivityInstance);
        $this->assertModelEquals($activityInstance, $resovledActivityInstance);
    }

    /** @test */
    public function create_module_environment_creates_and_binds_a_module_instance_with_the_correct_parameters()
    {
        $this->createModuleEnvironment('alias1');

        $moduleInstance = $this->app->make(ModuleInstance::class);
        $this->assertInstanceOf(ModuleInstance::class, $moduleInstance);
        $this->assertEquals($this->getActivity()->id, $moduleInstance->activity_id);
        $this->assertEquals('alias1', $moduleInstance->alias);
    }

    /** @test */
    public function create_module_environment_binds_a_given_module_instance()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        $this->setModuleInstance($moduleInstance);
        $this->createModuleEnvironment('alias1');

        $resolvedModuleInstance = $this->app->make(ModuleInstance::class);
        $this->assertInstanceOf(ModuleInstance::class, $resolvedModuleInstance);
        $this->assertModelEquals($moduleInstance, $resolvedModuleInstance);
    }

    /** @test */
    public function create_module_environment_creates_and_logs_in_a_control_user_only_when_for_is_user()
    {
        $this->setModuleIsFor('user');
        $this->createModuleEnvironment('alias1');

        $this->assertInstanceOf(User::class, $this->app->make(Authentication::class)->getUser());
        $this->assertNull($this->app->make(Authentication::class)->getGroup());
        $this->assertNull($this->app->make(Authentication::class)->getRole());
    }

    /** @test */
    public function create_module_environment_logs_in_a_given_control_user_when_for_is_user()
    {
        $user = User::factory()->create();
        $this->setControlUser($user);
        $this->setModuleIsFor('user');
        $this->createModuleEnvironment('alias1');

        $this->assertInstanceOf(User::class, $this->app->make(Authentication::class)->getUser());
        $this->assertModelEquals($user, $this->app->make(Authentication::class)->getUser());
    }

    /** @test */
    public function create_module_environment_creates_and_logs_in_a_control_user_and_control_group_when_for_is_group()
    {
        $this->setModuleIsFor('group');
        $this->createModuleEnvironment('alias1');

        $this->assertInstanceOf(User::class, $this->app->make(Authentication::class)->getUser());
        $this->assertInstanceOf(Group::class, $this->app->make(Authentication::class)->getGroup());
        $this->assertNull($this->app->make(Authentication::class)->getRole());
    }

    /** @test */
    public function create_module_environment_logs_in_a_given_control_group_when_for_is_group()
    {
        $group = Group::factory()->create();
        $this->setControlGroup($group);
        $this->setModuleIsFor('group');
        $this->createModuleEnvironment('alias1');

        $this->assertInstanceOf(Group::class, $this->app->make(Authentication::class)->getGroup());
        $this->assertModelEquals($group, $this->app->make(Authentication::class)->getGroup());
    }

    /** @test */
    public function create_module_environment_creates_and_logs_in_a_control_user_and_control_group_and_control_role_when_for_is_role()
    {
        $this->setModuleIsFor('role');
        $this->createModuleEnvironment('alias1');

        $this->assertInstanceOf(User::class, $this->app->make(Authentication::class)->getUser());
        $this->assertInstanceOf(Group::class, $this->app->make(Authentication::class)->getGroup());
        $this->assertInstanceOf(Role::class, $this->app->make(Authentication::class)->getRole());
    }

    /** @test */
    public function create_module_environment_logs_in_a_given_control_role_when_for_is_role()
    {
        $role = Role::factory()->create();
        $this->setControlRole($role);
        $this->setModuleIsFor('role');
        $this->createModuleEnvironment('alias1');

        $this->assertInstanceOf(Role::class, $this->app->make(Authentication::class)->getRole());
        $this->assertModelEquals($role, $this->app->make(Authentication::class)->getRole());
    }

    /** @test */
    public function admin_url_returns_the_correct_admin_url()
    {
        $activity = Activity::factory()->create(['slug' => 'activity-slug']);
        $moduleInstance = ModuleInstance::factory()->create(['slug' => 'module-instance-slug']);
        $this->setActivity($activity);
        $this->setModuleInstance($moduleInstance);
        $this->createModuleEnvironment('module-alias');

        $this->assertEquals('/a/activity-slug/module-instance-slug/module-alias/', $this->adminUrl());
    }

    /** @test */
    public function admin_url_appends_path_starting_with_a_forward_slash_to_the_end_of_the_url()
    {
        $activity = Activity::factory()->create(['slug' => 'activity-slug']);
        $moduleInstance = ModuleInstance::factory()->create(['slug' => 'module-instance-slug']);
        $this->setActivity($activity);
        $this->setModuleInstance($moduleInstance);
        $this->createModuleEnvironment('module-alias');

        $this->assertEquals('/a/activity-slug/module-instance-slug/module-alias/test-page/1', $this->adminUrl('/test-page/1'));
    }

    /** @test */
    public function admin_url_appends_path_starting_without_a_forward_slash_to_the_end_of_the_url()
    {
        $activity = Activity::factory()->create(['slug' => 'activity-slug']);
        $moduleInstance = ModuleInstance::factory()->create(['slug' => 'module-instance-slug']);
        $this->setActivity($activity);
        $this->setModuleInstance($moduleInstance);
        $this->createModuleEnvironment('module-alias');

        $this->assertEquals('/a/activity-slug/module-instance-slug/module-alias/test-page/1', $this->adminUrl('test-page/1'));
    }

    /** @test */
    public function user_url_returns_the_correct_user_url()
    {
        $activity = Activity::factory()->create(['slug' => 'activity-slug']);
        $moduleInstance = ModuleInstance::factory()->create(['slug' => 'module-instance-slug']);
        $this->setActivity($activity);
        $this->setModuleInstance($moduleInstance);
        $this->createModuleEnvironment('module-alias');

        $this->assertEquals('/p/activity-slug/module-instance-slug/module-alias/', $this->userUrl());
    }

    /** @test */
    public function user_url_appends_path_starting_with_a_forward_slash_to_the_end_of_the_url()
    {
        $activity = Activity::factory()->create(['slug' => 'activity-slug']);
        $moduleInstance = ModuleInstance::factory()->create(['slug' => 'module-instance-slug']);
        $this->setActivity($activity);
        $this->setModuleInstance($moduleInstance);
        $this->createModuleEnvironment('module-alias');

        $this->assertEquals('/p/activity-slug/module-instance-slug/module-alias/test-page/1', $this->userUrl('/test-page/1'));
    }

    /** @test */
    public function user_url_appends_path_starting_without_a_forward_slash_to_the_end_of_the_url()
    {
        $activity = Activity::factory()->create(['slug' => 'activity-slug']);
        $moduleInstance = ModuleInstance::factory()->create(['slug' => 'module-instance-slug']);
        $this->setActivity($activity);
        $this->setModuleInstance($moduleInstance);
        $this->createModuleEnvironment('module-alias');

        $this->assertEquals('/p/activity-slug/module-instance-slug/module-alias/test-page/1', $this->userUrl('test-page/1'));
    }

    /** @test */
    public function admin_api_url_returns_the_correct_admin_api_url()
    {
        $activity = Activity::factory()->create(['slug' => 'activity-slug']);
        $moduleInstance = ModuleInstance::factory()->create(['slug' => 'module-instance-slug']);
        $this->setActivity($activity);
        $this->setModuleInstance($moduleInstance);
        $this->createModuleEnvironment('module-alias');

        $this->assertEquals('/api/a/activity-slug/module-instance-slug/module-alias/', $this->adminApiUrl());
    }

    /** @test */
    public function admin_api_url_appends_path_starting_with_a_forward_slash_to_the_end_of_the_url()
    {
        $activity = Activity::factory()->create(['slug' => 'activity-slug']);
        $moduleInstance = ModuleInstance::factory()->create(['slug' => 'module-instance-slug']);
        $this->setActivity($activity);
        $this->setModuleInstance($moduleInstance);
        $this->createModuleEnvironment('module-alias');

        $this->assertEquals('/api/a/activity-slug/module-instance-slug/module-alias/test-page/1', $this->adminApiUrl('/test-page/1'));
    }

    /** @test */
    public function admin_api_url_appends_path_starting_without_a_forward_slash_to_the_end_of_the_url()
    {
        $activity = Activity::factory()->create(['slug' => 'activity-slug']);
        $moduleInstance = ModuleInstance::factory()->create(['slug' => 'module-instance-slug']);
        $this->setActivity($activity);
        $this->setModuleInstance($moduleInstance);
        $this->createModuleEnvironment('module-alias');

        $this->assertEquals('/api/a/activity-slug/module-instance-slug/module-alias/test-page/1', $this->adminApiUrl('test-page/1'));
    }

    /** @test */
    public function user_api_url_returns_the_correct_user_api_url()
    {
        $activity = Activity::factory()->create(['slug' => 'activity-slug']);
        $moduleInstance = ModuleInstance::factory()->create(['slug' => 'module-instance-slug']);
        $this->setActivity($activity);
        $this->setModuleInstance($moduleInstance);
        $this->createModuleEnvironment('module-alias');

        $this->assertEquals('/api/p/activity-slug/module-instance-slug/module-alias/', $this->userApiUrl());
    }

    /** @test */
    public function user_api_url_appends_path_starting_with_a_forward_slash_to_the_end_of_the_url()
    {
        $activity = Activity::factory()->create(['slug' => 'activity-slug']);
        $moduleInstance = ModuleInstance::factory()->create(['slug' => 'module-instance-slug']);
        $this->setActivity($activity);
        $this->setModuleInstance($moduleInstance);
        $this->createModuleEnvironment('module-alias');

        $this->assertEquals('/api/p/activity-slug/module-instance-slug/module-alias/test-page/1', $this->userApiUrl('/test-page/1'));
    }

    /** @test */
    public function user_api_url_appends_path_starting_without_a_forward_slash_to_the_end_of_the_url()
    {
        $activity = Activity::factory()->create(['slug' => 'activity-slug']);
        $moduleInstance = ModuleInstance::factory()->create(['slug' => 'module-instance-slug']);
        $this->setActivity($activity);
        $this->setModuleInstance($moduleInstance);
        $this->createModuleEnvironment('module-alias');

        $this->assertEquals('/api/p/activity-slug/module-instance-slug/module-alias/test-page/1', $this->userApiUrl('test-page/1'));
    }

    /** @test */
    public function get_activity_returns_the_activity()
    {
        $this->createModuleEnvironment('alias1');
        $this->assertInstanceOf(Activity::class, $this->getActivity());
    }

    /** @test */
    public function get_module_is_for_returns_the_activity_for_of_the_activity()
    {
        $this->setModuleIsFor('user');
        $this->createModuleEnvironment('alias1');
        $this->assertIsString($this->getModuleIsFor());
        $this->assertEquals('user', $this->getModuleIsFor());
    }

    /** @test */
    public function get_activity_instance_returns_the_activity_instance()
    {
        $this->createModuleEnvironment('alias1');
        $this->assertInstanceOf(ActivityInstance::class, $this->getActivityInstance());
    }

    /** @test */
    public function get_module_instance_returns_the_module_instance()
    {
        $this->createModuleEnvironment('alias1');
        $this->assertInstanceOf(ModuleInstance::class, $this->getModuleInstance());
    }

    /** @test */
    public function get_control_user_returns_the_control_user()
    {
        $this->createModuleEnvironment('alias1');
        $this->assertInstanceOf(User::class, $this->getControlUser());
    }

    /** @test */
    public function get_control_group_returns_the_control_user()
    {
        $this->setModuleIsFor('group');
        $this->createModuleEnvironment('alias1');
        $this->assertInstanceOf(Group::class, $this->getControlGroup());
    }

    /** @test */
    public function get_control_role_returns_the_control_user()
    {
        $this->setModuleIsFor('role');
        $this->createModuleEnvironment('alias1');
        $this->assertInstanceOf(Role::class, $this->getControlRole());
    }
}
