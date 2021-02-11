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
     * @dataProvider progressChangeProvider
     */
    public function saving_progress_updates_a_hash_when_a_progress_is_changed(\Closure $getData)
    {
        [$firstProgress, $changedProgress] = $getData();

        $this->repository->saveChanges($this->activityInstance->id, 'some_caller',  $firstProgress);

        $this->assertTrue(
            $this->repository->hasChanged($this->activityInstance->id, 'some_caller', $changedProgress)
        );
    }

    public function progressChangeProvider(): array
    {
        return [
            'Return two set of Activities with Modules' => [
                function () {
                    return [
                        $this->createProgress(),
                        $this->createProgress(function (Progress $progress): Progress {
                            $progress->setActivityId(factory(Activity::class)->create()->id);
                            $progress->setActivityInstanceId(factory(ActivityInstance::class)->create()->id);
                            return $progress;
                        })
                    ];
                }
            ],
            'Return two set of Activities with different percentages' => [
                function () {
                    return [
                        $this->createProgress(),
                        $this->createProgress(function (Progress $progress): Progress {
                            $progress->setActivityId(factory(Activity::class)->create()->id);
                            $progress->setPercentage(50);
                            return $progress;
                        })
                    ];
                }
            ],
            'Return two set of Activities with different complete statuses' => [
                function () {
                    return [
                        $this->createProgress(),
                        $this->createProgress(function (Progress $progress): Progress {
                            $progress->setActivityId(factory(Activity::class)->create()->id);
                            $progress->isComplete(false);
                            return $progress;
                        })
                    ];
                }
            ]
        ];
    }

    public function createProgress(\Closure $callback = null): Progress
    {
        $activity = factory(Activity::class)->create();
        $modules = factory(ModuleInstance::class, 5)->create(['activity_id' => $activity->id]);

        $progress = Progress::create(
            $activity->id,
            factory(ActivityInstance::class)->create()->id,
            Carbon::now(),
            true,
            80
        );

        $moduleProgress1 = ModuleInstanceProgress::create(
            $modules[0]->id,
            true, true, 100, true, true
        );
        $moduleProgress2 = ModuleInstanceProgress::create(
            $modules[0]->id,
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
        $progress = $this->createProgress();
        // Ensure that first pass returns true:
        $this->assertTrue($this->repository->hasChanged($this->activityInstance->id, 'caller', $progress));
        // Trigger Save of Progress:
        $this->repository->saveChanges($this->activityInstance->id, 'caller', $progress);
        // Ensure that second pass is false as no changes
        $this->assertFalse($this->repository->hasChanged($this->activityInstance->id, 'caller', $progress));
    }

    /**
     *
     * @test
     */
    public function hasChanged_returns_true_if_progress_has_changed_since_last_save()
    {
        $progress = $this->createProgress();

        // Ensure that first pass returns true:
        $this->assertTrue($this->repository->hasChanged($this->activityInstance->id, 'caller', $progress));
        // Trigger Save of Progress:
        $this->repository->saveChanges($this->activityInstance->id, 'caller', $progress);

        $updatedProgress = $this->createProgress(function (Progress $progress): Progress {
            $progress->setPercentage(50);
            return $progress;
        });

        // Ensure that second pass is false as no changes
        $this->assertTrue($this->repository->hasChanged($this->activityInstance->id, 'caller', $updatedProgress));
    }

    /**
     * @dataProvider progressChangeProvider
     * @test
     */
    public function hasChanged_returns_true_if_the_progress_is_not_saved(\Closure $getData)
    {
        [$progress] = $getData();

        $this->assertTrue($this->repository->hasChanged($this->activity->id, 'caller', $progress));
    }
}