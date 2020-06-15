<?php

namespace BristolSU\Support\Tests\Progress\Jobs;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Progress\Handlers\Handler;
use BristolSU\Support\Progress\Jobs\UpdateProgress;
use BristolSU\Support\Progress\Progress;
use BristolSU\Support\Progress\ProgressExport;
use BristolSU\Support\Progress\Snapshot;
use BristolSU\Support\Tests\TestCase;
use Carbon\Carbon;
use Prophecy\Argument;

class UpdateProgressTest extends TestCase
{

    /** @test */
    public function it_takes_a_snapshot_and_passes_it_to_the_driver_to_save(){
        $activity = factory(Activity::class)->create();
        
        $progresses = [
            Progress::create($activity->id, 3, Carbon::now(), true, 100),
            Progress::create($activity->id, 3, Carbon::now(), false, 50),
            Progress::create($activity->id, 3, Carbon::now(), false, 10),
        ];
        
        $snapshot = $this->prophesize(Snapshot::class);
        $snapshot->ofActivity(Argument::that(function($arg) use ($activity) {
            return $arg instanceof Activity && $activity->is($arg);
        }))->shouldBeCalled()->willReturn($progresses);
        
        $handler = $this->prophesize(Handler::class);
        $handler->saveMany($progresses)->shouldBeCalled();
        
        $this->app['config']->set('support.progress.export.fake-setup', [
            'driver' => 'fake',
        ]);
        
        ProgressExport::extend('fake', function($app, $config) use ($handler) {
            return $handler->reveal();
        });
        
        $job = new UpdateProgress($activity, 'fake-setup');

        $job->handle($snapshot->reveal());
        
    }
    
}