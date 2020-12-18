<?php


namespace BristolSU\Support\Tests\Control\Settings;


use BristolSU\Support\Control\Settings\ControlCategory;
use BristolSU\Support\Tests\TestCase;

class ControlCategoryTest extends TestCase
{

    /** @test */
    public function the_key_is_set(){
        $category = new ControlCategory();
        $this->assertEquals('control', $category->key());
    }

    /** @test */
    public function a_name_is_returned()
    {
        $category = new ControlCategory();
        $this->assertIsString($category->name());
        $this->assertGreaterThanOrEqual(1, strlen($category->name()));
    }

    /** @test */
    public function a_description_is_returned(){
        $category = new ControlCategory();
        $this->assertIsString($category->description());
        $this->assertGreaterThanOrEqual(1, strlen($category->description()));
    }

}
