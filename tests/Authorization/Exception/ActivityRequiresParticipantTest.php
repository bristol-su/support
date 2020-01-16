<?php

namespace BristolSU\Support\Tests\Authorization\Exception;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Authorization\Exception\ActivityRequiresGroup;
use BristolSU\Support\Authorization\Exception\ActivityRequiresParticipant;
use BristolSU\Support\Tests\TestCase;

class ActivityRequiresParticipantTest extends TestCase
{

    /** @test */
    public function getActivity_returns_the_activity(){
        $activity = factory(Activity::class)->create();
        $exception = new ActivityRequiresParticipant('message', 500, null, $activity);
        
        $this->assertModelEquals($activity, $exception->getActivity());
    }
    
    /** @test */
    public function createWithActivity_creates_the_exception_with_an_activity(){
        $activity = factory(Activity::class)->create();
        $exception = ActivityRequiresParticipant::createWithActivity($activity, 'A Message', 404);

        $this->assertModelEquals($activity, $exception->getActivity());
        $this->assertEquals(404, $exception->getCode());
        $this->assertEquals('A Message', $exception->getMessage());
    }
    
}