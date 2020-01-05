<?php

namespace BristolSU\Support\Tests\ActivityInstance;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;
use Carbon\Carbon;

class ActivityInstanceTest extends TestCase
{

    /** @test */
    public function runNumber_returns_1_if_the_activity_instance_is_the_first(){
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()]);
        $this->assertEquals(1, $activityInstance->run_number);
    }

    /** @test */
    public function runNumber_returns_1_if_no_activity_instances_found(){
        $activityInstance = new ActivityInstance(['activity_id' => 500, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()]);
        $this->assertEquals(1, $activityInstance->run_number);
    }

    /** @test */
    public function runNumber_returns_2_if_the_activity_instance_is_the_second(){
        $activityInstance1 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()]);
        $activityInstance2 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()->addSecond()]);
        $this->assertEquals(2, $activityInstance2->run_number);
    }

    /** @test */
    public function runNumber_returns_3_if_the_activity_instance_is_the_third(){
        $activityInstance1 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()]);
        $activityInstance2 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()->addSecond()]);
        $activityInstance3 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()->addSeconds(2)]);
        $this->assertEquals(3, $activityInstance3->run_number);
    }
    
    /** @test */
    public function runNumber_only_looks_at_similar_activity_instances(){
        $activityInstance1 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()]);
        $activityInstance2 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()->addSecond()]);
        $activityInstance3 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()->addSeconds(2)]);
        $activityInstancefake1 = factory(ActivityInstance::class)->create(['activity_id' => 2, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()]);
        $activityInstancefake2 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'group', 'resource_id' => 1, 'created_at' => Carbon::now()]);
        $activityInstancefake3 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 2, 'created_at' => Carbon::now()]);
        $this->assertEquals(3, $activityInstance3->run_number);
    }
    
    /** @test */
    public function it_belongs_to_an_activity(){
        $activity = factory(Activity::class)->create();
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);
        
        $this->assertModelEquals($activity, $activityInstance->activity);
    }
    
    /** @test */
    public function it_has_module_instances_through_an_activity(){
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
    public function getParticipantAttribute_returns_a_user_if_resource_type_is_a_user(){
        $user = new User(['id' => 3]);
        $userRepository = $this->prophesize(UserRepository::class);
        $userRepository->getById(3)->shouldBeCalledTimes(3)->willReturn($user);
        $this->app->instance(UserRepository::class, $userRepository->reveal());
        
        $activityInstance = factory(ActivityInstance::class)->create(['resource_type' => 'user', 'resource_id' => 3]);
        $this->assertEquals($user, $activityInstance->getParticipantAttribute());
        $this->assertEquals($user, $activityInstance->participant);
        $this->assertEquals($user, $activityInstance->participant());
    }

    /** @test */
    public function getParticipantAttribute_returns_a_group_if_resource_type_is_a_group(){
        $group = new Group(['id' => 3]);
        $groupRepository = $this->prophesize(GroupRepository::class);
        $groupRepository->getById(3)->shouldBeCalledTimes(3)->willReturn($group);
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());

        $activityInstance = factory(ActivityInstance::class)->create(['resource_type' => 'group', 'resource_id' => 3]);
        $this->assertEquals($group, $activityInstance->getParticipantAttribute());
        $this->assertEquals($group, $activityInstance->participant);
        $this->assertEquals($group, $activityInstance->participant());
    }

    /** @test */
    public function getParticipantAttribute_returns_a_role_if_resource_type_is_a_role(){
        $role = new Role(['id' => 3]);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->getById(3)->shouldBeCalledTimes(3)->willReturn($role);
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());

        $activityInstance = factory(ActivityInstance::class)->create(['resource_type' => 'role', 'resource_id' => 3]);
        $this->assertEquals($role, $activityInstance->getParticipantAttribute());
        $this->assertEquals($role, $activityInstance->participant);
        $this->assertEquals($role, $activityInstance->participant());
    }
    
    /** @test */
    public function getParticipantAttribute_throws_an_exception_if_the_resource_type_is_not_one_of_user_group_or_role(){
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Resource type is not valid');
        
        $activityInstance = factory(ActivityInstance::class)->make();
        $activityInstance->resource_type = 'notvalid';
        $activityInstance->getParticipantAttribute();
    }
    
}