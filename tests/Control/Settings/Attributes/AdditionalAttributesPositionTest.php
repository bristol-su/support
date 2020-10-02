<?php

namespace BristolSU\Support\Tests\Control\Settings\Attributes;

use BristolSU\Support\Control\Settings\Attributes\AdditionalAttributesPosition;
use BristolSU\Support\Tests\TestCase;

class AdditionalAttributesPositionTest extends TestCase
{

    /** @test */
    public function it_has_the_right_key(){
        $this->assertEquals('Control.AdditionalAttribute.Position', AdditionalAttributesPosition::key());
    }

    /** @test */
    public function it_returns_the_default_value()
    {
        $this->assertEquals([], AdditionalAttributesPosition::defaultValue());
    }

}
