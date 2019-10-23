<?php

namespace BristolSU\Support\Tests\Logic;

use BristolSU\Support\Filters\Contracts\FilterRepository;
use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Logic\Contracts\AudienceFactory;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Logic\LogicAudience;
use Illuminate\Support\Collection;
use BristolSU\Support\Tests\TestCase;
use Prophecy\Argument;

class LogicAudienceTest extends TestCase
{
    
    /** @test */
    public function audience_retrieves_the_possible_audience_from_the_audience_factory(){
        $audience = $this->prophesize(AudienceFactory::class);
        $audience->for('user')->shouldBeCalled()->willReturn(new Collection);
        $filterRepository = $this->prophesize(FilterRepository::class);

        $logic = factory(Logic::class)->create(['for' => 'user']);
        $logicAudience = new LogicAudience($filterRepository->reveal(), $audience->reveal());

        $logicAudience->audience($logic);
    }

    // TODO More tests around audience here!

}
