<?php

namespace BristolSU\Support\Tests\Control\Settings\Attributes;

use BristolSU\Support\Control\Settings\Attributes\AdditionalAttributesUser;
use BristolSU\Support\Tests\TestCase;

class AdditionalAttributesUserTest extends TestCase
{

    /** @test */
    public function it_has_the_right_key(){
        $this->assertEquals('Control.AdditionalAttribute.User', AdditionalAttributesUser::key());
    }

    /** @test */
    public function it_returns_the_default_value()
    {
        $this->assertEquals([], AdditionalAttributesUser::defaultValue());
    }

}
