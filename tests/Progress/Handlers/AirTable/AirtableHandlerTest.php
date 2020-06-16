<?php

namespace BristolSU\Support\Tests\Progress\Handlers\AirTable;

use BristolSU\ControlDB\Models\DataGroup;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Progress\Handlers\AirTable\AirtableHandler;
use BristolSU\Support\Progress\Handlers\AirTable\CreateRecords;
use BristolSU\Support\Progress\ModuleInstanceProgress;
use BristolSU\Support\Progress\Progress;
use BristolSU\Support\Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;
use PHPUnit\Framework\Assert;

class AirtableHandlerTest extends TestCase
{

    /** @test */
    public function it_passes_the_apiKey_tableName_and_baseId_to_each_job(){
        Bus::fake(CreateRecords::class);

        $activity = factory(Activity::class)->create();
        $activityInstance = factory(ActivityInstance::class)->create([
            'activity_id' => $activity->id,
        ]);
        $progress = Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55);

        $handler = new AirtableHandler('myBaseId', 'myTableName', 'myApiKey');
        $handler->save($progress);

        $createRecordsReflection = new \ReflectionClass(CreateRecords::class);
        $apiKeyProperty = $createRecordsReflection->getProperty('apiKey');
        $apiKeyProperty->setAccessible(true);
        $tableNameProperty = $createRecordsReflection->getProperty('tableName');
        $tableNameProperty->setAccessible(true);
        $baseIdProperty = $createRecordsReflection->getProperty('baseId');
        $baseIdProperty->setAccessible(true);

        Bus::assertDispatched(CreateRecords::class, function(CreateRecords $job) use ($apiKeyProperty, $tableNameProperty, $baseIdProperty) {
            return $apiKeyProperty->getValue($job) === 'myApiKey'
                && $tableNameProperty->getValue($job) === 'myTableName'
                && $baseIdProperty->getValue($job) === 'myBaseId';
        });

    }

    /** @test */
    public function it_delays_each_10_record_chunks_by_2_seconds_after_the_previous(){
        Bus::fake(CreateRecords::class);

        $now = Carbon::now();
        Carbon::setTestNow($now);

        $activity = factory(Activity::class)->create();
        $activityInstance = factory(ActivityInstance::class)->create([
            'activity_id' => $activity->id,
        ]);
        $progresses = [
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),

            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),

            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),

            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
            Progress::create($activity->id, $activityInstance->id, Carbon::now(), false, 55),
        ];

        $handler = new AirtableHandler('myBaseId', 'myTableName', 'myApiKey');
        $handler->saveMany($progresses);

        Bus::assertDispatched(CreateRecords::class, function(CreateRecords $job) use ($now) {
            return $now->equalTo($job->delay);
        });
        Bus::assertDispatched(CreateRecords::class, function(CreateRecords $job) use ($now) {
            return Carbon::now()->addSeconds(2)->equalTo($job->delay);
        });
        Bus::assertDispatched(CreateRecords::class, function(CreateRecords $job) use ($now) {
            return Carbon::now()->addSeconds(4)->equalTo($job->delay);
        });
        Bus::assertDispatched(CreateRecords::class, function(CreateRecords $job) use ($now) {
            return Carbon::now()->addSeconds(6)->equalTo($job->delay);
        });
    }
    
    /** @test */
    public function it_correctly_parses_a_single_progress(){
        Bus::fake(CreateRecords::class);
        
        $now = Carbon::now();
        $dataGroup = factory(DataGroup::class)->create(['name' => 'Test Group 1']);
        $group = $this->newGroup(['data_provider_id' => $dataGroup->id()]);
        
        $activity = factory(Activity::class)->create();
        $activityInstance = factory(ActivityInstance::class)->create([
            'activity_id' => $activity->id,
            'resource_type' => 'group',
            'resource_id' => $group->id()
        ]);
        $module1 = factory(ModuleInstance::class)->create(['activity_id' => $activity->id, 'name' => 'Test Module 1']);
        $module2 = factory(ModuleInstance::class)->create(['activity_id' => $activity->id, 'name' => 'Test Module 2']);
        $module3 = factory(ModuleInstance::class)->create(['activity_id' => $activity->id, 'name' => 'Test Module 3']);
        $module4 = factory(ModuleInstance::class)->create(['activity_id' => $activity->id, 'name' => 'Test Module 4']);
        
        $progress = Progress::create($activity->id, $activityInstance->id, $now, false, 55);
        $moduleProgress1 = ModuleInstanceProgress::create($module1->id, true, true, 10, true, false);
        $moduleProgress2 = ModuleInstanceProgress::create($module2->id, true, false, 10, false, false);
        $moduleProgress3 = ModuleInstanceProgress::create($module3->id, false, false, 10, false, false);
        $moduleProgress4 = ModuleInstanceProgress::create($module4->id, false, true, 10, false, true);

        $progress->pushModule($moduleProgress1);
        $progress->pushModule($moduleProgress2);
        $progress->pushModule($moduleProgress3);
        $progress->pushModule($moduleProgress4);
        
        $handler = new AirtableHandler('myBaseId', 'myTableName', 'myApiKey');
        $handler->save($progress);
        
        $createRecordsReflection = new \ReflectionClass(CreateRecords::class);
        $dataProperty = $createRecordsReflection->getProperty('data');
        $dataProperty->setAccessible(true);
        
        Bus::assertDispatched(CreateRecords::class, function(CreateRecords $job) use ($dataProperty, $activityInstance, $activity, $group, $now) {
            $data = $dataProperty->getValue($job);
            Assert::assertArrayHasKey('typecast', $data);
            Assert::assertTrue($data['typecast']);
            Assert::assertArrayHasKey('records', $data);
            Assert::assertArrayHasKey(0, $data['records']);
            Assert::assertArrayHasKey('fields', $data['records'][0]);
            Assert::assertEquals([
                'Participant Name' => 'Test Group 1',
                'Mandatory Modules' => ['Test Module 1', 'Test Module 2'],
                'Optional Modules' => ['Test Module 3', 'Test Module 4'],
                'Complete Modules' => ['Test Module 1', 'Test Module 4'],
                'Incomplete Modules' => ['Test Module 2', 'Test Module 3'],
                'Active Modules' => ['Test Module 1'],
                'Inactive Modules' => ['Test Module 2', 'Test Module 3', 'Test Module 4'],
                'Hidden Modules' => ['Test Module 1', 'Test Module 2', 'Test Module 3'],
                'Visible Modules' => ['Test Module 4'],
                '% Complete' => 55,
                'Activity Instance ID' => $activityInstance->id,
                'Activity ID' => $activity->id,
                'Participant ID' => $group->id(),
                'Snapshot Date' => $now->format(\DateTime::ATOM)
            ], $data['records'][0]['fields']);
            return true;
        });
        
    }

    /** @test */
    public function it_correctly_parses_two_progresses(){
        Bus::fake(CreateRecords::class);

        $now = Carbon::now();
        $dataGroup1 = factory(DataGroup::class)->create(['name' => 'Test Group 1']);
        $group1 = $this->newGroup(['data_provider_id' => $dataGroup1->id()]);
        $dataGroup2 = factory(DataGroup::class)->create(['name' => 'Test Group 2']);
        $group2 = $this->newGroup(['data_provider_id' => $dataGroup2->id()]);

        $activity = factory(Activity::class)->create();
        $activityInstance1 = factory(ActivityInstance::class)->create([
            'activity_id' => $activity->id,
            'resource_type' => 'group',
            'resource_id' => $group1->id()
        ]);
        $activityInstance2 = factory(ActivityInstance::class)->create([
            'activity_id' => $activity->id,
            'resource_type' => 'group',
            'resource_id' => $group2->id()
        ]);
        
        $module1 = factory(ModuleInstance::class)->create(['activity_id' => $activity->id, 'name' => 'Test Module 1']);
        $module2 = factory(ModuleInstance::class)->create(['activity_id' => $activity->id, 'name' => 'Test Module 2']);
        $module3 = factory(ModuleInstance::class)->create(['activity_id' => $activity->id, 'name' => 'Test Module 3']);
        $module4 = factory(ModuleInstance::class)->create(['activity_id' => $activity->id, 'name' => 'Test Module 4']);

        $progress1 = Progress::create($activity->id, $activityInstance1->id, $now, false, 55);
        $moduleProgress1_1 = ModuleInstanceProgress::create($module1->id, true, true, 10, true, false);
        $moduleProgress1_2 = ModuleInstanceProgress::create($module2->id, true, false, 10, false, false);
        $moduleProgress1_3 = ModuleInstanceProgress::create($module3->id, false, false, 10, false, false);
        $moduleProgress1_4 = ModuleInstanceProgress::create($module4->id, false, true, 10, false, true);

        $progress2 = Progress::create($activity->id, $activityInstance2->id, $now, true, 55);
        $moduleProgress2_1 = ModuleInstanceProgress::create($module1->id, false, false, 10, false, true);
        $moduleProgress2_2 = ModuleInstanceProgress::create($module2->id, false, true, 10, true, true);
        $moduleProgress2_3 = ModuleInstanceProgress::create($module3->id, true, true, 10, true, true);
        $moduleProgress2_4 = ModuleInstanceProgress::create($module4->id, true, false, 10, true, false);

        
        $progress1->pushModule($moduleProgress1_1);
        $progress1->pushModule($moduleProgress1_2);
        $progress1->pushModule($moduleProgress1_3);
        $progress1->pushModule($moduleProgress1_4);
        $progress2->pushModule($moduleProgress2_1);
        $progress2->pushModule($moduleProgress2_2);
        $progress2->pushModule($moduleProgress2_3);
        $progress2->pushModule($moduleProgress2_4);

        $handler = new AirtableHandler('myBaseId', 'myTableName', 'myApiKey');
        $handler->saveMany([$progress1, $progress2]);

        $createRecordsReflection = new \ReflectionClass(CreateRecords::class);
        $dataProperty = $createRecordsReflection->getProperty('data');
        $dataProperty->setAccessible(true);

        Bus::assertDispatched(CreateRecords::class, function(CreateRecords $job) use ($dataProperty, $activityInstance1, $activityInstance2, $activity, $group1, $group2, $now) {
            $data = $dataProperty->getValue($job);
            Assert::assertArrayHasKey('typecast', $data);
            Assert::assertTrue($data['typecast']);
            Assert::assertArrayHasKey('records', $data);
            Assert::assertArrayHasKey(0, $data['records']);
            Assert::assertArrayHasKey('fields', $data['records'][0]);
            Assert::assertEquals([
                'Participant Name' => 'Test Group 1',
                'Mandatory Modules' => ['Test Module 1', 'Test Module 2'],
                'Optional Modules' => ['Test Module 3', 'Test Module 4'],
                'Complete Modules' => ['Test Module 1', 'Test Module 4'],
                'Incomplete Modules' => ['Test Module 2', 'Test Module 3'],
                'Active Modules' => ['Test Module 1'],
                'Inactive Modules' => ['Test Module 2', 'Test Module 3', 'Test Module 4'],
                'Hidden Modules' => ['Test Module 1', 'Test Module 2', 'Test Module 3'],
                'Visible Modules' => ['Test Module 4'],
                '% Complete' => 55,
                'Activity Instance ID' => $activityInstance1->id,
                'Activity ID' => $activity->id,
                'Participant ID' => $group1->id(),
                'Snapshot Date' => $now->format(\DateTime::ATOM)
            ], $data['records'][0]['fields']);

            Assert::assertArrayHasKey(1, $data['records']);
            Assert::assertArrayHasKey('fields', $data['records'][1]);
            Assert::assertEquals([
                'Participant Name' => 'Test Group 2',
                'Mandatory Modules' => ['Test Module 3', 'Test Module 4'],
                'Optional Modules' => ['Test Module 1', 'Test Module 2'],
                'Complete Modules' => ['Test Module 2', 'Test Module 3'],
                'Incomplete Modules' => ['Test Module 1', 'Test Module 4'],
                'Active Modules' => ['Test Module 2', 'Test Module 3', 'Test Module 4'],
                'Inactive Modules' => ['Test Module 1'],
                'Hidden Modules' => ['Test Module 4'],
                'Visible Modules' => ['Test Module 1', 'Test Module 2', 'Test Module 3'],
                '% Complete' => 55,
                'Activity Instance ID' => $activityInstance2->id,
                'Activity ID' => $activity->id,
                'Participant ID' => $group2->id(),
                'Snapshot Date' => $now->format(\DateTime::ATOM)
            ], $data['records'][1]['fields']);
            return true;
        });

    }
    
}