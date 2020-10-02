<?php

namespace BristolSU\Support\Tests\Settings\Saved;

use BristolSU\Support\Settings\Saved\DatabaseSavedSettingRepository;
use BristolSU\Support\Settings\Saved\SavedSettingModel;
use BristolSU\Support\Tests\TestCase;

class DatabaseSavedSettingRepositoryTest extends TestCase
{

    /** @test */
    public function has_returns_true_if_the_setting_with_the_given_key_exists(){
        factory(SavedSettingModel::class)->create(['key' => 'key1']);
        factory(SavedSettingModel::class)->create(['key' => 'key2']);

        $repo = new DatabaseSavedSettingRepository();
        $this->assertTrue($repo->has('key1'));
    }

    /** @test */
    public function has_returns_false_if_the_setting_with_the_given_key_does_not_exist(){
        factory(SavedSettingModel::class)->create(['key' => 'key2']);
        factory(SavedSettingModel::class)->create(['key' => 'key3']);

        $repo = new DatabaseSavedSettingRepository();
        $this->assertFalse($repo->has('key1'));
    }

    /** @test */
    public function get_returns_the_key_if_exists(){
        factory(SavedSettingModel::class)->create(['key' => 'key1', 'value' => 'value1']);
        factory(SavedSettingModel::class)->create(['key' => 'key2', 'value' => 'value2']);

        $repo = new DatabaseSavedSettingRepository();
        $this->assertEquals('value1', $repo->get('key1'));
        $this->assertEquals('value2', $repo->get('key2'));
    }

    /** @test */
    public function get_returns_the_given_default_value_if_does_not_exist(){
        $repo = new DatabaseSavedSettingRepository();
        $this->assertEquals('default-1', $repo->get('key1', 'default-1'));
    }

    /** @test */
    public function all_returns_an_array_of_keys_and_settings(){
        factory(SavedSettingModel::class)->create(['key' => 'key1', 'value' => 'value1']);
        factory(SavedSettingModel::class)->create(['key' => 'key2', 'value' => 'value2']);
        factory(SavedSettingModel::class)->create(['key' => 'key3', 'value' => 'value3']);
        factory(SavedSettingModel::class)->create(['key' => 'key4', 'value' => 'value4']);
        factory(SavedSettingModel::class)->create(['key' => 'key5', 'value' => 'value5']);

        $repo = new DatabaseSavedSettingRepository();
        $this->assertEquals([
          'key1' => 'value1',
          'key2' => 'value2',
          'key3' => 'value3',
          'key4' => 'value4',
          'key5' => 'value5'
        ], $repo->all());
    }

    /** @test */
    public function set_updates_the_value_of_the_setting_if_exists(){
        factory(SavedSettingModel::class)->create(['key' => 'key1', 'value' => 'value1']);

        $this->assertDatabaseHas('settings', [
          'key' => 'key1', 'value' => 'value1'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $repo->set('key1', 'value1-new');

        $this->assertDatabaseHas('settings', [
          'key' => 'key1', 'value' => 'value1-new'
        ]);
        $this->assertDatabaseMissing('settings', [
          'key' => 'key1', 'value' => 'value1'
        ]);

    }

    /** @test */
    public function set_creates_the_setting_if_does_not_already_exist(){
        $this->assertDatabaseMissing('settings', [
          'key' => 'key1'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $repo->set('key1', 'value1');

        $this->assertDatabaseHas('settings', [
          'key' => 'key1', 'value' => 'value1'
        ]);
    }

}
