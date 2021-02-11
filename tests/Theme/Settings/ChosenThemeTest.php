<?php

namespace BristolSU\Support\Tests\Theme\Settings;

use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\Theme\Settings\ChosenTheme;
use FormSchema\Fields\SelectField;
use Twigger\Blade\Foundation\ThemeDefinition;
use Twigger\Blade\Foundation\ThemeStore;

class ChosenThemeTest extends TestCase
{
    /** @test */
    public function the_class_can_be_created()
    {
        $themeStore = $this->prophesize(ThemeStore::class);
        $setting = new ChosenTheme($themeStore->reveal());
        $this->assertInstanceOf(ChosenTheme::class, $setting);
    }

    /** @test */
    public function key_returns_the_key()
    {
        $themeStore = $this->prophesize(ThemeStore::class);
        $setting = new ChosenTheme($themeStore->reveal());
        $this->assertEquals('appearance.theme.chosen-theme', $setting->key());
    }

    /** @test */
    public function validation_fails_if_the_theme_is_not_registered()
    {
        $themeStore = $this->prophesize(ThemeStore::class);
        $themeStore->hasTheme('bootstrap-test')->willReturn(false);
        $setting = new ChosenTheme($themeStore->reveal());

        $validator = $setting->validator('bootstrap-test');
        $this->assertTrue($validator->fails());
    }

    /** @test */
    public function validation_passes_if_the_theme_is_registered()
    {
        $themeStore = $this->prophesize(ThemeStore::class);
        $themeStore->hasTheme('bootstrap-test')->willReturn(true);
        $setting = new ChosenTheme($themeStore->reveal());

        $validator = $setting->validator('bootstrap-test');
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function validation_fails_if_the_theme_is_null()
    {
        $themeStore = $this->prophesize(ThemeStore::class);
        $setting = new ChosenTheme($themeStore->reveal());

        $validator = $setting->validator(null);
        $this->assertTrue($validator->fails());
    }

    /** @test */
    public function default_value_returns_the_first_theme_in_the_store()
    {
        $theme1 = $this->prophesize(ThemeDefinition::class);
        $theme1->id()->willReturn('bootstrap-test-1');
        $theme2 = $this->prophesize(ThemeDefinition::class);
        $theme3 = $this->prophesize(ThemeDefinition::class);

        $themeStore = $this->prophesize(ThemeStore::class);
        $themeStore->allThemes()->willReturn([$theme1->reveal(), $theme2->reveal(), $theme3->reveal()]);
        $setting = new ChosenTheme($themeStore->reveal());

        $this->assertEquals('bootstrap-test-1', $setting->defaultValue());
    }

    /** @test */
    public function default_value_throws_an_exception_if_no_themes_are_registered()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No theme has been registered');

        $themeStore = $this->prophesize(ThemeStore::class);
        $themeStore->allThemes()->willReturn([]);
        $setting = new ChosenTheme($themeStore->reveal());

        $setting->defaultValue();
    }

    /** @test */
    public function field_options_returns_a_field_schema()
    {
        $theme1 = $this->prophesize(ThemeDefinition::class);
        $theme1->id()->willReturn('test');
        $theme1->name()->willReturn('Test1');

        $themeStore = $this->prophesize(ThemeStore::class);
        $themeStore->allThemes()->willReturn([$theme1->reveal()]);
        $setting = new ChosenTheme($themeStore->reveal());

        $field = $setting->fieldOptions();
        $this->assertInstanceOf(SelectField::class, $field);
    }

    /** @test */
    public function the_resulting_select_option_has_all_themes_as_options()
    {
        $theme1 = $this->prophesize(ThemeDefinition::class);
        $theme1->id()->willReturn('bootstrap-test-1');
        $theme1->name()->willReturn('BootStrap Test 1');
        $theme2 = $this->prophesize(ThemeDefinition::class);
        $theme2->id()->willReturn('bootstrap-test-2');
        $theme2->name()->willReturn('BootStrap Test 2');
        $theme3 = $this->prophesize(ThemeDefinition::class);
        $theme3->id()->willReturn('bootstrap-test-3');
        $theme3->name()->willReturn('BootStrap Test 3');

        $themeStore = $this->prophesize(ThemeStore::class);
        $themeStore->allThemes()->willReturn([$theme1->reveal(), $theme2->reveal(), $theme3->reveal()]);
        $setting = new ChosenTheme($themeStore->reveal());

        $field = $setting->fieldOptions();
        $this->assertEquals([
            ['id' => 'bootstrap-test-1', 'name' => 'BootStrap Test 1'],
            ['id' => 'bootstrap-test-2', 'name' => 'BootStrap Test 2'],
            ['id' => 'bootstrap-test-3', 'name' => 'BootStrap Test 3'],
        ], $field->values());
    }
}
