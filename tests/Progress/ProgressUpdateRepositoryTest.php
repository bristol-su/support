<?php

namespace BristolSU\Support\Tests\Progress;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Progress\ModuleInstanceProgress;
use BristolSU\Support\Progress\Progress;
use BristolSU\Support\Progress\ProgressHashes;
use BristolSU\Support\Progress\ProgressUpdateRepository;
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

        $this->hashesTableName = (new ProgressHashes())->getTable();
    }

    /** @test */
    public function saving_progress_saves_a_new_hash_correctly()
    {
        $this->repository->saveChanges($this->activityInstance->id, 'caller', $this->createProgress());

        // Ensure that there is a new hash in the DB:
        $this->assertDatabaseHas($this->hashesTableName, ['item_key' => 'caller_' . $this->activityInstance->id]);
    }

    /**
     * @test
     */
    public function saving_progress_updates_a_hash_when_a_progress_is_changed()
    {
        foreach ($this->progressChangeProvider() as $case => $arguments) {
            [$firstProgress, $changedProgress] = $arguments[0];

            $this->repository->saveChanges($this->activityInstance->id, 'some_caller', $firstProgress);

            $this->assertTrue(
                $this->repository->hasChanged($this->activityInstance->id, 'some_caller', $changedProgress),
                sprintf('Test case %s expected hasChanged to be true', $case)
            );
        }
    }

    public function progressChangeProvider(): array
    {
        return [
            'Return two set of Progresses' => [
                [
                    $this->createProgress(),
                    $this->createProgress(function (Progress $progress): Progress {
                        $progress->setActivityId(factory(Activity::class)->create()->id);
                        $progress->setActivityInstanceId(factory(ActivityInstance::class)->create()->id);

                        return $progress;
                    })
                ]
            ],
            'Return two set of Activities with different percentages' => [
                [
                    $this->createProgress(),
                    $this->createProgress(function (Progress $progress): Progress {
                        $progress->setActivityId(factory(Activity::class)->create()->id);
                        $progress->setPercentage(50);

                        return $progress;
                    })
                ]
            ],
            'Return two set of Activities with different complete statuses' => [
                [
                    $this->createProgress(),
                    $this->createProgress(function (Progress $progress): Progress {
                        $progress->setActivityId(factory(Activity::class)->create()->id);
                        $progress->setComplete(false);

                        return $progress;
                    })
                ]
            ],
            'Return two set of Progresses with Module Mandatory changed' => [
                [
                    $this->createProgress(),
                    $this->createProgress(function (Progress $progress): Progress {
                        $progress->setActivityId(factory(Activity::class)->create()->id);
                        $progress->getModules()[0]->setMandatory(false);

                        return $progress;
                    })
                ]
            ],
            'Return two set of Progresses with Module Complete changed' => [
                [
                    $this->createProgress(),
                    $this->createProgress(function (Progress $progress): Progress {
                        $progress->setActivityId(factory(Activity::class)->create()->id);
                        $progress->getModules()[0]->setComplete(false);

                        return $progress;
                    })
                ]
            ],
            'Return two set of Progresses with Module Percentage changed' => [
                [
                    $this->createProgress(),
                    $this->createProgress(function (Progress $progress): Progress {
                        $progress->setActivityId(factory(Activity::class)->create()->id);
                        $progress->getModules()[0]->setPercentage(10);

                        return $progress;
                    })
                ]
            ],
            'Return two set of Progresses with Module Active changed' => [
                [
                    $this->createProgress(),
                    $this->createProgress(function (Progress $progress): Progress {
                        $progress->setActivityId(factory(Activity::class)->create()->id);
                        $progress->getModules()[0]->setActive(false);

                        return $progress;
                    })
                ]
            ],
            'Return two set of Progresses with Module Visibility changed' => [
                [
                    $this->createProgress(),
                    $this->createProgress(function (Progress $progress): Progress {
                        $progress->setActivityId(factory(Activity::class)->create()->id);
                        $progress->getModules()[0]->setVisible(false);

                        return $progress;
                    })
                ]
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
            true,
            true,
            100,
            true,
            true
        );
        $moduleProgress2 = ModuleInstanceProgress::create(
            $modules[1]->id,
            true,
            true,
            100,
            true,
            true
        );
        $progress->pushModule($moduleProgress1);
        $progress->pushModule($moduleProgress2);

        if ($callback === null) {
            $callback = fn(Progress $progress): Progress => $progress;
        }

        return $callback($progress);
    }

    /** @test */
    public function has_changed_returns_false_if_progress_has_not_changed_since_last_save()
    {
        $progress = $this->createProgress();
        // Ensure that first pass returns true:
        $this->assertTrue($this->repository->hasChanged($this->activityInstance->id, 'caller', $progress));
        // Trigger Save of Progress:
        $this->repository->saveChanges($this->activityInstance->id, 'caller', $progress);
        // Ensure that second pass is false as no changes
        $this->assertFalse($this->repository->hasChanged($this->activityInstance->id, 'caller', $progress));
    }

    /** @test */
    public function has_changed_returns_true_if_progress_has_changed_since_last_save()
    {
        $progress = $this->createProgress();

        $this->assertTrue($this->repository->hasChanged($this->activityInstance->id, 'caller', $progress));

        $this->repository->saveChanges($this->activityInstance->id, 'caller', $progress);

        $progress->setComplete(false);

        $this->assertTrue($this->repository->hasChanged($this->activityInstance->id, 'caller', $progress));
    }

    /** @test */
    public function has_changed_returns_true_if_the_progress_is_not_saved()
    {
        foreach ($this->progressChangeProvider() as $case => $arguments) {
            [$firstProgress, $changedProgress] = $arguments[0];

            $this->assertTrue(
                $this->repository->hasChanged(
                    $this->activity->id,
                    'caller',
                    $firstProgress
                ),
                sprintf('Test case %s expected hasChanged to be true', $case)
            );
        }
    }
}
