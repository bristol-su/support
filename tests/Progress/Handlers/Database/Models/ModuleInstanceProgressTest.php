<?php

namespace BristolSU\Support\Tests\Progress\Handlers\Database\Models;

use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Progress\Handlers\Database\Models\ModuleInstanceProgress;
use BristolSU\Support\Progress\Handlers\Database\Models\Progress;
use BristolSU\Support\Tests\TestCase;
use Carbon\Carbon;

class ModuleInstanceProgressTest extends TestCase
{
    /** @test */
    public function the_module_instance_progress_model_can_be_created()
    {
        $now = Carbon::now();

        $progress = ModuleInstanceProgress::create([
            'module_instance_id' => 2,
            'progress_id' => 3,
            'mandatory' => true,
            'complete' => false,
            'percentage' => 20,
            'active' => true,
            'visible' => false
        ]);

        $this->assertDatabaseHas('module_instance_progress', [
            'module_instance_id' => 2,
            'progress_id' => 3,
            'mandatory' => 1,
            'complete' => 0,
            'percentage' => 20,
            'active' => 1,
            'visible' => 0
        ]);
    }

    /** @test */
    public function the_progress_can_be_retrieved()
    {
        $progress = Progress::factory()->create();
        $moduleInstanceProgress = ModuleInstanceProgress::factory()->create([
            'progress_id' => $progress->id,
        ]);

        $this->assertModelEquals($progress, $moduleInstanceProgress->progress);
    }

    /** @test */
    public function the_module_instance_can_be_retrieved()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        ModuleInstance::factory()->count(3)->create();
        $progress = ModuleInstanceProgress::factory()->create(['module_instance_id' => $moduleInstance->id]);

        $this->assertModelEquals($moduleInstance, $progress->moduleInstance);
    }
}
