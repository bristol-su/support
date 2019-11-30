<?php

namespace BristolSU\Support\Tests\Authorization\Exception;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Authorization\Exception\ActivityRequiresRole;
use BristolSU\Support\Tests\TestCase;

class ActivityRequiresRoleTest extends TestCase
{

    /** @test */
    public function getActivity_returns_the_activity(){
        $activity = factory(Activity::class)->create();
        $exception = new ActivityRequiresRole('message', 500, null, $activity);
        
        $this->assertModelEquals($activity, $exception->getActivity());
    }
    
}