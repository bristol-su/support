<?php

namespace BristolSU\Support\Tests\Theme\Settings;

use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\Theme\Settings\ThemeGroup;

class ThemeGroupTest extends TestCase
{

    /** @test */
    public function the_class_can_be_created(){
        $group = new ThemeGroup();
        $this->assertInstanceOf(ThemeGroup::class, $group);
    }

    /** @test */
    public function key_returns_the_key(){
        $group = new ThemeGroup();
        $this->assertEquals('appearance.theme', $group->key());
    }

    /** @test */
    public function name_returns_a_string(){
        $group = new ThemeGroup();
        $this->assertIsString($group->name());
    }

    /** @test */
    public function description_returns_a_string(){
        $group = new ThemeGroup();
        $this->assertIsString($group->description());
    }

}
