<?php

namespace BristolSU\Support\Tests\Http\Controllers;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\User\User as DatabaseUser;

class ModuleInstanceRedirectControllerTest extends TestCase
{
    
    public function setUp(): void
    {
        parent::setUp();
        $this->activity = factory(Activity::class)->create(['slug' => 'act']);
        $this->moduleInstance = factory(ModuleInstance::class)->create(['slug' => 'mod', 'activity_id' => $this->activity->id, 'alias' => $this->alias()]);
        $this->databaseUser = factory(DatabaseUser::class)->create();
        $this->activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $this->activity->id, 'resource_id' => $this->databaseUser->control_id, 'resource_type' => 'user']);
        $this->user = new User(['id' => $this->databaseUser->control_id]);
        $this->group = new Group(['id' => 3]);
        $this->role = new Role(['id' => 5]);
        $this->app->instance(Activity::class, $this->activity);
        $this->app->instance(ActivityInstance::class, $this->activityInstance);
        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->willReturn($this->activityInstance);
        $this->app->instance(ActivityInstanceResolver::class, $activityInstanceResolver->reveal());
        $this->app->instance(ModuleInstance::class, $this->moduleInstance);
    }

    /** @test */
    public function it_redirects_an_admin_module_instance_to_the_correct_url(){
        $this->beUser(new User(['id' => $this->databaseUser->control_id]));
        $response = $this->get('/a/' . $this->moduleInstance->activity->slug . '/' . $this->moduleInstance->slug);
        $response->assertRedirect('/a/' . $this->moduleInstance->activity->slug . '/' . $this->moduleInstance->slug . '/' . $this->moduleInstance->alias);
    }

    /** @test */
    public function it_redirects_a_participant_module_instance_to_the_correct_url(){
        $this->beUser(new User(['id' => $this->databaseUser->control_id]));
        $response = $this->get('/p/' . $this->moduleInstance->activity->slug . '/' . $this->moduleInstance->slug);
        $response->assertRedirect('/p/' . $this->moduleInstance->activity->slug . '/' . $this->moduleInstance->slug . '/' . $this->moduleInstance->alias);
    }
    
}