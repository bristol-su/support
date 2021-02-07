<?php

namespace BristolSU\Support\Tests\Settings\Saved;

use BristolSU\Support\Settings\Saved\SavedSettingModel;
use BristolSU\Support\Tests\TestCase;

class SavedSettingModelTest extends TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $setting = factory(SavedSettingModel::class)->create([
            'key' => 'key1',
            'value' => 'val1',
            'visibility' => 'global'
        ]);

        $this->assertDatabaseHas('settings', [
            'key' => 'key1',
            'value' => 'val1',
            'visibility' => 'global'
        ]);
    }

    /** @test */
    public function scope_key_returns_a_model_with_the_given_key()
    {
        $uniqueKey = 'some.unique.key.12345abjskdhfsdafdfs-aba';
        factory(SavedSettingModel::class, 10)->create();
        $setting = factory(SavedSettingModel::class)->create([
            'key' => $uniqueKey,
            'value' => 'some-value'
        ]);
        $retrieved = SavedSettingModel::key($uniqueKey)->first();
        $this->assertNotNull($retrieved);
        $this->assertInstanceOf(SavedSettingModel::class, $retrieved);
        $this->assertModelEquals($setting, $retrieved);
    }

    /** @test */
    public function scope_global_only_returns_global_settings()
    {
        $user = $this->newUser();

        $globalSettings = factory(SavedSettingModel::class, 5)->create([
            'user_id' => null, 'visibility' => 'global'
        ]);

        $userSettings = factory(SavedSettingModel::class, 6)->create([
            'user_id' => $user->id(), 'visibility' => 'user'
        ]);

        $userDefaultSettings = factory(SavedSettingModel::class, 7)->create([
            'user_id' => null, 'visibility' => 'user'
        ]);

        $settings = SavedSettingModel::global()->get();
        $this->assertEquals(5, $settings->count());
        foreach ($settings as $setting) {
            $this->assertModelEquals($globalSettings->shift(), $setting);
        }
    }

    /** @test */
    public function scope_user_with_no_user_only_returns_user_settings_for_everyone()
    {
        $user = $this->newUser();

        $globalSettings = factory(SavedSettingModel::class, 5)->create([
            'user_id' => null, 'visibility' => 'global'
        ]);

        $userSettings = factory(SavedSettingModel::class, 6)->create([
            'user_id' => $user->id(), 'visibility' => 'user'
        ]);

        $userDefaultSettings = factory(SavedSettingModel::class, 7)->create([
            'user_id' => null, 'visibility' => 'user'
        ]);

        $settings = SavedSettingModel::user()->get();
        $this->assertEquals(7, $settings->count());
        foreach ($settings as $setting) {
            $this->assertModelEquals($userDefaultSettings->shift(), $setting);
        }
    }

    /** @test */
    public function scope_user_with_a_user_id_only_returns_settings_for_that_user()
    {
        $user = $this->newUser();

        $globalSettings = factory(SavedSettingModel::class, 5)->create([
            'user_id' => null, 'visibility' => 'global'
        ]);

        $userSettings = factory(SavedSettingModel::class, 6)->create([
            'user_id' => $user->id(), 'visibility' => 'user'
        ]);

        $userDefaultSettings = factory(SavedSettingModel::class, 7)->create([
            'user_id' => null, 'visibility' => 'user'
        ]);

        $settings = SavedSettingModel::user($user->id())->get();
        $this->assertEquals(6, $settings->count());
        foreach ($settings as $setting) {
            $this->assertModelEquals($userSettings->shift(), $setting);
        }
    }

    /** @test */
    public function get_setting_value_returns_the_setting_value()
    {
        $setting = factory(SavedSettingModel::class)->create([
            'key' => 'some-key',
            'value' => 'some-value'
        ]);

        $this->assertEquals('some-value', $setting->getSettingValue());
    }

    /** @test */
    public function get_setting_key_returns_the_setting_key()
    {
        $setting = factory(SavedSettingModel::class)->create([
            'key' => 'some-key',
            'value' => 'some-value'
        ]);

        $this->assertEquals('some-key', $setting->getSettingKey());
    }
}
