<?php

namespace BristolSU\Support\Tests\ModuleInstance\Settings;

use BristolSU\Support\ModuleInstance\Contracts\Settings\ModuleSettingsStore;
use BristolSU\Support\Tests\TestCase;

class ModuleSettingsStoreTest extends TestCase
{
    /** @test */
    public function it_registers_and_retrieves_a_form_schema_against_an_alias()
    {
        $form = \FormSchema\Generator\Form::make()->getSchema();
        app(ModuleSettingsStore::class)->register('alias1', $form);
        $this->assertSame($form, app(ModuleSettingsStore::class)->get('alias1'));
    }

    /** @test */
    public function get_throws_an_exception_if_not_registered()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Settings not found for alias alias1-test');
        
        app(ModuleSettingsStore::class)->get('alias1-test');
    }
}
