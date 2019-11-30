<?php

namespace BristolSU\Support\Tests\Authorization\Exception;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Authorization\Exception\ActivityRequiresGroup;
use BristolSU\Support\Tests\TestCase;

class ActivityRequiresGroupTest extends TestCase
{

    /** @test */
    public function getActivity_returns_the_activity(){
        $activity = factory(Activity::class)->create();
        $exception = new ActivityRequiresGroup('message', 500, null, $activity);
        
        $this->assertModelEquals($activity, $exception->getActivity());
    }
    
}