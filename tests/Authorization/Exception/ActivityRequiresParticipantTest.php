<?php

namespace BristolSU\Support\Tests\Authorization\Exception;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Authorization\Exception\ActivityRequiresParticipant;
use BristolSU\Support\Tests\TestCase;

class ActivityRequiresParticipantTest extends TestCase
{
    /** @test */
    public function get_activity_returns_the_activity()
    {
        $activity = Activity::factory()->create();
        $exception = new ActivityRequiresParticipant('message', 500, null, $activity);
        
        $this->assertModelEquals($activity, $exception->getActivity());
    }
    
    /** @test */
    public function create_with_activity_creates_the_exception_with_an_activity()
    {
        $activity = Activity::factory()->create();
        $exception = ActivityRequiresParticipant::createWithActivity($activity, 'A Message', 404);

        $this->assertModelEquals($activity, $exception->getActivity());
        $this->assertEquals(404, $exception->getCode());
        $this->assertEquals('A Message', $exception->getMessage());
    }
}
