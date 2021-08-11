<?php

namespace BristolSU\Support\Tests\Authorization\Exception;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Authorization\Exception\ActivityDisabled;
use BristolSU\Support\Tests\TestCase;

class ActivityDisabledTest extends TestCase
{
    /** @test */
    public function an_activity_can_be_set_and_retrieved()
    {
        $activity = Activity::factory()->create();
        $exception = new ActivityDisabled();
        
        $exception->setActivity($activity);
        
        $this->assertModelEquals($activity, $exception->activity());
    }

    /** @test */
    public function an_activity_can_be_set_through_the_static_method_and_retrieved()
    {
        $activity = Activity::factory()->create();
        $exception = ActivityDisabled::fromActivity($activity);

        $this->assertModelEquals($activity, $exception->activity());
    }
}
