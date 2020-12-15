<?php


namespace BristolSU\Support\Tests\Control\Settings\Attributes;


use BristolSU\Support\Control\Settings\Attributes\AttributeGroup;
use BristolSU\Support\Tests\TestCase;

class AttributeGroupTest extends TestCase
{

    /** @test */
    public function the_key_is_set(){
        $group = new AttributeGroup();
        $this->assertEquals('control.data-fields', $group->key());
    }

    /** @test */
    public function a_name_is_returned()
    {
        $group = new AttributeGroup();
        $this->assertIsString($group->name());
        $this->assertGreaterThanOrEqual(1, strlen($group->name()));
    }

    /** @test */
    public function a_description_is_returned(){
        $group = new AttributeGroup();
        $this->assertIsString($group->description());
        $this->assertGreaterThanOrEqual(1, strlen($group->description()));
    }

}
