<?php

namespace BristolSU\Support\Tests\ActivityInstance;

use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\ControlDB\Models\DataGroup;
use BristolSU\ControlDB\Models\DataRole;
use BristolSU\ControlDB\Models\DataUser;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Progress\Handlers\Database\Models\Progress;
use BristolSU\Support\Tests\TestCase;
use Carbon\Carbon;

class ActivityInstanceTest extends TestCase
{
    /** @test */
    public function run_number_returns_1_if_the_activity_instance_is_the_first()
    {
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()]);
        $this->assertEquals(1, $activityInstance->run_number);
    }

    /** @test */
    public function run_number_returns_1_if_no_activity_instances_found()
    {
        $activityInstance = new ActivityInstance(['activity_id' => 500, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()]);
        $this->assertEquals(1, $activityInstance->run_number);
    }

    /** @test */
    public function run_number_returns_2_if_the_activity_instance_is_the_second()
    {
        $activityInstance1 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()]);
        $activityInstance2 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()->addSecond()]);
        $this->assertEquals(2, $activityInstance2->run_number);
    }

    /** @test */
    public function run_number_returns_3_if_the_activity_instance_is_the_third()
    {
        $activityInstance1 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()]);
        $activityInstance2 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()->addSecond()]);
        $activityInstance3 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()->addSeconds(2)]);
        $this->assertEquals(3, $activityInstance3->run_number);
    }

    /** @test */
    public function run_number_only_looks_at_similar_activity_instances()
    {
        $activityInstance1 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()]);
        $activityInstance2 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()->addSecond()]);
        $activityInstance3 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()->addSeconds(2)]);
        $activityInstancefake1 = factory(ActivityInstance::class)->create(['activity_id' => 2, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()]);
        $activityInstancefake2 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'group', 'resource_id' => 1, 'created_at' => Carbon::now()]);
        $activityInstancefake3 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 2, 'created_at' => Carbon::now()]);
        $this->assertEquals(3, $activityInstance3->run_number);
    }

    /** @test */
    public function it_belongs_to_an_activity()
    {
        $activity = factory(Activity::class)->create();
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);

        $this->assertModelEquals($activity, $activityInstance->activity);
    }

    /** @test */
    public function it_has_module_instances_through_an_activity()
    {
        $activityFake = factory(Activity::class)->create();
        $moduleInstanceFake1 = factory(ModuleInstance::class)->create(['activity_id' => $activityFake->id]);
        $moduleinstanceFake2 = factory(ModuleInstance::class)->create(['activity_id' => $activityFake->id]);

        $activity = factory(Activity::class)->create();
        $moduleInstance1 = factory(ModuleInstance::class)->create(['activity_id' => $activity->id]);
        $moduleInstance2 = factory(ModuleInstance::class)->create(['activity_id' => $activity->id]);
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);

        $moduleInstances = $activityInstance->moduleInstances;

        $this->assertModelEquals($moduleInstance1, $moduleInstances->offsetGet(0));
        $this->assertModelEquals($moduleInstance2, $moduleInstances->offsetGet(1));
    }

    /** @test */
    public function get_participant_attribute_returns_a_user_if_resource_type_is_a_user()
    {
        $user = $this->newUser();
        $userRepository = $this->prophesize(UserRepository::class);
        $userRepository->getById($user->id())->shouldBeCalledTimes(3)->willReturn($user);
        $this->app->instance(UserRepository::class, $userRepository->reveal());

        $activityInstance = factory(ActivityInstance::class)->create(['resource_type' => 'user', 'resource_id' => $user->id()]);
        $this->assertEquals($user, $activityInstance->getParticipantAttribute());
        $this->assertEquals($user, $activityInstance->participant);
        $this->assertEquals($user, $activityInstance->participant());
    }

    /** @test */
    public function get_participant_attribute_returns_a_group_if_resource_type_is_a_group()
    {
        $group = $this->newGroup();
        $groupRepository = $this->prophesize(GroupRepository::class);
        $groupRepository->getById($group->id())->shouldBeCalledTimes(3)->willReturn($group);
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());

        $activityInstance = factory(ActivityInstance::class)->create(['resource_type' => 'group', 'resource_id' => $group->id()]);
        $this->assertEquals($group, $activityInstance->getParticipantAttribute());
        $this->assertEquals($group, $activityInstance->participant);
        $this->assertEquals($group, $activityInstance->participant());
    }

    /** @test */
    public function get_participant_attribute_returns_a_role_if_resource_type_is_a_role()
    {
        $role = $this->newRole();
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->getById($role->id())->shouldBeCalledTimes(3)->willReturn($role);
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());

        $activityInstance = factory(ActivityInstance::class)->create(['resource_type' => 'role', 'resource_id' => $role->id()]);
        $this->assertEquals($role, $activityInstance->getParticipantAttribute());
        $this->assertEquals($role, $activityInstance->participant);
        $this->assertEquals($role, $activityInstance->participant());
    }

    /** @test */
    public function get_participant_attribute_throws_an_exception_if_the_resource_type_is_not_one_of_user_group_or_role()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Resource type is not valid');

        $activityInstance = factory(ActivityInstance::class)->make();
        $activityInstance->resource_type = 'notvalid';
        $activityInstance->getParticipantAttribute();
    }

    /** @test */
    public function get_auth_identifier_name_returns_id()
    {
        $activityInstance = factory(ActivityInstance::class)->create();
        $this->assertEquals('id', $activityInstance->getAuthIdentifierName());
    }

    /** @test */
    public function get_auth_identifier_returns_id_of_model()
    {
        $activityInstance = factory(ActivityInstance::class)->create();
        $this->assertEquals($activityInstance->id, $activityInstance->getAuthIdentifier());
    }

    /** @test */
    public function get_auth_password_returns_null()
    {
        $activityInstance = factory(ActivityInstance::class)->create();
        $this->assertNull($activityInstance->getAuthPassword());
    }

    /** @test */
    public function get_remember_token_returns_null()
    {
        $activityInstance = factory(ActivityInstance::class)->create();
        $this->assertNull($activityInstance->getRememberToken());
    }

    /** @test */
    public function get_remember_token_name_returns_null()
    {
        $activityInstance = factory(ActivityInstance::class)->create();
        $this->assertNull($activityInstance->getRememberTokenName());
    }

    /** @test */
    public function set_remember_token_name_returns_null()
    {
        $activityInstance = factory(ActivityInstance::class)->create();
        $this->assertNull($activityInstance->setRememberToken('token'));
    }

    /** @test */
    public function revisions_are_saved()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $activityInstance = factory(ActivityInstance::class)->create(['name' => 'OldName']);

        $activityInstance->name = 'NewName';
        $activityInstance->save();

        $this->assertEquals(1, $activityInstance->revisionHistory->count());
        $this->assertEquals($activityInstance->id, $activityInstance->revisionHistory->first()->revisionable_id);
        $this->assertEquals(ActivityInstance::class, $activityInstance->revisionHistory->first()->revisionable_type);
        $this->assertEquals('name', $activityInstance->revisionHistory->first()->key);
        $this->assertEquals('OldName', $activityInstance->revisionHistory->first()->old_value);
        $this->assertEquals('NewName', $activityInstance->revisionHistory->first()->new_value);
    }

    /** @test */
    public function participant_name_returns_the_group_name_if_the_activity_instance_is_for_a_group()
    {
        $dataGroup = factory(DataGroup::class)->create(['name' => 'Group Name 1']);
        $group = $this->newGroup(['data_provider_id' => $dataGroup->id()]);

        $activityInstance = factory(ActivityInstance::class)->create(['resource_type' => 'group', 'resource_id' => $group->id()]);
        $this->assertEquals('Group Name 1', $activityInstance->participantName());
    }

    /** @test */
    public function participant_name_returns_the_user_preferred_name_if_the_activity_instance_is_for_a_user()
    {
        $dataUser = factory(DataUser::class)->create(['preferred_name' => 'User Name 1']);
        $user = $this->newUser(['data_provider_id' => $dataUser->id()]);

        $activityInstance = factory(ActivityInstance::class)->create(['resource_type' => 'user', 'resource_id' => $user->id()]);
        $this->assertEquals('User Name 1', $activityInstance->participantName());
    }

    /** @test */
    public function participant_name_returns_the_role_name_if_the_activity_instance_is_for_a_role()
    {
        $dataRole = factory(DataRole::class)->create(['role_name' => 'Role Name 1']);
        $role = $this->newRole(['data_provider_id' => $dataRole->id()]);

        $activityInstance = factory(ActivityInstance::class)->create(['resource_type' => 'role', 'resource_id' => $role->id()]);
        $this->assertEquals('Role Name 1', $activityInstance->participantName());
    }

    /** @test */
    public function participant_name_throws_an_exception_if_the_type_is_not_one_of_user_group_or_role()
    {
        $this->expectException(\Exception::class);

        $activityInstance = new ActivityInstance();
        $activityInstance->participantName();
    }

    /** @test */
    public function it_has_many_progresses()
    {
        $activityInstance = factory(ActivityInstance::class)->create();
        factory(Progress::class, 5)->create();
        $progresses = factory(Progress::class, 2)->create(['activity_instance_id' => $activityInstance->id]);
        factory(Progress::class, 5)->create();

        $retrievedProgresses = $activityInstance->progress;
        $this->assertCount(2, $retrievedProgresses);
        $this->assertModelEquals($progresses[0], $retrievedProgresses[0]);
        $this->assertModelEquals($progresses[1], $retrievedProgresses[1]);
    }
}
