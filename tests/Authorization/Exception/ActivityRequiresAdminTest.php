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
    
}