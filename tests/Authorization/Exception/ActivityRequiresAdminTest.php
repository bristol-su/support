<?php

namespace BristolSU\Support\Tests\Authorization\Exception;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Authorization\Exception\ActivityRequiresAdmin;
use BristolSU\Support\Tests\TestCase;

class ActivityRequiresAdminTest extends TestCase
{

    /** @test */
    public function getActivity_returns_the_activity(){
        $activity = factory(Activity::class)->create();
        $exception = new ActivityRequiresAdmin('message', 500, null, $activity);
        
        $this->assertModelEquals($activity, $exception->getActivity());
    }

    /** @test */
    public function createWithActivity_creates_the_exception_with_an_activity(){
        $activity = factory(Activity::class)->create();
        $exception = ActivityRequiresAdmin::createWithActivity($activity, 'A Message', 404);

        $this->assertModelEquals($activity, $exception->getActivity());
        $this->assertEquals(404, $exception->getCode());
        $this->assertEquals('A Message', $exception->getMessage());
    }
    
}