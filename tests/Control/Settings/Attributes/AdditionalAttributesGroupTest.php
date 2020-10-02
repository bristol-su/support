<?php

namespace BristolSU\Support\Tests\Control\Settings\Attributes;

use BristolSU\Support\Control\Settings\Attributes\AdditionalAttributesGroup;
use BristolSU\Support\Tests\TestCase;

class AdditionalAttributesGroupTest extends TestCase
{

    /** @test */
    public function it_has_the_right_key(){
        $this->assertEquals('Control.AdditionalAttribute.Group', AdditionalAttributesGroup::key());
    }

    /** @test */
    public function it_returns_the_default_value()
    {
        $this->assertEquals([], AdditionalAttributesGroup::defaultValue());
    }

}
