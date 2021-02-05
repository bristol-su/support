<?php

namespace BristolSU\Support\Tests\Progress;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Progress\ModuleInstanceProgress;
use BristolSU\Support\Progress\Progress;
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

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new ProgressUpdateRepository();

        $this->activity = factory(Activity::class)->create();
        $this->modules = factory(ModuleInstance::class, 5)->create(['activity_id' => $this->activity->id]);
        $this->activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $this->activity->id]);
    }

    /** @test */
    public function saving_progress_saves_a_new_hash_correctly()
    {
        // We actually don't need prophecy here, as the repository doesn't depend on anything. Here you can just do normal tests
        // interacting directly with the database. You also never want to mock the class you're testing, otherwise you won't
        // end up testing anything!
    }

    /**
     * @test
     * @dataProvider progressChangeProvider
     */
    public function saving_progress_updates_a_hash_when_a_progress_is_changed(Progress $firstProgress, Progress $changedProgress)
    {
        /*
         * We need to check every thing that can change to make sure it works. Use a data provider for this
         */
        $this->repository->saveChanges($this->activityInstance->id, 'some_caller', $firstProgress);

        $this->assertTrue(
            $this->repository->hasChanged($this->activityInstance->id, 'some_caller', $changedProgress)
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
            $this->activity->id,
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

    }

    /** @test */
    public function hasChanged_returns_true_if_progress_has_changed_since_last_save()
    {

    }

    /** @test */
    public function hasChanged_returns_false_if_the_progress_is_not_saved(){

    }

//    /** @test */
//    public function item_key_is_generated_correctly()
//    {
//        $this->repository->getItemKey($this->activity->id, 'caller')
//                         ->shouldBeCalled()
//                         ->willReturn('caller_' . $this->activity->id);
//
//        /*
//         * This has to be created after the above. You tell the repository what to do when getItemKey is called, then
//         * ask for a repository instance. If this line is in setup, the repository instance doesn't have any prophecies
//         * as the above line is called after creation of the repository instance.
//         */
//        $snapshot = (new Snapshot($this->repository->reveal()));
//
//        $this->repository->reveal()->getItemKey($this->activity->id, 'caller');
//    }
//
//    /** @test */
//    public function a_hash_is_generated_correctly_and_is_valid()
//    {
//        $this->repository->checkHash('$hash', '$hash')->shouldBeCalled()->willReturn(true);
//
////        $this->repository->generateActivityHash($this->snapshot->ofActivityInstance($this->activityInstance))->shouldBeCalled();
//
////        $hash = $this->repository->reveal()->generateActivityHash($this->snapshot->ofActivityInstance($this->activityInstance));
//
//        $this->repository->reveal()->checkHash('$hash', '$hash');
//
//
//    }
//
//    /** @test */
//    public function has_changed_will_return_true_on_first_pass()
//    {
//        $this->repository->hasChanged($this->activityInstance->id, 'caller', $this->snapshot->ofActivityInstance($this->activityInstance))
//                         ->shouldBeCalled()
//                         ->willReturn(true);
//
//        $response = $this->repository->reveal()->hasChanged($this->activityInstance->id, 'caller', $this->snapshot->ofActivityInstance($this->activityInstance));
//
//        $this->assertTrue($response);
//    }


}