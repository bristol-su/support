<?php

namespace BristolSU\Support\Tests\Control\Settings\Attributes;

use BristolSU\Support\Control\Settings\Attributes\AdditionalAttributesPosition;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Field;

class AdditionalAttributesPositionTest extends TestCase
{

    /** @test */
    public function it_has_the_right_key(){
        $this->assertEquals('control.data-fields.position', AdditionalAttributesPosition::getKey());
    }

    /** @test */
    public function it_has_an_empty_array_as_the_default()
    {
        $this->assertEquals([], (new AdditionalAttributesPosition())->defaultValue());
    }

    /** @test */
    public function it_returns_rules(){
        $this->assertIsArray((new AdditionalAttributesPosition())->rules());
    }

    /** @test */
    public function it_returns_a_field()
    {
        $this->assertInstanceOf(Field::class, (new AdditionalAttributesPosition())->fieldOptions());
    }

}
