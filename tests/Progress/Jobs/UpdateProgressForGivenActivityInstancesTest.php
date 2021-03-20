<?php

namespace BristolSU\Support\Tests\Progress\Jobs;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Progress\Handlers\Handler;
use BristolSU\Support\Progress\Jobs\UpdateProgressForGivenActivityInstances;
use BristolSU\Support\Progress\Progress;
use BristolSU\Support\Progress\ProgressExport;
use BristolSU\Support\Progress\Snapshot;
use BristolSU\Support\Tests\TestCase;
use Carbon\Carbon;
use Prophecy\Argument;

class UpdateProgressForGivenActivityInstancesTest extends TestCase
{
    /** @test */
    public function it_takes_snapshots_and_passes_them_to_the_driver_to_save()
    {
        $activity = factory(Activity::class)->create();

        $activityInstance1 = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);
        $activityInstance2 = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);
        $activityInstance3 = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);
        $activityInstance4 = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);

        $progress1 = Progress::create($activity->id, $activityInstance1->id, Carbon::now(), true, 100);
        $progress2 = Progress::create($activity->id, $activityInstance2->id, Carbon::now(), false, 50);
        $progress3 = Progress::create($activity->id, $activityInstance3->id, Carbon::now(), false, 10);


        $snapshot = $this->prophesize(Snapshot::class);
        $snapshot->ofUpdateToActivityInstance(Argument::that(function ($arg) use ($activityInstance1) {
            return $arg instanceof ActivityInstance && $activityInstance1->is($arg);
        }), 'fake-setup')->shouldBeCalled()->willReturn($progress1);
        $snapshot->ofUpdateToActivityInstance(Argument::that(function ($arg) use ($activityInstance2) {
            return $arg instanceof ActivityInstance && $activityInstance2->is($arg);
        }), 'fake-setup')->shouldBeCalled()->willReturn($progress2);
        $snapshot->ofUpdateToActivityInstance(Argument::that(function ($arg) use ($activityInstance3) {
            return $arg instanceof ActivityInstance && $activityInstance3->is($arg);
        }), 'fake-setup')->shouldBeCalled()->willReturn($progress3);
        $snapshot->ofUpdateToActivityInstance(Argument::that(function ($arg) use ($activityInstance4) {
            return $arg instanceof ActivityInstance && $activityInstance4->is($arg);
        }), 'fake-setup')->shouldBeCalled()->willReturn(null);

        $handler = $this->prophesize(Handler::class);
        $handler->saveMany([$progress1, $progress2, $progress3])->shouldBeCalled();

        $this->app['config']->set('support.progress.export.fake-setup', [
            'driver' => 'fake',
        ]);

        ProgressExport::extend('fake', function ($app, $config) use ($handler) {
            return $handler->reveal();
        });

        $job = new UpdateProgressForGivenActivityInstances([
            $activityInstance1, $activityInstance2, $activityInstance3, $activityInstance4
        ], 'fake-setup');

        $job->handle($snapshot->reveal());
    }
}
