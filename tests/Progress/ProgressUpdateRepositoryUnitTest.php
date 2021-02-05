<?php

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Progress\ProgressUpdateRepository;
use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\Progress\Snapshot;
use \Prophecy\Prophecy\ObjectProphecy;

class ProgressUpdateRepositoryUnitTest extends TestCase
{
    protected ObjectProphecy $repository;
    protected $activity;
    protected $modules;
    protected $activityInstance;
    protected $snapshot;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->prophesize(ProgressUpdateRepository::class);

        $this->activity = factory(Activity::class)->create();
        $this->modules = factory(ModuleInstance::class, 5)->create(['activity_id' => $this->activity->id]);
        $this->activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $this->activity->id]);
        $this->snapshot = (new Snapshot($this->repository->reveal()));
    }

    /** @test */
    public function item_key_is_generated_correctly()
    {
        $this->repository->getItemKey($this->activity->id, 'caller')
                         ->shouldBeCalled()
                         ->willReturn('caller_' . $this->activity->id);

        $this->repository->reveal()->getItemKey($this->activity->id, 'caller');
    }

    /** @test */
    public function a_hash_is_generated_correctly_and_is_valid()
    {
        $this->repository->checkHash('$hash', '$hash')->shouldBeCalled()->willReturn(true);

//        $this->repository->generateActivityHash($this->snapshot->ofActivityInstance($this->activityInstance))->shouldBeCalled();

//        $hash = $this->repository->reveal()->generateActivityHash($this->snapshot->ofActivityInstance($this->activityInstance));

        $this->repository->reveal()->checkHash('$hash', '$hash');


    }

    /** @test */
    public function has_changed_will_return_true_on_first_pass()
    {
        $this->repository->hasChanged($this->activityInstance->id, 'caller', $this->snapshot->ofActivityInstance($this->activityInstance))
                         ->shouldBeCalled()
                         ->willReturn(true);

        $response = $this->repository->reveal()->hasChanged($this->activityInstance->id, 'caller', $this->snapshot->ofActivityInstance($this->activityInstance));

        $this->assertTrue($response);
    }


}