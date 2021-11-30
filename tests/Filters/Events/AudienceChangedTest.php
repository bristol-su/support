<?php

namespace BristolSU\Support\Tests\Filters\Events;

use BristolSU\Support\Filters\Events\AudienceChanged;
use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Database\Eloquent\Model;

class AudienceChangedTest extends TestCase
{

    /** @test */
    public function it_can_be_created_and_data_retrieved(){

        $filterInstances = Model::withoutEvents(fn() => FilterInstance::factory()->count(10)->create()->all());

        $user = $this->newUser();

        $event = new AudienceChanged($filterInstances, $user);
        $this->assertEquals($filterInstances, $event->filterInstances);
        $this->assertEquals($user, $event->model);
    }

}
