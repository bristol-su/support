<?php

namespace BristolSU\Support\Tests\Progress;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Progress\ModuleInstanceProgress;
use BristolSU\Support\Progress\Progress;
use BristolSU\Support\Progress\ProgressHashes;
use BristolSU\Support\Progress\ProgressUpdateRepository;
use BristolSU\Support\Progress\Snapshot;
use BristolSU\Support\Tests\TestCase;
use Carbon\Carbon;

class ProgressUpdateRepositoryTest extends TestCase
{
    protected ProgressUpdateRepository $repository;
    protected $activity;

    /**
     * @var ModuleInstance[] $modules
     */
    protected $modules;
    protected $activityInstance;
    protected $hashesTableName;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new ProgressUpdateRepository();

        $this->activity = factory(Activity::class)->create();
        $this->modules = factory(ModuleInstance::class, 5)->create(['activity_id' => $this->activity->id]);
        $this->activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $this->activity->id]);

        $this->hashesTableName = (new ProgressHashes)->getTable();
    }

    /** @test */
    public function saving_progress_saves_a_new_hash_correctly()
    {
        // Create a new Snapshot Instance and trigger Update to Activity Instance:
        (new Snapshot($this->repository))->ofUpdateToActivityInstance($this->activityInstance, 'caller');

        // Ensure that there is a new hash in the DB:
        $this->assertDatabaseHas($this->hashesTableName, ['item_key' => 'caller_' . $this->activityInstance->id]);
    }

    /**
     * @test
     * @dataProvider progressChangeProvider()
     */
    public function saving_progress_updates_a_hash_when_a_progress_is_changed(Progress $firstProgress, Progress $changedProgress)
    {
//        $Progress = $this->progressChangeProvider();
//        $firstProgress = $Progress[0][0];
//        $changedProgress = $Progress[0][1];

        /*
         * We need to check every thing that can change to make sure it works. Use a data provider for this
         */
        $this->repository->saveChanges($this->activityInstance->id, 'some_caller',  $firstProgress);

        dd(
            $firstProgress,
            $changedProgress,
            $this->repository->hasChanged($this->activityInstance->id, 'some_caller', $changedProgress)
        );

        $this->assertTrue(
            true
        );

    }

    public function progressChangeProvider(): array
    {
        /*
         * Add one of these for every thing that can be changed for the progress and both module instance progresses
         */
        return [
            [
                $this->createProgress(),
                $this->createProgress(function (Progress $progress): Progress {
                    $progress->setActivityId(factory(Activity::class)->create()->id);
                    return $progress;
                }),
            ],
            [
                $this->createProgress(),
                $this->createProgress(function (Progress $progress): Progress {
                    $progress->setActivityInstanceId(factory(Activity::class)->create()->id);
                    return $progress;
                }),
            ]
        ];
    }

    public function createProgress(\Closure $callback = null): Progress
    {
        $progress = Progress::create(
            factory(Activity::class)->create()->id,
            $this->activityInstance->id,
            Carbon::now(),
            true,
            80
        );
        $moduleProgress1 = ModuleInstanceProgress::create(
            $this->modules[0]->id,
            true, true, 100, true, true
        );
        $moduleProgress2 = ModuleInstanceProgress::create(
            $this->modules[0]->id,
            true, true, 100, true, true
        );
        $progress->pushModule($moduleProgress1);
        $progress->pushModule($moduleProgress2);

        if($callback === null) {
            $callback = fn(Progress $progress): Progress => $progress;
        }

        return $callback($progress);
    }

    /** @test */
    public function hasChanged_returns_false_if_progress_has_not_changed_since_last_save()
    {
        // Create a new Snapshot:
        (new Snapshot($this->repository))->ofUpdateToActivityInstance($this->activityInstance, 'caller');

//        $this->repository->hasChanged($this->activityInstance)

    }

    /** @test */
    public function hasChanged_returns_true_if_progress_has_changed_since_last_save()
    {

    }

    /** @test */
    public function hasChanged_returns_false_if_the_progress_is_not_saved(){

    }
}