<?php

namespace BristolSU\Support\Tests\Settings\Definition;

use BristolSU\Support\Settings\Definition\Category;
use BristolSU\Support\Settings\Definition\Definition;
use BristolSU\Support\Settings\Definition\SettingStore;
use BristolSU\Support\Settings\Definition\Group;
use BristolSU\Support\Tests\TestCase;

class SettingStoreTest extends TestCase
{

    /** @test */
    public function register_will_throw_an_exception_if_the_setting_is_not_an_instance_of_definition(){
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Setting definitions must extend BristolSU\Support\Settings\Definition\Definition, type [BristolSU\Support\Tests\Settings\Definition\FakeClass] given');
        $store = new SettingStore();
        $store->register(FakeClass::class, DummyCategory1::class, DummyGroup1::class);
    }

    /** @test */
    public function register_will_throw_an_exception_if_the_group_is_not_an_instance_of_group(){
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Setting definitions must extend BristolSU\Support\Settings\Definition\Group, type [BristolSU\Support\Tests\Settings\Definition\FakeClass] given');
        $store = new SettingStore();
        $store->register(DummySetting1::class, DummyCategory1::class, FakeClass::class);
    }

    /** @test */
    public function register_will_throw_an_exception_if_the_category_is_not_an_instance_of_category(){
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Setting definitions must extend BristolSU\Support\Settings\Definition\Category, type [BristolSU\Support\Tests\Settings\Definition\FakeClass] given');
        $store = new SettingStore();
        $store->register(DummySetting1::class, FakeClass::class, DummyGroup1::class);
    }

    /** @test */
    public function register_registers_a_single_setting(){
        $store = new SettingStore();
        $store->register(DummySetting1::class, DummyCategory1::class, DummyGroup1::class);
        $this->assertEquals([
          DummyCategory1::class => [
            DummyGroup1::class => [
              DummySetting1::class
            ]
        ]
        ], $store->all());
    }

    public function a_definition_is_appended_to_the_group_if_the_group_and_category_have_already_been_registered()
    {
        $store = new SettingStore();
        $store->register(DummySetting1::class, DummyCategory1::class, DummyGroup1::class);
        $store->register(DummySetting2::class, DummyCategory1::class, DummyGroup1::class);
        $this->assertEquals([
          DummyCategory1::class => [
            DummyGroup1::class => [
              DummySetting1::class,
              DummySetting2::class
            ]
          ]
        ], $store->all());
    }

    /** @test */
    public function a_definition_is_not_added_twice(){
        $store = new SettingStore();
        $store->register(DummySetting1::class, DummyCategory1::class, DummyGroup1::class);
        $store->register(DummySetting1::class, DummyCategory1::class, DummyGroup1::class);
        $this->assertEquals([
          DummyCategory1::class => [
            DummyGroup1::class => [
              DummySetting1::class
            ]
          ]
        ], $store->all());
    }

    /** @test */
    public function get_by_key_returns_the_definition_class_with_the_given_key(){
        $store = new SettingStore();
        DummySetting1::setKey('key1');
        DummySetting1::setDefaultValue('val-1');
        $store->register(DummySetting1::class, DummyCategory1::class, DummyGroup1::class);
        $this->assertEquals(DummySetting1::class, $store->getByKey('key1'));
    }

    /** @test */
    public function getByKey_throws_an_exception_if_the_key_does_not_exist(){
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Setting definition with key [key1] could not be found');

        $store = new SettingStore();
        $this->assertEquals(DummySetting1::class, $store->getByKey('key1'));
    }

}

class FakeClass {}

class BaseCategory extends Category {}

class BaseGroup extends Group {}


class DummySettingBase extends Definition {

    public static function setKey($key)
    {
        static::$key = $key;
    }

    public static function key(): string
    {
        return static::$key;
    }

    public static function setDefaultValue($value)
    {
        static::$value = $value;
    }

    public static function defaultValue()
    {
        return static::$value;
    }
}

class DummySetting1 extends DummySettingBase {
    protected static $key;
    protected static $value;
}
class DummySetting2 extends DummySettingBase {
    protected static $key;
    protected static $value;
}
class DummySetting3 extends DummySettingBase {
    protected static $key;
    protected static $value;
}
class DummyCategory1 extends BaseCategory {}
class DummyCategory2 extends BaseCategory {}
class DummyGroup1 extends BaseGroup {}

class DummyGroup2 extends BaseGroup {}
