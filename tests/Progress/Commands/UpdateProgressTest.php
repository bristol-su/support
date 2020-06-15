<?php

namespace BristolSU\Support\Tests\Progress\Commands;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Progress\Jobs\UpdateProgress as UpdateProgressJob;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Support\Facades\Bus;

class UpdateProgressTest extends TestCase
{

    /** @test */
    public function the_job_dispatches_a_job_per_activity_with_the_database_exporter_if_no_options_given(){
        Bus::fake(UpdateProgressJob::class);
        
        $activities = factory(Activity::class, 5)->create();
        
        $this->artisan('progress:snapshot');

        Bus::assertDispatched(UpdateProgressJob::class, 5);

        $reflectionClass = new \ReflectionClass(UpdateProgressJob::class);
        $activityProperty = $reflectionClass->getProperty('activity');
        $activityProperty->setAccessible(true);
        Bus::assertDispatched(UpdateProgressJob::class, function(UpdateProgressJob $job) use ($activities, $activityProperty) {
            return $activities[0]->is($activityProperty->getValue($job))
                || $activities[1]->is($activityProperty->getValue($job))
                || $activities[2]->is($activityProperty->getValue($job))
                || $activities[3]->is($activityProperty->getValue($job))
                || $activities[4]->is($activityProperty->getValue($job));
        });

        $driverProperty = $reflectionClass->getProperty('driver');
        $driverProperty->setAccessible(true);
        Bus::assertDispatched(UpdateProgressJob::class, function(UpdateProgressJob $job) use ($driverProperty) {
            return $driverProperty->getValue($job) === 'database';
        });
        
    }
    
    /** @test */
    public function the_job_dispatches_the_activity_job_if_the_activity_id_is_given(){
        Bus::fake(UpdateProgressJob::class);

        $activities = factory(Activity::class, 5)->create();
        $activity = factory(Activity::class)->create();
        
        $this->artisan(sprintf('progress:snapshot %s', $activity->id));

        $reflectionClass = new \ReflectionClass(UpdateProgressJob::class);
        $activityProperty = $reflectionClass->getProperty('activity');
        $activityProperty->setAccessible(true);
        Bus::assertDispatched(UpdateProgressJob::class, function(UpdateProgressJob $job) use ($activity, $activityProperty) {
                return $activity->is($activityProperty->getValue($job));
        });

        $driverProperty = $reflectionClass->getProperty('driver');
        $driverProperty->setAccessible(true);
        Bus::assertDispatched(UpdateProgressJob::class, function(UpdateProgressJob $job) use ($driverProperty) {
            return $driverProperty->getValue($job) === 'database';
        });
        
    }


    /** @test */
    public function the_exporter_method_can_be_changed_using_shorthand(){
        Bus::fake(UpdateProgressJob::class);

        $activity = factory(Activity::class)->create();

        $this->artisan(sprintf('progress:snapshot %s -E new-exporter', $activity->id));

        $reflectionClass = new \ReflectionClass(UpdateProgressJob::class);
        $driverProperty = $reflectionClass->getProperty('driver');
        $driverProperty->setAccessible(true);
        Bus::assertDispatched(UpdateProgressJob::class, function(UpdateProgressJob $job) use ($driverProperty) {
            return $driverProperty->getValue($job) === 'new-exporter';
        });
    }
    
    /** @test */
    public function the_exporter_method_can_be_changed(){
        Bus::fake(UpdateProgressJob::class);

        $activity = factory(Activity::class)->create();

        $this->artisan(sprintf('progress:snapshot %s --exporter=new-exporter', $activity->id));

        $reflectionClass = new \ReflectionClass(UpdateProgressJob::class);
        $driverProperty = $reflectionClass->getProperty('driver');
        $driverProperty->setAccessible(true);
        Bus::assertDispatched(UpdateProgressJob::class, function(UpdateProgressJob $job) use ($driverProperty) {
            return $driverProperty->getValue($job) === 'new-exporter';
        });
    }
    
}