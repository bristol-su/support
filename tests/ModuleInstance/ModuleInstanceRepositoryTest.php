<?php

namespace BristolSU\Support\Tests\ModuleInstance;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\ModuleInstance\ModuleInstanceRepository;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ModuleInstanceRepositoryTest extends TestCase
{
    /** @test */
    public function it_retrieves_a_module_instance_by_id()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        $this->assertDatabaseHas('module_instances', [
            'id' => $moduleInstance->id,
            'alias' => $moduleInstance->alias
        ]);

        $repository = new ModuleInstanceRepository();
        $foundInstance = $repository->getById($moduleInstance->id);

        $this->assertInstanceOf(ModuleInstance::class, $foundInstance);
        $this->assertModelEquals($moduleInstance, $foundInstance);
    }

    /** @test */
    public function it_throws_an_exception_if_module_instance_not_found()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new ModuleInstanceRepository();
        $repository->getById(10);
    }

    /** @test */
    public function it_creates_a_module_instance()
    {
        $repository = new ModuleInstanceRepository();
        $activity = Activity::factory()->create();

        $instance = $repository->create([
            'alias' => 'alias',
            'activity_id' => $activity->id,
            'name' => 'name',
            'description' => 'description',
            'slug' => 'slug1',
            'active' => 1,
            'visible' => 2,
            'mandatory' => 3,
            'completion_condition_instance_id' => 4,
            'enabled' => true,
            'user_id' => 5
        ]);

        $this->assertDatabaseHas('module_instances', [
            'alias' => 'alias',
            'activity_id' => $activity->id,
            'name' => 'name',
            'description' => 'description',
            'slug' => 'slug1',
            'active' => 1,
            'visible' => 2,
            'mandatory' => 3,
            'completion_condition_instance_id' => 4,
            'enabled' => 1,
            'user_id' => 5
        ]);
    }

    /** @test */
    public function all_returns_all_module_instances()
    {
        $moduleInstances = ModuleInstance::factory()->count(5)->create();

        $repository = new ModuleInstanceRepository();
        $foundModuleInstances = $repository->all();

        $this->assertEquals(5, $foundModuleInstances->count());
        $this->assertContainsOnlyInstancesOf(ModuleInstance::class, $foundModuleInstances);
        foreach ($moduleInstances as $moduleInstance) {
            $this->assertModelEquals($moduleInstance, $foundModuleInstances->shift());
        }
    }

    /** @test */
    public function all_with_alias_returns_all_module_instances_with_the_alias()
    {
        ModuleInstance::factory()->count(5)->create();
        $moduleInstances = ModuleInstance::factory()->count(3)->create(['alias' => 'an-alias-here']);
        ModuleInstance::factory()->count(3)->create();

        $repository = new ModuleInstanceRepository();
        $foundModuleInstances = $repository->allWithAlias('an-alias-here');

        $this->assertEquals(3, $foundModuleInstances->count());
        $this->assertContainsOnlyInstancesOf(ModuleInstance::class, $foundModuleInstances);
        foreach ($moduleInstances as $moduleInstance) {
            $this->assertModelEquals($moduleInstance, $foundModuleInstances->shift());
        }
    }

    /** @test */
    public function it_retrieves_all_module_instances_through_an_activity()
    {
        $activity = Activity::factory()->create();
        $activity2 = Activity::factory()->create();

        $moduleInstances = ModuleInstance::factory()->count(10)->create(['activity_id' => $activity->id]);
        $otherModuleInstances = ModuleInstance::factory()->count(4)->create(['activity_id' => $activity2->id]);

        $repository = new ModuleInstanceRepository();
        $foundModuleInstances = $repository->allThroughActivity($activity);
        $this->assertEquals(10, $foundModuleInstances->count());

        foreach ($moduleInstances as $moduleInstance) {
            $this->assertModelEquals($moduleInstance, $foundModuleInstances->shift());
        }

        $foundModuleInstances = $repository->allThroughActivity($activity2);
        $this->assertEquals(4, $foundModuleInstances->count());

        foreach ($otherModuleInstances as $moduleInstance) {
            $this->assertModelEquals($moduleInstance, $foundModuleInstances->shift());
        }
    }

    /** @test */
    public function it_retrieves_all_enabled_module_instances_through_an_activity()
    {
        $activity = Activity::factory()->create();

        $moduleInstances = ModuleInstance::factory()->count(10)->create(['activity_id' => $activity->id, 'enabled' => true]);
        $otherModuleInstances = ModuleInstance::factory()->count(4)->create(['activity_id' => $activity->id, 'enabled' => false]);

        $repository = new ModuleInstanceRepository();
        $foundModuleInstances = $repository->allEnabledThroughActivity($activity);
        $this->assertEquals(10, $foundModuleInstances->count());

        foreach ($moduleInstances as $moduleInstance) {
            $this->assertModelEquals($moduleInstance, $foundModuleInstances->shift());
        }
    }

    /** @test */
    public function it_updates_a_module_instance()
    {
        $repository = new ModuleInstanceRepository();

        $instance = ModuleInstance::factory()->create([
            'alias' => 'alias',
            'activity_id' => 1,
            'name' => 'name',
            'description' => 'description',
            'slug' => 'slug1',
            'active' => 2,
            'visible' => 3,
            'mandatory' => 4,
            'completion_condition_instance_id' => 5,
            'enabled' => true,
            'user_id' => 6
        ]);

        $this->assertDatabaseHas('module_instances', [
            'alias' => 'alias',
            'activity_id' => 1,
            'name' => 'name',
            'description' => 'description',
            'slug' => 'slug1',
            'active' => 2,
            'visible' => 3,
            'mandatory' => 4,
            'completion_condition_instance_id' => 5,
            'enabled' => true,
            'user_id' => 6
        ]);

        $newInstance = $repository->update($instance->id, [
            'alias' => 'aliasNew',
            'activity_id' => 7,
            'name' => 'nameNew',
            'description' => 'descriptionNew',
            'slug' => 'slug1New',
            'active' => 8,
            'visible' => 9,
            'mandatory' => 10,
            'completion_condition_instance_id' => 11,
            'enabled' => false,
            'user_id' => 12
        ]);

        $this->assertDatabaseHas('module_instances', [
            'alias' => 'aliasNew',
            'activity_id' => 7,
            'name' => 'nameNew',
            'description' => 'descriptionNew',
            'slug' => 'slug1New',
            'active' => 8,
            'visible' => 9,
            'mandatory' => 10,
            'completion_condition_instance_id' => 11,
            'enabled' => 0,
            'user_id' => 12
        ]);

        $this->assertEquals($instance->id, $newInstance->id);
        $this->assertEquals('aliasNew', $newInstance->alias);
        $this->assertEquals(7, $newInstance->activity_id);
        $this->assertEquals('nameNew', $newInstance->name);
        $this->assertEquals('descriptionNew', $newInstance->description);
        $this->assertEquals('slug1New', $newInstance->slug);
        $this->assertEquals(8, $newInstance->active);
        $this->assertEquals(9, $newInstance->visible);
        $this->assertEquals(10, $newInstance->mandatory);
        $this->assertEquals(11, $newInstance->completion_condition_instance_id);
        $this->assertEquals(false, $newInstance->enabled);
        $this->assertEquals(12, $newInstance->user_id);
    }

    /** @test */
    public function it_deletes_a_module_instance()
    {
        $instance = ModuleInstance::factory()->create();
        $repository = new ModuleInstanceRepository();

        $this->assertDatabaseHas('module_instances', ['id' => $instance->id]);

        $repository->delete($instance->id);
        $this->assertDatabaseMissing('module_instances', ['id' => $instance->id]);
    }
}
