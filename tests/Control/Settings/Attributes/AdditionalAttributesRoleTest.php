<?php

namespace BristolSU\Support\Tests\Control\Settings\Attributes;

use BristolSU\Support\Control\Settings\Attributes\AdditionalAttributesRole;
use BristolSU\Support\Tests\TestCase;

class AdditionalAttributesRoleTest extends TestCase
{

    /** @test */
    public function it_has_the_right_key(){
        $this->assertEquals('Control.AdditionalAttribute.Role', AdditionalAttributesRole::key());
    }

    /** @test */
    public function it_returns_the_default_value()
    {
        $this->assertEquals([], AdditionalAttributesRole::defaultValue());
    }

}
