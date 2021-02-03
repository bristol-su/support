<?php

namespace BristolSU\Support\Tests\Progress\Handlers\Database;

use BristolSU\Support\Progress\Handlers\Database\DatabaseHandler;
use BristolSU\Support\Progress\ModuleInstanceProgress;
use BristolSU\Support\Progress\Progress;
use BristolSU\Support\Tests\TestCase;
use Carbon\Carbon;

class DatabaseHandlerTest extends TestCase
{
    /** @test */
    public function it_saves_a_progress()
    {
        $now = Carbon::now();
        $progress = Progress::create(2, 3, $now, false, 40);
        $moduleInstanceProgress1 = ModuleInstanceProgress::create(
            4,
            false,
            true,
            100,
            true,
            false
        );
        $moduleInstanceProgress2 = ModuleInstanceProgress::create(
            5,
            true,
            false,
            24,
            false,
            true
        );
        $moduleInstanceProgress3 = ModuleInstanceProgress::create(
            6,
            false,
            false,
            5,
            true,
            false
        );

        $progress->pushModule($moduleInstanceProgress1);
        $progress->pushModule($moduleInstanceProgress2);
        $progress->pushModule($moduleInstanceProgress3);
        
        $handler = new DatabaseHandler();
        $handler->save($progress);
        
        $this->assertDatabaseHas('progress', [
            'activity_instance_id' => 3,
            'complete' => 0,
            'percentage' => 40,
            'timestamp' => $now->format('Y-m-d H:i:s')
        ]);
        $progressModel = \BristolSU\Support\Progress\Handlers\Database\Models\Progress::where([
            'activity_instance_id' => 3,
            'complete' => 0,
            'percentage' => 40,
        ])->firstOrFail();
        
        $this->assertDatabaseHas('module_instance_progress', [
            'module_instance_id' => 4,
            'progress_id' => $progressModel->id,
            'mandatory' => 0,
            'complete' => 1,
            'percentage' => 100,
            'active' => 1,
            'visible' => 0
        ]);
        $this->assertDatabaseHas('module_instance_progress', [
            'module_instance_id' => 5,
            'progress_id' => $progressModel->id,
            'mandatory' => 1,
            'complete' => 0,
            'percentage' => 24,
            'active' => 0,
            'visible' => 1
        ]);
        $this->assertDatabaseHas('module_instance_progress', [
            'module_instance_id' => 6,
            'progress_id' => $progressModel->id,
            'mandatory' => 0,
            'complete' => 0,
            'percentage' => 5,
            'active' => 1,
            'visible' => 0
        ]);
    }

    /** @test */
    public function it_saves_many_progresses()
    {
        $now = Carbon::now();
        $progress1 = Progress::create(2, 3, $now, false, 40);
        $moduleInstanceProgress1 = ModuleInstanceProgress::create(
            4,
            false,
            true,
            100,
            true,
            false
        );
        $moduleInstanceProgress2 = ModuleInstanceProgress::create(
            5,
            true,
            false,
            24,
            false,
            true
        );
        $moduleInstanceProgress3 = ModuleInstanceProgress::create(
            6,
            false,
            false,
            5,
            true,
            false
        );

        $progress1->pushModule($moduleInstanceProgress1);
        $progress1->pushModule($moduleInstanceProgress2);
        $progress1->pushModule($moduleInstanceProgress3);

        $progress2 = Progress::create(7, 8, $now, true, 100);
        $moduleInstanceProgress1 = ModuleInstanceProgress::create(
            9,
            false,
            true,
            100,
            true,
            false
        );
        $moduleInstanceProgress2 = ModuleInstanceProgress::create(
            10,
            true,
            false,
            24,
            false,
            true
        );
        $moduleInstanceProgress3 = ModuleInstanceProgress::create(
            11,
            false,
            false,
            5,
            true,
            false
        );

        $progress2->pushModule($moduleInstanceProgress1);
        $progress2->pushModule($moduleInstanceProgress2);
        $progress2->pushModule($moduleInstanceProgress3);

        $handler = new DatabaseHandler();
        $handler->saveMany([$progress1, $progress2]);

        $this->assertDatabaseHas('progress', [
            'activity_instance_id' => 3,
            'complete' => 0,
            'percentage' => 40,
            'timestamp' => $now->format('Y-m-d H:i:s')
        ]);
        $progressModel1 = \BristolSU\Support\Progress\Handlers\Database\Models\Progress::where([
            'activity_instance_id' => 3,
            'complete' => 0,
            'percentage' => 40,
        ])->firstOrFail();
        
        $this->assertDatabaseHas('progress', [
            'activity_instance_id' => 8,
            'complete' => 1,
            'percentage' => 100,
            'timestamp' => $now->format('Y-m-d H:i:s')
        ]);
        $progressModel2 = \BristolSU\Support\Progress\Handlers\Database\Models\Progress::where([
            'activity_instance_id' => 8,
            'complete' => 1,
            'percentage' => 100,
        ])->firstOrFail();

        $this->assertNotEquals($progressModel1->id, $progressModel2->id);
        
        $this->assertDatabaseHas('module_instance_progress', [
            'module_instance_id' => 4,
            'progress_id' => $progressModel1->id,
            'mandatory' => 0,
            'complete' => 1,
            'percentage' => 100,
            'active' => 1,
            'visible' => 0
        ]);
        $this->assertDatabaseHas('module_instance_progress', [
            'module_instance_id' => 5,
            'progress_id' => $progressModel1->id,
            'mandatory' => 1,
            'complete' => 0,
            'percentage' => 24,
            'active' => 0,
            'visible' => 1
        ]);
        $this->assertDatabaseHas('module_instance_progress', [
            'module_instance_id' => 6,
            'progress_id' => $progressModel1->id,
            'mandatory' => 0,
            'complete' => 0,
            'percentage' => 5,
            'active' => 1,
            'visible' => 0
        ]);
        
        
        $this->assertDatabaseHas('module_instance_progress', [
            'module_instance_id' => 9,
            'progress_id' => $progressModel2->id,
            'mandatory' => 0,
            'complete' => 1,
            'percentage' => 100,
            'active' => 1,
            'visible' => 0
        ]);
        $this->assertDatabaseHas('module_instance_progress', [
            'module_instance_id' => 10,
            'progress_id' => $progressModel2->id,
            'mandatory' => 1,
            'complete' => 0,
            'percentage' => 24,
            'active' => 0,
            'visible' => 1
        ]);
        $this->assertDatabaseHas('module_instance_progress', [
            'module_instance_id' => 11,
            'progress_id' => $progressModel2->id,
            'mandatory' => 0,
            'complete' => 0,
            'percentage' => 5,
            'active' => 1,
            'visible' => 0
        ]);
    }
}
