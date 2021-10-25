<?php

namespace BristolSU\Support\Tests\Logic\Audience;

use BristolSU\Support\Logic\Audience\Audience;
use BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory;
use BristolSU\Support\Tests\TestCase;

class AudienceTest extends TestCase
{

    /** @test */
    public function it_loads_the_facade_root(){
        $root = $this->prophesize(AudienceMemberFactory::class);
        $this->app->instance(AudienceMemberFactory::class, $root->reveal());

        $this->assertEquals(
            $root->reveal(), Audience::getFacadeRoot()
        );
    }

}
