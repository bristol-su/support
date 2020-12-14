<?php

namespace BristolSU\Support\Tests\Settings\Saved;

use BristolSU\Support\Settings\Saved\DatabaseSavedSettingRepository;
use BristolSU\Support\Settings\Saved\SavedSettingModel;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DatabaseSavedSettingRepositoryTest extends TestCase
{

    /** @test */
    public function hasGlobal_returns_true_if_the_global_setting_exists(){
        $setting = factory(SavedSettingModel::class)->create([
            'visibility' => 'global', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $this->assertTrue(
            $repo->hasGlobal('mykey1')
        );
    }

    /** @test */
    public function hasGlobal_returns_false_if_the_global_setting_does_not_exist(){
        $repo = new DatabaseSavedSettingRepository();
        $this->assertFalse(
            $repo->hasGlobal('mykey1')
        );
    }

    /** @test */
    public function getGlobalValue_returns_the_value_of_the_setting(){
        $setting = factory(SavedSettingModel::class)->create([
            'visibility' => 'global', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $this->assertEquals(
            'testvalue1', $repo->getGlobalValue('mykey1')
        );
    }

    /** @test */
    public function getGlobalValue_throws_a_model_not_found_exception_if_the_setting_does_not_exist(){
        $this->expectException(ModelNotFoundException::class);

        $repo = new DatabaseSavedSettingRepository();
        $repo->getGlobalValue('mykey1');
    }

    /** @test */
    public function hasUser_returns_false_if_a_user_setting_does_not_exist(){
        $user = $this->newUser();
        $repo = new DatabaseSavedSettingRepository();
        $this->assertFalse(
            $repo->hasUser('mykey1', $user->id())
        );
    }

    /** @test */
    public function hasUser_returns_true_if_a_user_setting_exists(){
        $user = $this->newUser();
        $setting = factory(SavedSettingModel::class)->create([
            'visibility' => 'user', 'user_id' => $user->id(), 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $this->assertTrue(
            $repo->hasUser('mykey1', $user->id())
        );
    }

    /** @test */
    public function hasUser_returns_true_if_a_user_default_setting_exists(){
        $user = $this->newUser();
        $setting = factory(SavedSettingModel::class)->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $this->assertTrue(
            $repo->hasUser('mykey1', $user->id())
        );
    }

    /** @test */
    public function hasUser_returns_true_if_a_user_default_setting_and_user_setting_exists(){
        $user = $this->newUser();
        $setting = factory(SavedSettingModel::class)->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);
        $setting = factory(SavedSettingModel::class)->create([
            'visibility' => 'user', 'user_id' => $user->id(), 'key' => 'mykey1', 'value' => 'testvalue12'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $this->assertTrue(
            $repo->hasUser('mykey1', $user->id())
        );
    }

    /** @test */
    public function getUserValue_returns_the_user_value_if_user_setting_exists(){
        $user = $this->newUser();
        $setting = factory(SavedSettingModel::class)->create([
            'visibility' => 'user', 'user_id' => $user->id(), 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $this->assertEquals(
            'testvalue1', $repo->getUserValue('mykey1', $user->id())
        );
    }

    /** @test */
    public function getUserValue_returns_the_user_default_value_if_user_default_setting_exists(){
        $user = $this->newUser();
        $setting = factory(SavedSettingModel::class)->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue2'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $this->assertEquals(
            'testvalue2', $repo->getUserValue('mykey1', $user->id())
        );
    }

    /** @test */
    public function getUserValue_returns_the_user_value_if_both_user_and_user_default_settings_exist(){
        $user = $this->newUser();
        $setting = factory(SavedSettingModel::class)->create([
            'visibility' => 'user', 'user_id' => $user->id(), 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);
        $defaultSetting = factory(SavedSettingModel::class)->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue2'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $this->assertEquals(
            'testvalue1', $repo->getUserValue('mykey1', $user->id())
        );
    }

    /** @test */
    public function getUserValue_throws_a_model_not_found_exception_if_both_settings_missing(){
        $this->expectException(ModelNotFoundException::class);

        $user = $this->newUser();
        $repo = new DatabaseSavedSettingRepository();
        $this->assertEquals(
            'testvalue1', $repo->getUserValue('mykey1', $user->id())
        );
    }

    /** @test */
    public function setForUser_updates_a_setting_for_a_specific_user_if_the_setting_already_exists(){
        $user = $this->newUser();
        $setting = factory(SavedSettingModel::class)->create([
            'visibility' => 'user', 'user_id' => $user->id(), 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);
        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'visibility' => 'user',
            'value' => 'testvalue1'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $repo->setForUser('mykey1', 'testvalue2', $user->id());

        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'visibility' => 'user',
            'value' => 'testvalue2'
        ]);
    }

    /** @test */
    public function setForUser_creates_a_setting_for_a_specific_user_if_the_setting_does_not_exist(){
        $user = $this->newUser();
        $this->assertDatabaseMissing('settings', [
            'visibility' => 'user',
            'value' => 'testvalue1'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $repo->setForUser('mykey1', 'testvalue1', $user->id());

        $this->assertDatabaseHas('settings', [
            'visibility' => 'user',
            'value' => 'testvalue1'
        ]);
    }

    /** @test */
    public function setForAllUsers_updates_a_setting_for_a_all_users_if_the_setting_already_exists(){
        $setting = factory(SavedSettingModel::class)->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);
        $this->assertDatabaseHas('settings', [
            'visibility' => 'user',
            'user_id' => null,
            'value' => 'testvalue1'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $repo->setForAllUsers('mykey1', 'testvalue2');

        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'visibility' => 'user',
            'user_id' => null,
            'value' => 'testvalue2'
        ]);
    }

    /** @test */
    public function setForAllUsers_creates_a_setting_for_all_users_if_the_setting_does_not_exist(){
        $this->assertDatabaseMissing('settings', [
            'visibility' => 'user',
            'value' => 'testvalue1',
            'user_id' => null,
            'key' => 'mykey1'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $repo->setForAllUsers('mykey1', 'testvalue1');

        $this->assertDatabaseHas('settings', [
            'visibility' => 'user',
            'value' => 'testvalue1'
        ]);
    }

    /** @test */
    public function setForAllUsers_only_sets_the_default_setting_not_a_user_specific_setting_of_the_same_name(){
        $user = $this->newUser();
        $setting = factory(SavedSettingModel::class)->create([
            'visibility' => 'user', 'user_id' => $user->id(), 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);
        $defaultSetting = factory(SavedSettingModel::class)->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue2'
        ]);

        $this->assertDatabaseHas('settings', [
            'visibility' => 'user',
            'user_id' => $user->id(),
            'key' => 'mykey1',
            'value' => 'testvalue1'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $repo->setForAllUsers('mykey1', 'testvalue3');

        $this->assertDatabaseHas('settings', [
            'visibility' => 'user',
            'user_id' => $user->id(),
            'key' => 'mykey1',
            'value' => 'testvalue1'
        ]);
    }

    /** @test */
    public function setForUsers_only_sets_the_user_setting_not_a_default_user_setting_of_the_same_name(){
        $user = $this->newUser();
        $setting = factory(SavedSettingModel::class)->create([
            'visibility' => 'user', 'user_id' => $user->id(), 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);
        $defaultSetting = factory(SavedSettingModel::class)->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue2'
        ]);

        $this->assertDatabaseHas('settings', [
            'visibility' => 'user',
            'user_id' => null,
            'key' => 'mykey1',
            'value' => 'testvalue2'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $repo->setForUser('mykey1', 'testvalue3', $user->id());

        $this->assertDatabaseHas('settings', [
            'visibility' => 'user',
            'user_id' => null,
            'key' => 'mykey1',
            'value' => 'testvalue2'
        ]);
    }

    /** @test */
    public function setGlobal_updates_a_global_setting_if_the_setting_already_exists(){
        $setting = factory(SavedSettingModel::class)->create([
            'visibility' => 'global', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);
        $this->assertDatabaseHas('settings', [
            'visibility' => 'global',
            'user_id' => null,
            'value' => 'testvalue1'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $repo->setGlobal('mykey1', 'testvalue2');

        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'visibility' => 'global',
            'user_id' => null,
            'value' => 'testvalue2'
        ]);
    }

    /** @test */
    public function setGlobal_creates_a_global_setting_if_the_setting_does_not_exist(){
        $this->assertDatabaseMissing('settings', [
            'visibility' => 'global',
            'value' => 'testvalue1',
            'user_id' => null,
            'key' => 'abc123'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $repo->setGlobal('abc123', 'testvalue2');

        $this->assertDatabaseHas('settings', [
            'visibility' => 'global',
            'value' => 'testvalue2',
            'user_id' => null,
            'key' => 'abc123'
        ]);
    }

    /** @test */
    public function hasUserDefault_returns_true_if_a_user_default_setting_exists()
    {
        $setting = factory(SavedSettingModel::class)->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue2'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $this->assertTrue(
            $repo->hasUserDefault('mykey1')
        );
    }

    /** @test */
    public function hasUserDefault_returns_false_if_a_user_default_setting_does_not_exist()
    {
        $repo = new DatabaseSavedSettingRepository();
        $this->assertFalse(
            $repo->hasUserDefault('mykey1')
        );
    }

    /** @test */
    public function getUserDefault_returns_the_value_of_the_setting(){
        $setting = factory(SavedSettingModel::class)->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue2'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $this->assertEquals(
            'testvalue2', $repo->getUserDefault('mykey1')
        );
    }

    /** @test */
    public function getUserDefault_throws_a_model_not_found_exception_if_the_setting_does_not_exist(){
        $this->expectException(ModelNotFoundException::class);

        $repo = new DatabaseSavedSettingRepository();
        $repo->getUserDefault('mykey1');
    }

    /** @test */
    public function getAllUserDefaults_returns_an_array_of_default_setting_keys_and_values(){
        factory(SavedSettingModel::class)->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey1', 'value' => 'testvalue1'
        ]);
        factory(SavedSettingModel::class)->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey2', 'value' => 'testvalue2'
        ]);
        factory(SavedSettingModel::class)->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey3', 'value' => 'testvalue3'
        ]);
        factory(SavedSettingModel::class)->create([
            'visibility' => 'user', 'user_id' => null, 'key' => 'mykey4', 'value' => 'testvalue4'
        ]);

        $repo = new DatabaseSavedSettingRepository();
        $this->assertEquals([
            'mykey1' => 'testvalue1',
            'mykey2' => 'testvalue2',
            'mykey3' => 'testvalue3',
            'mykey4' => 'testvalue4'
        ], $repo->getAllUserDefaults());
    }

    /** @test */
    public function getAllUserDefaults_returns_an_empty_array_if_no_default_settings_in_database(){
        $repo = new DatabaseSavedSettingRepository();
        $this->assertEquals([], $repo->getAllUserDefaults());
    }

}
