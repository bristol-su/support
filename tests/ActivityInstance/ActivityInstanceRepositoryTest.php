<?php

namespace BristolSU\Support\Tests\ActivityInstance;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\ActivityInstanceRepository;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ActivityInstanceRepositoryTest extends TestCase
{

    /**
     * @var ActivityInstanceRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new ActivityInstanceRepository;
    }

    /** @test */
    public function firstFor_returns_the_first_available_model(){
        $activityInstanceFake = factory(ActivityInstance::class)->create(['activity_id' => 2, 'resource_type' => 'user', 'resource_id' => 1]);
        $activityInstanceFake2 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'group', 'resource_id' => 1]);
        $activityInstanceFake3 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 2]);
        $activityInstance1 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1]);
        $activityInstance2 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1]);
        
        $this->assertModelEquals($activityInstance1, $this->repository->firstFor(1, 'user', 1));
    }
    
    /** @test */
    public function firstFor_throws_an_exception_if_no_models_are_found(){
        $this->expectException(ModelNotFoundException::class);
        $this->repository->firstFor(1, 'user', 1);
    }
    
    /** @test */
    public function create_creates_an_activity_instance(){
        $this->repository->create(1, 'user', 2, 'name1', 'desc1');
        $this->assertDatabaseHas('activity_instances', [
            'activity_id' => 1,
            'resource_type' => 'user',
            'resource_id' => 2,
            'name' => 'name1',
            'description' => 'desc1'
        ]);
    }
    
    /** @test */
    public function getById_returns_an_activity_instance_with_the_given_id(){
        $activityInstance = factory(ActivityInstance::class)->create();
        $this->assertModelEquals($activityInstance, $this->repository->getById($activityInstance->id));
    }
    
    /** @test */
    public function getById_throws_an_exception_if_not_found(){
        $this->expectException(ModelNotFoundException::class);
        $this->assertDatabaseMissing('activity_instances', ['id' => 1]);
        $this->repository->getById(1);
    }
    
    /** @test */
    public function allFor_returns_all_matching_activity_instances(){
        $activityInstanceFake = factory(ActivityInstance::class)->create(['activity_id' => 2, 'resource_type' => 'user', 'resource_id' => 1]);
        $activityInstanceFake2 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'group', 'resource_id' => 1]);
        $activityInstanceFake3 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 2]);
        $activityInstance1 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1]);
        $activityInstance2 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1]);
        $activityInstance3 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1]);
        
        $instances = $this->repository->allFor(1, 'user', 1);
        $this->assertModelEquals($activityInstance1, $instances->offsetGet(0));
        $this->assertModelEquals($activityInstance2, $instances->offsetGet(1));
        $this->assertModelEquals($activityInstance3, $instances->offsetGet(2));
    }
}