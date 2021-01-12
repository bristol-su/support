<?php

namespace BristolSU\Support\Tests\Theme\Settings;

use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\Theme\Settings\AppearanceCategory;

class AppearanceCategoryTest extends TestCase
{

    /** @test */
    public function the_class_can_be_created(){
        $category = new AppearanceCategory();
        $this->assertInstanceOf(AppearanceCategory::class, $category);
    }

    /** @test */
    public function key_returns_the_key(){
        $category = new AppearanceCategory();
        $this->assertEquals('appearance', $category->key());
    }

    /** @test */
    public function name_returns_a_string(){
        $category = new AppearanceCategory();
        $this->assertIsString($category->name());
    }

    /** @test */
    public function description_returns_a_string(){
        $category = new AppearanceCategory();
        $this->assertIsString($category->description());
    }

}
