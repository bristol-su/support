<?php

namespace BristolSU\Support\Tests\Module;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\Settings\ModuleInstanceSetting;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Models\ModuleInstancePermission;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\ModuleInstance\ModuleInstanceRepository;

class ModuleInstanceRepositoryTest extends TestCase
{

    /** @test */
    public function it_retrieves_a_module_instance_by_id(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        $this->assertDatabaseHas('module_instances', $moduleInstance->toArray());

        $repository = new ModuleInstanceRepository;
        $foundInstance = $repository->getById($moduleInstance->id);

        $this->assertInstanceOf(ModuleInstance::class, $foundInstance);
        $this->assertTrue($moduleInstance->is($foundInstance));
    }

    /** @test */
    public function it_throws_an_exception_if_module_instance_not_found(){
        $this->expectException(ModelNotFoundException::class);

        $repository = new ModuleInstanceRepository;
        $repository->getById(10);
    }

    /** @test */
    public function it_creates_a_module_instance(){
        $repository = new ModuleInstanceRepository;
        $activity = factory(Activity::class)->create();
        $instance = $repository->create([
            'alias' => 'alias',
            'activity_id' => $activity->id,
            'name' => 'name',
            'description' => 'description',
            'active' => 1,
            'visible' => 2,
            'mandatory' => 3,
            'complete' => 'complete',
        ]);

        $this->assertDatabaseHas('module_instances', [
            'alias' => 'alias',
            'activity_id' => $activity->id,
            'name' => 'name',
            'description' => 'description',
            'active' => 1,
            'visible' => 2,
            'mandatory' => 3,
            'complete' => 'complete',
        ]);
    }

}
