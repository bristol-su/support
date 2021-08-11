<?php

namespace BristolSU\Support\Tests\Progress\Handlers\Database\Models;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Progress\Handlers\Database\Models\ModuleInstanceProgress;
use BristolSU\Support\Progress\Handlers\Database\Models\Progress;
use BristolSU\Support\Tests\TestCase;
use Carbon\Carbon;

class ProgressTest extends TestCase
{
    /** @test */
    public function the_progress_model_can_be_created()
    {
        $now = Carbon::now();

        $progress = \BristolSU\Support\Progress\Handlers\Database\Models\Progress::create([
            'activity_instance_id' => 4,
            'complete' => false,
            'percentage' => 40,
            'timestamp' => $now
        ]);

        $this->assertDatabaseHas('progress', [
            'activity_instance_id' => 4,
            'complete' => 0,
            'percentage' => 40,
            'timestamp' => $now->format('Y-m-d H:i:s')
        ]);
    }

    /** @test */
    public function the_module_instance_progresses_can_be_retrieved()
    {
        $progress = Progress::factory()->create();
        $moduleInstanceProgresses = ModuleInstanceProgress::factory()->count(5)->create([
            'progress_id' => $progress->id,
        ]);

        $this->assertCount(5, $progress->moduleInstanceProgress);
        $this->assertModelEquals($moduleInstanceProgresses[0], $progress->moduleInstanceProgress[0]);
        $this->assertModelEquals($moduleInstanceProgresses[1], $progress->moduleInstanceProgress[1]);
        $this->assertModelEquals($moduleInstanceProgresses[2], $progress->moduleInstanceProgress[2]);
        $this->assertModelEquals($moduleInstanceProgresses[3], $progress->moduleInstanceProgress[3]);
        $this->assertModelEquals($moduleInstanceProgresses[4], $progress->moduleInstanceProgress[4]);
    }

    /** @test */
    public function the_activity_instance_can_be_retrieved()
    {
        $activityInstance = ActivityInstance::factory()->create();
        ActivityInstance::factory()->count(3)->create();
        $progress = Progress::factory()->create(['activity_instance_id' => $activityInstance->id]);

        $this->assertModelEquals($activityInstance, $progress->activityInstance);
    }
}
