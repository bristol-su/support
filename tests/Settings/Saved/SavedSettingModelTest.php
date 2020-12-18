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
            'type' => 'string',
            'visibility' => 'global'
        ]);

        $this->assertDatabaseHas('settings', [
            'key' => 'key1',
            'value' => 'val1',
            'type' => 'string',
            'visibility' => 'global'
        ]);
    }

    /** @test */
    public function it_can_handle_a_string()
    {
        $setting = factory(SavedSettingModel::class)->create([
            'key' => 'key1',
            'value' => 'val1'
        ]);


        $this->assertDatabaseHas('settings', [
            'key' => 'key1',
            'value' => 'val1',
            'type' => 'string'
        ]);

        $this->assertEquals('val1', $setting->value);
    }

    /** @test */
    public function it_can_handle_a_integer()
    {
        $setting = factory(SavedSettingModel::class)->create([
            'key' => 'key1',
            'value' => 2
        ]);


        $this->assertDatabaseHas('settings', [
            'key' => 'key1',
            'value' => '2',
            'type' => 'integer'
        ]);

        $this->assertEquals(2, $setting->value);

    }

    /** @test */
    public function it_can_handle_an_array()
    {
        $setting = factory(SavedSettingModel::class)->create([
            'key' => 'key1',
            'value' => ['test' => 'one']
        ]);


        $this->assertDatabaseHas('settings', [
            'key' => 'key1',
            'value' => '{"test":"one"}',
            'type' => 'array'
        ]);

        $this->assertEquals(['test' => 'one'], $setting->value);

    }

    /** @test */
    public function it_can_handle_a_boolean()
    {
        $setting1 = factory(SavedSettingModel::class)->create([
            'key' => 'key1',
            'value' => true
        ]);
        $setting2 = factory(SavedSettingModel::class)->create([
            'key' => 'key2',
            'value' => false
        ]);


        $this->assertDatabaseHas('settings', [
            'key' => 'key1',
            'value' => '1',
            'type' => 'boolean'
        ]);
        $this->assertDatabaseHas('settings', [
            'key' => 'key2',
            'value' => '0',
            'type' => 'boolean'
        ]);
        $this->assertEquals(true, $setting1->value);

        $this->assertEquals(false, $setting2->value);

    }

    /** @test */
    public function it_can_handle_a_float()
    {
        $setting = factory(SavedSettingModel::class)->create([
            'key' => 'key1',
            'value' => 0.01
        ]);


        $this->assertDatabaseHas('settings', [
            'key' => 'key1',
            'value' => '0.01',
            'type' => 'float'
        ]);

        $this->assertEquals(0.01, $setting->value);

    }

    /** @test */
    public function it_can_handle_a_null_value()
    {
        $setting = factory(SavedSettingModel::class)->create([
            'key' => 'key1',
            'value' => null
        ]);


        $this->assertDatabaseHas('settings', [
            'key' => 'key1',
            'value' => null,
            'type' => 'null'
        ]);

        $this->assertEquals(null, $setting->value);

    }

    /** @test */
    public function it_can_handle_a_serializable_object()
    {
        $object = new TestSerializableClass();

        $setting = factory(SavedSettingModel::class)->create([
            'key' => 'key1',
            'value' => $object
        ]);

        $this->assertDatabaseHas('settings', [
            'key' => 'key1',
            'value' => serialize($object),
            'type' => 'object'
        ]);

        $obj = $setting->value;
        $this->assertEquals('new', $obj->test);
    }

    /** @test */
    public function scopeKey_returns_a_model_with_the_given_key()
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
    public function scopeGlobal_only_returns_global_settings(){
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
        foreach($settings as $setting) {
            $this->assertModelEquals($globalSettings->shift(), $setting);
        }

    }

    /** @test */
    public function scopeUser_with_no_user_only_returns_user_settings_for_everyone(){
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
        foreach($settings as $setting) {
            $this->assertModelEquals($userDefaultSettings->shift(), $setting);
        }
    }

    /** @test */
    public function scopeUser_with_a_user_id_only_returns_settings_for_that_user(){
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
        foreach($settings as $setting) {
            $this->assertModelEquals($userSettings->shift(), $setting);
        }
    }

    /** @test */
    public function getSettingValue_returns_the_setting_value()
    {
        $setting = factory(SavedSettingModel::class)->create([
            'key' => 'some-key',
            'value' => 'some-value'
        ]);

        $this->assertEquals('some-value', $setting->getSettingValue());
    }

    /** @test */
    public function getSettingKey_returns_the_setting_key()
    {
        $setting = factory(SavedSettingModel::class)->create([
            'key' => 'some-key',
            'value' => 'some-value'
        ]);

        $this->assertEquals('some-key', $setting->getSettingKey());
    }

    /** @test */
    public function it_throws_an_exception_on_retrieval_of_value_if_value_is_not_a_supported_type()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Type something-else-entirely is not supported in retrieving settings');
        $setting = factory(SavedSettingModel::class)->create([
            'key' => 'some-key',
            'value' => 'some-value',
            'type' => 'something-else-entirely'
        ]);

        $setting->value;
    }

    /** @test */
    public function it_throws_an_exception_on_setting_of_a_value_if_value_is_not_a_supported_type()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Type object is not supported in saving settings');
        $setting = SavedSettingModel::create([
            'key' => 'some-key',
            'value' => function () {
                return 'hi';
            }
        ]);
    }

}

class TestSerializableClass
{
    public $test = 'no';

    public function __sleep()
    {
        return ['test'];
    }

    public function __wakeup()
    {
        $this->test = 'new';
    }
}
