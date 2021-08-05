<?php

namespace BristolSU\Support\Tests\Settings\Saved;

use BristolSU\Support\Settings\Saved\DatabaseSavedSettingRepository;
use BristolSU\Support\Settings\Saved\SavedSettingModel;
use BristolSU\Support\Settings\Saved\ValueManipulator\Manipulator;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DatabaseSavedSettingRepositoryTest extends TestCase
{
    /** @test */
    public function has_global_returns_true_if_the_global_setting_exists()
    {
        $manipulator = $this->prophesize(Manipulator::class);

        $setting = SavedSettingModel::factory()->create([
            'visibility' => 'global', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $this->assertTrue(
            $repo->hasGlobal('mykey1')
        );
    }

    /** @test */
    public function has_global_returns_false_if_the_global_setting_does_not_exist()
    {
        $manipulator = $this->prophesize(Manipulator::class);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $this->assertFalse(
            $repo->hasGlobal('mykey1')
        );
    }

    /** @test */
    public function get_global_value_returns_the_value_of_the_setting_manipulated()
    {
        $manipulator = $this->prophesize(Manipulator::class);
        $manipulator->decode('mykey1', 'abc123')->shouldBeCalled()->willReturn('realvalue1');

        $setting = SavedSettingModel::factory()->create([
            'visibility' => 'global', 'user_id' => null, 'key' => 'mykey1', 'value' => 'abc123'
        ]);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $this->assertEquals(
            'realvalue1',
            $repo->getGlobalValue('mykey1')
        );
    }

    /** @test */
    public function get_global_value_throws_a_model_not_found_exception_if_the_setting_does_not_exist()
    {
        $manipulator = $this->prophesize(Manipulator::class);

        $this->expectException(ModelNotFoundException::class);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $repo->getGlobalValue('mykey1');
    }

    /** @test */
    public function has_user_returns_false_if_a_user_setting_does_not_exist()
    {
        $manipulator = $this->prophesize(Manipulator::class);

        $user = $this->newUser();
        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $this->assertFalse(
            $repo->hasUser('mykey1', $user->id())
        );
    }

    /** @test */
    public function has_user_returns_true_if_a_user_setting_exists()
    {
        $manipulator = $this->prophesize(Manipulator::class);

        $user = $this->newUser();
        $setting = SavedSettingModel::factory()->create([
            'visibility' => 'user', 'user_id' => $user->id(), 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $this->assertTrue(
            $repo->hasUser('mykey1', $user->id())
        );
    }

    /** @test */
    public function has_user_returns_true_if_a_user_default_setting_exists()
    {
        $manipulator = $this->prophesize(Manipulator::class);

        $user = $this->newUser();
        $setting = SavedSettingModel::factory()->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $this->assertTrue(
            $repo->hasUser('mykey1', $user->id())
        );
    }

    /** @test */
    public function has_user_returns_true_if_a_user_default_setting_and_user_setting_exists()
    {
        $manipulator = $this->prophesize(Manipulator::class);

        $user = $this->newUser();
        $setting = SavedSettingModel::factory()->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);
        $setting = SavedSettingModel::factory()->create([
            'visibility' => 'user', 'user_id' => $user->id(), 'key' => 'mykey1', 'value' => 'testvalue12'
        ]);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $this->assertTrue(
            $repo->hasUser('mykey1', $user->id())
        );
    }

    /** @test */
    public function get_user_value_returns_the_user_value_if_user_setting_exists()
    {
        $manipulator = $this->prophesize(Manipulator::class);
        $manipulator->decode('mykey1', 'abc123')->shouldBeCalled()->willReturn('testvalue1');

        $user = $this->newUser();
        $setting = SavedSettingModel::factory()->create([
            'visibility' => 'user', 'user_id' => $user->id(), 'key' => 'mykey1', 'value' => 'abc123'
        ]);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $this->assertEquals(
            'testvalue1',
            $repo->getUserValue('mykey1', $user->id())
        );
    }

    /** @test */
    public function get_user_value_returns_the_user_default_value_if_user_default_setting_exists()
    {
        $manipulator = $this->prophesize(Manipulator::class);
        $manipulator->decode('mykey1', 'abc123')->shouldBeCalled()->willReturn('testvalue2');

        $user = $this->newUser();
        $setting = SavedSettingModel::factory()->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey1', 'value' => 'abc123'
        ]);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $this->assertEquals(
            'testvalue2',
            $repo->getUserValue('mykey1', $user->id())
        );
    }

    /** @test */
    public function get_user_value_returns_the_user_value_if_both_user_and_user_default_settings_exist()
    {
        $manipulator = $this->prophesize(Manipulator::class);
        $manipulator->decode('mykey1', 'abc1')->shouldBeCalled()->willReturn('testvalue1');

        $user = $this->newUser();
        $setting = SavedSettingModel::factory()->create([
            'visibility' => 'user', 'user_id' => $user->id(), 'key' => 'mykey1', 'value' => 'abc1'
        ]);
        $defaultSetting = SavedSettingModel::factory()->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey1', 'value' => 'abc2'
        ]);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $this->assertEquals(
            'testvalue1',
            $repo->getUserValue('mykey1', $user->id())
        );
    }

    /** @test */
    public function get_user_value_throws_a_model_not_found_exception_if_both_settings_missing()
    {
        $manipulator = $this->prophesize(Manipulator::class);

        $this->expectException(ModelNotFoundException::class);

        $user = $this->newUser();
        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $this->assertEquals(
            'testvalue1',
            $repo->getUserValue('mykey1', $user->id())
        );
    }

    /** @test */
    public function set_for_user_updates_a_setting_for_a_specific_user_if_the_setting_already_exists()
    {
        $manipulator = $this->prophesize(Manipulator::class);
        $manipulator->encode('mykey1', 'testvalue2')->shouldBeCalled()->willReturn('abc123');

        $user = $this->newUser();
        $setting = SavedSettingModel::factory()->create([
            'visibility' => 'user', 'user_id' => $user->id(), 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);
        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'visibility' => 'user',
            'value' => 'testvalue1'
        ]);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $repo->setForUser('mykey1', 'testvalue2', $user->id());

        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'visibility' => 'user',
            'value' => 'abc123'
        ]);
    }

    /** @test */
    public function set_for_user_creates_a_setting_for_a_specific_user_if_the_setting_does_not_exist()
    {
        $manipulator = $this->prophesize(Manipulator::class);
        $manipulator->encode('mykey1', 'testvalue1')->shouldBeCalled()->willReturn('abc123');

        $user = $this->newUser();
        $this->assertDatabaseMissing('settings', [
            'visibility' => 'user',
            'value' => 'testvalue1'
        ]);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $repo->setForUser('mykey1', 'testvalue1', $user->id());

        $this->assertDatabaseHas('settings', [
            'visibility' => 'user',
            'value' => 'abc123'
        ]);
    }

    /** @test */
    public function set_for_all_users_updates_a_setting_for_a_all_users_if_the_setting_already_exists()
    {
        $manipulator = $this->prophesize(Manipulator::class);
        $manipulator->encode('mykey1', 'testvalue2')->shouldBeCalled()->willReturn('abc123');

        $setting = SavedSettingModel::factory()->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);
        $this->assertDatabaseHas('settings', [
            'visibility' => 'user',
            'user_id' => null,
            'value' => 'testvalue1'
        ]);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $repo->setForAllUsers('mykey1', 'testvalue2');

        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'visibility' => 'user',
            'user_id' => null,
            'value' => 'abc123'
        ]);
    }

    /** @test */
    public function set_for_all_users_creates_a_setting_for_all_users_if_the_setting_does_not_exist()
    {
        $manipulator = $this->prophesize(Manipulator::class);
        $manipulator->encode('mykey1', 'testvalue1')->shouldBeCalled()->willReturn('abc123');

        $this->assertDatabaseMissing('settings', [
            'visibility' => 'user',
            'value' => 'testvalue1',
            'user_id' => null,
            'key' => 'mykey1'
        ]);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $repo->setForAllUsers('mykey1', 'testvalue1');

        $this->assertDatabaseHas('settings', [
            'visibility' => 'user',
            'value' => 'abc123'
        ]);
    }

    /** @test */
    public function set_for_all_users_only_sets_the_default_setting_not_a_user_specific_setting_of_the_same_name()
    {
        $manipulator = $this->prophesize(Manipulator::class);
        $manipulator->encode('mykey1', 'testvalue3')->shouldBeCalled()->willReturn('abc123');

        $user = $this->newUser();
        $setting = SavedSettingModel::factory()->create([
            'visibility' => 'user', 'user_id' => $user->id(), 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);
        $defaultSetting = SavedSettingModel::factory()->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue2'
        ]);

        $this->assertDatabaseHas('settings', [
            'visibility' => 'user',
            'user_id' => $user->id(),
            'key' => 'mykey1',
            'value' => 'testvalue1'
        ]);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $repo->setForAllUsers('mykey1', 'testvalue3');

        $this->assertDatabaseHas('settings', [
            'visibility' => 'user',
            'user_id' => $user->id(),
            'key' => 'mykey1',
            'value' => 'testvalue1'
        ]);
    }

    /** @test */
    public function set_for_users_only_sets_the_user_setting_not_a_default_user_setting_of_the_same_name()
    {
        $manipulator = $this->prophesize(Manipulator::class);
        $manipulator->encode('mykey1', 'testvalue3')->shouldBeCalled()->willReturn('abc123');

        $user = $this->newUser();
        $setting = SavedSettingModel::factory()->create([
            'visibility' => 'user', 'user_id' => $user->id(), 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);
        $defaultSetting = SavedSettingModel::factory()->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue2'
        ]);

        $this->assertDatabaseHas('settings', [
            'visibility' => 'user',
            'user_id' => null,
            'key' => 'mykey1',
            'value' => 'testvalue2'
        ]);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $repo->setForUser('mykey1', 'testvalue3', $user->id());

        $this->assertDatabaseHas('settings', [
            'visibility' => 'user',
            'user_id' => null,
            'key' => 'mykey1',
            'value' => 'testvalue2'
        ]);
    }

    /** @test */
    public function set_global_updates_a_global_setting_if_the_setting_already_exists()
    {
        $manipulator = $this->prophesize(Manipulator::class);
        $manipulator->encode('mykey1', 'testvalue2')->shouldBeCalled()->willReturn('abc123');

        $setting = SavedSettingModel::factory()->create([
            'visibility' => 'global', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);
        $this->assertDatabaseHas('settings', [
            'visibility' => 'global',
            'user_id' => null,
            'value' => 'testvalue1'
        ]);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $repo->setGlobal('mykey1', 'testvalue2');

        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'visibility' => 'global',
            'user_id' => null,
            'value' => 'abc123'
        ]);
    }

    /** @test */
    public function set_global_creates_a_global_setting_if_the_setting_does_not_exist()
    {
        $manipulator = $this->prophesize(Manipulator::class);
        $manipulator->encode('mykey1', 'testvalue2')->shouldBeCalled()->willReturn('abc123');

        $this->assertDatabaseMissing('settings', [
            'visibility' => 'global',
            'user_id' => null,
            'key' => 'mykey1'
        ]);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $repo->setGlobal('mykey1', 'testvalue2');

        $this->assertDatabaseHas('settings', [
            'visibility' => 'global',
            'value' => 'abc123',
            'user_id' => null,
            'key' => 'mykey1'
        ]);
    }

    /** @test */
    public function has_user_default_returns_true_if_a_user_default_setting_exists()
    {
        $manipulator = $this->prophesize(Manipulator::class);

        $setting = SavedSettingModel::factory()->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue2'
        ]);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $this->assertTrue(
            $repo->hasUserDefault('mykey1')
        );
    }

    /** @test */
    public function has_user_default_returns_false_if_a_user_default_setting_does_not_exist()
    {
        $manipulator = $this->prophesize(Manipulator::class);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $this->assertFalse(
            $repo->hasUserDefault('mykey1')
        );
    }

    /** @test */
    public function get_user_default_returns_the_value_of_the_setting()
    {
        $manipulator = $this->prophesize(Manipulator::class);
        $manipulator->decode('mykey1', 'testvalue2')->shouldBeCalled()->willReturn('abc123');

        $setting = SavedSettingModel::factory()->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue2'
        ]);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $this->assertEquals(
            'abc123',
            $repo->getUserDefault('mykey1')
        );
    }

    /** @test */
    public function get_user_default_throws_a_model_not_found_exception_if_the_setting_does_not_exist()
    {
        $manipulator = $this->prophesize(Manipulator::class);

        $this->expectException(ModelNotFoundException::class);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $repo->getUserDefault('mykey1');
    }

    /** @test */
    public function get_all_user_defaults_returns_an_array_of_default_setting_keys_and_values()
    {
        $manipulator = $this->prophesize(Manipulator::class);
        $manipulator->decode('mykey1', 'testvalue1')->shouldBeCalled()->willReturn('testvalue1-decoded');
        $manipulator->decode('mykey2', 'testvalue2')->shouldBeCalled()->willReturn('testvalue2-decoded');
        $manipulator->decode('mykey3', 'testvalue3')->shouldBeCalled()->willReturn('testvalue3-decoded');
        $manipulator->decode('mykey4', 'testvalue4')->shouldBeCalled()->willReturn('testvalue4-decoded');

        SavedSettingModel::factory()->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);
        SavedSettingModel::factory()->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey2', 'value' => 'testvalue2'
        ]);
        SavedSettingModel::factory()->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey3', 'value' => 'testvalue3'
        ]);
        SavedSettingModel::factory()->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey4', 'value' => 'testvalue4'
        ]);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $this->assertEquals([
            'mykey1' => 'testvalue1-decoded',
            'mykey2' => 'testvalue2-decoded',
            'mykey3' => 'testvalue3-decoded',
            'mykey4' => 'testvalue4-decoded'
        ], $repo->getAllUserDefaults());
    }

    /** @test */
    public function get_all_user_defaults_returns_an_empty_array_if_no_default_settings_in_database()
    {
        $manipulator = $this->prophesize(Manipulator::class);

        $repo = new DatabaseSavedSettingRepository($manipulator->reveal());
        $this->assertEquals([], $repo->getAllUserDefaults());
    }
}
