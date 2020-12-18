<?php

namespace BristolSU\Support\Tests\Control\Settings\Attributes;

use BristolSU\Support\Control\Settings\Attributes\AdditionalAttributesRole;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Field;

class AdditionalAttributesRoleTest extends TestCase
{

    /** @test */
    public function it_has_the_right_key(){
        $this->assertEquals('control.data-fields.role', AdditionalAttributesRole::getKey());
    }

    /** @test */
    public function it_has_an_empty_array_as_the_default()
    {
        $this->assertEquals([], (new AdditionalAttributesRole())->defaultValue());
    }

    /** @test */
    public function it_returns_rules(){
        $this->assertIsArray((new AdditionalAttributesRole())->rules());
    }

    /** @test */
    public function it_returns_a_field()
    {
        $this->assertInstanceOf(Field::class, (new AdditionalAttributesRole())->fieldOptions());
    }
}
