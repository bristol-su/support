<?php

namespace BristolSU\Support\Tests\Authentication;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authentication\HasResource;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\Control\Models\User;
use Illuminate\Database\Eloquent\Builder;

class HasResourceTest extends TestCase
{
    use HasResource;
    
    /** @test */
    public function activity_instance_id_returns_the_activity_instance_id(){
        
    }
    
    /** @test */
    public function module_instance_id_returns_the_module_instance_id(){
        
    }
    
//    /** @test */
//    public function scopeForResource_applies_query_conditions_to_only_find_relevant_models(){
//        $activity = factory(Activity::class)->create([
//            'activity_for' => 'user'
//        ]);
//        $moduleInstance = factory(ModuleInstance::class)->create(['activity_id' => $activity]);
//        $this->instance(ModuleInstance::class, $moduleInstance);
//
//        $user = new User(['id' => 1]);
//        $authentication = $this->prophesize(Authentication::class);
//        $authentication->getUser()->shouldBeCalled()->willReturn($user);
//        $this->instance(Authentication::class, $authentication->reveal());
//
//        $query = $this->prophesize(Builder::class);
//        $query->where('resource_type', 'user')->shouldBeCalled()->willReturn($query->reveal());
//        $query->where('resource_id', $user->id)->shouldBeCalled()->willReturn($query->reveal());
//        
//        $this->scopeForResource($query->reveal());
//        
//    }
//
//    /** @test */
//    public function scopeForResource_applies_query_conditions_to_only_find_relevant_group_models(){
//        $activity = factory(Activity::class)->create([
//            'activity_for' => 'group'
//        ]);
//        $moduleInstance = factory(ModuleInstance::class)->create(['activity_id' => $activity]);
//        $this->instance(ModuleInstance::class, $moduleInstance);
//
//        $group = new Group(['id' => 2]);
//        $authentication = $this->prophesize(Authentication::class);
//        $authentication->getGroup()->shouldBeCalled()->willReturn($group);
//        $this->instance(Authentication::class, $authentication->reveal());
//
//        $query = $this->prophesize(Builder::class);
//        $query->where('resource_type', 'group')->shouldBeCalled()->willReturn($query->reveal());
//        $query->where('resource_id', 2)->shouldBeCalled()->willReturn($query->reveal());
//
//        $this->scopeForResource($query->reveal());
//
//    }
//
//
//    /** @test */
//    public function resource_id_returns_the_current_role_id_if_activity_type_is_role(){
//        $activity = factory(Activity::class)->create([
//            'activity_for' => 'role'
//        ]);
//        $moduleInstance = factory(ModuleInstance::class)->create(['activity_id' => $activity]);
//        $this->instance(ModuleInstance::class, $moduleInstance);
//
//        $role = new Role(['id' => 2]);
//        $authentication = $this->prophesize(Authentication::class);
//        $authentication->getRole()->shouldBeCalled()->willReturn($role);
//        $this->instance(Authentication::class, $authentication->reveal());
//
//        $this->assertEquals($role->id, static::resourceId());
//    }
//
//    /** @test */
//    public function resource_id_throws_an_exception_if_the_activity_type_is_role_but_a_role_is_not_logged_in(){
//        $this->expectException(\Exception::class);
//        $this->expectExceptionMessage('You must be logged in as a role');
//        $this->expectExceptionCode(403);
//
//        $activity = factory(Activity::class)->create([
//            'activity_for' => 'role'
//        ]);
//        $moduleInstance = factory(ModuleInstance::class)->create(['activity_id' => $activity]);
//        $this->instance(ModuleInstance::class, $moduleInstance);
//
//        $this->instance(Authentication::class, $this->prophesize(Authentication::class)->reveal());
//
//        static::resourceId();
//    }
//
//    /** @test */
//    public function scopeForResource_applies_query_conditions_to_only_find_relevant_role_models(){
//        $activity = factory(Activity::class)->create([
//            'activity_for' => 'role'
//        ]);
//        $moduleInstance = factory(ModuleInstance::class)->create(['activity_id' => $activity]);
//        $this->instance(ModuleInstance::class, $moduleInstance);
//
//        $role = new Role(['id' => 2]);
//        $authentication = $this->prophesize(Authentication::class);
//        $authentication->getRole()->shouldBeCalled()->willReturn($role);
//        $this->instance(Authentication::class, $authentication->reveal());
//
//        $query = $this->prophesize(Builder::class);
//        $query->where('resource_type', 'role')->shouldBeCalled()->willReturn($query->reveal());
//        $query->where('resource_id', 2)->shouldBeCalled()->willReturn($query->reveal());
//
//        $this->scopeForResource($query->reveal());
//
//    }

}