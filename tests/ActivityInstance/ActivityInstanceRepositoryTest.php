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
        $this->repository = new ActivityInstanceRepository();
    }

    /** @test */
    public function first_for_returns_the_first_available_model()
    {
        $activityInstanceFake = ActivityInstance::factory()->create(['activity_id' => 2, 'resource_type' => 'user', 'resource_id' => 1]);
        $activityInstanceFake2 = ActivityInstance::factory()->create(['activity_id' => 1, 'resource_type' => 'group', 'resource_id' => 1]);
        $activityInstanceFake3 = ActivityInstance::factory()->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 2]);
        $activityInstance1 = ActivityInstance::factory()->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1]);
        $activityInstance2 = ActivityInstance::factory()->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1]);

        $this->assertModelEquals($activityInstance1, $this->repository->firstFor(1, 'user', 1));
    }

    /** @test */
    public function first_for_throws_an_exception_if_no_models_are_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->repository->firstFor(1, 'user', 1);
    }

    /** @test */
    public function create_creates_an_activity_instance()
    {
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
    public function get_by_id_returns_an_activity_instance_with_the_given_id()
    {
        $activityInstance = ActivityInstance::factory()->create();
        $this->assertModelEquals($activityInstance, $this->repository->getById($activityInstance->id));
    }

    /** @test */
    public function get_by_id_throws_an_exception_if_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->assertDatabaseMissing('activity_instances', ['id' => 1]);
        $this->repository->getById(1);
    }

    /** @test */
    public function all_for_returns_all_matching_activity_instances()
    {
        $activityInstanceFake = ActivityInstance::factory()->create(['activity_id' => 2, 'resource_type' => 'user', 'resource_id' => 1]);
        $activityInstanceFake2 = ActivityInstance::factory()->create(['activity_id' => 1, 'resource_type' => 'group', 'resource_id' => 1]);
        $activityInstanceFake3 = ActivityInstance::factory()->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 2]);
        $activityInstance1 = ActivityInstance::factory()->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1]);
        $activityInstance2 = ActivityInstance::factory()->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1]);
        $activityInstance3 = ActivityInstance::factory()->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1]);

        $instances = $this->repository->allFor(1, 'user', 1);
        $this->assertModelEquals($activityInstance1, $instances->offsetGet(0));
        $this->assertModelEquals($activityInstance2, $instances->offsetGet(1));
        $this->assertModelEquals($activityInstance3, $instances->offsetGet(2));
    }

    /** @test */
    public function all_for_activity_returns_all_activity_instances_belonging_to_an_activity()
    {
        $activityInstanceFake1 = ActivityInstance::factory()->create(['activity_id' => 1]);
        $activityInstanceFake2 = ActivityInstance::factory()->create(['activity_id' => 1]);
        $activityInstanceFake3 = ActivityInstance::factory()->create(['activity_id' => 1]);
        $activityInstance1 = ActivityInstance::factory()->create(['activity_id' => 2]);
        $activityInstance2 = ActivityInstance::factory()->create(['activity_id' => 2]);
        $activityInstance3 = ActivityInstance::factory()->create(['activity_id' => 2]);

        $instances = $this->repository->allForActivity(2);
        $this->assertModelEquals($activityInstance1, $instances->offsetGet(0));
        $this->assertModelEquals($activityInstance2, $instances->offsetGet(1));
        $this->assertModelEquals($activityInstance3, $instances->offsetGet(2));
    }

    /** @test */
    public function all_for_resource_returns_all_activity_instances_for_a_resource()
    {
        $group = $this->newGroup();
        $group2 = $this->newGroup();
        $activityInstance1 = ActivityInstance::factory()->create(['resource_type' => 'group', 'resource_id' => $group->id()]);
        $activityInstance2 = ActivityInstance::factory()->create(['resource_type' => 'group', 'resource_id' => $group->id()]);
        $activityInstance3 = ActivityInstance::factory()->create(['resource_type' => 'group', 'resource_id' => $group->id()]);

        $activityInstance4 = ActivityInstance::factory()->create(['resource_type' => 'user', 'resource_id' => $group->id()]);
        $activityInstance5 = ActivityInstance::factory()->create(['resource_type' => 'group', 'resource_id' => $group2->id()]);

        $instances = $this->repository->allForResource('group', $group->id());
        $this->assertCount(3, $instances);
        $this->assertModelEquals($activityInstance1, $instances->offsetGet(0));
        $this->assertModelEquals($activityInstance2, $instances->offsetGet(1));
        $this->assertModelEquals($activityInstance3, $instances->offsetGet(2));
    }
}
