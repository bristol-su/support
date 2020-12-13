<?php

namespace BristolSU\Support\Tests\Settings;

use BristolSU\Support\Settings\Definition\Definition;
use BristolSU\Support\Settings\Definition\SettingStore;
use BristolSU\Support\Settings\Saved\SavedSettingRepository;
use BristolSU\Support\Settings\Setting;
use BristolSU\Support\Tests\TestCase;

class SettingTest extends TestCase
{

    /** @test */
    public function all_returns_all_default_values_for_all_registered_settings_if_no_overrides_in_db(){
        $SettingStore = $this->prophesize(SettingStore::class);
        DummySetting1::setKey('key1');
        DummySetting1::setDefaultValue('val1');
        DummySetting2::setKey('key2');
        DummySetting2::setDefaultValue('val2');
        DummySetting3::setKey('key3');
        DummySetting3::setDefaultValue('val3');
        DummySetting4::setKey('key4');
        DummySetting4::setDefaultValue('val4');
        $SettingStore->all()->willReturn([
          'DummyCat' => [
            'DummyGroup' => [DummySetting1::class, DummySetting2::class],
            'DummyGroup2' => [DummySetting3::class]
          ],
          'DummyCat2' => [
            'DummyGroup3' => [DummySetting4::class]
          ]
        ]);

        $savedSettingRepository = $this->prophesize(SavedSettingRepository::class);
        $savedSettingRepository->all()->willReturn([]);

        $setting = new Setting($SettingStore->reveal(), $savedSettingRepository->reveal());
        $this->assertEquals([
          'key1' => 'val1',
          'key2' => 'val2',
          'key3' => 'val3',
          'key4' => 'val4'
        ], $setting->all());
    }

    /** @test */
    public function all_overrides_any_extra_values_from_db(){
        $SettingStore = $this->prophesize(SettingStore::class);
        DummySetting1::setKey('key1');
        DummySetting1::setDefaultValue('val1');
        DummySetting2::setKey('key2');
        DummySetting2::setDefaultValue('val2');
        DummySetting3::setKey('key3');
        DummySetting3::setDefaultValue('val3');
        DummySetting4::setKey('key4');
        DummySetting4::setDefaultValue('val4');
        $SettingStore->all()->willReturn([
          'DummyCat' => [
            'DummyGroup' => [DummySetting1::class, DummySetting2::class],
            'DummyGroup2' => [DummySetting3::class]
          ],
          'DummyCat2' => [
            'DummyGroup3' => [DummySetting4::class]
          ]
        ]);

        $savedSettingRepository = $this->prophesize(SavedSettingRepository::class);
        $savedSettingRepository->all()->willReturn(['key1' => 'val1-new', 'key3' => 'val3-new', 'key4' => 'val4-new']);

        $setting = new Setting($SettingStore->reveal(), $savedSettingRepository->reveal());
        $this->assertEquals([
          'key1' => 'val1-new',
          'key2' => 'val2',
          'key3' => 'val3-new',
          'key4' => 'val4-new'
        ], $setting->all());
    }

    /** @test */
    public function all_does_not_include_any_overrides_without_a_definition(){
        $SettingStore = $this->prophesize(SettingStore::class);
        DummySetting1::setKey('key1');
        DummySetting1::setDefaultValue('val1');
        DummySetting2::setKey('key2');
        DummySetting2::setDefaultValue('val2');
        $SettingStore->all()->willReturn([
          'DummyCat' => [
            'DummyGroup' => [DummySetting1::class, DummySetting2::class]
          ]
        ]);

        $savedSettingRepository = $this->prophesize(SavedSettingRepository::class);
        $savedSettingRepository->all()->willReturn(['key3' => 'val3']);

        $setting = new Setting($SettingStore->reveal(), $savedSettingRepository->reveal());
        $this->assertEquals([
          'key1' => 'val1',
          'key2' => 'val2',
        ], $setting->all());
    }

    /** @test */
    public function get_returns_the_default_value_if_does_not_exists_as_override(){
        $SettingStore = $this->prophesize(SettingStore::class);
        DummySetting1::setKey('key1');
        DummySetting1::setDefaultValue('val1');
        $SettingStore->getByKey('key1')->willReturn(DummySetting1::class);

        $savedSettingRepository = $this->prophesize(SavedSettingRepository::class);
        $savedSettingRepository->has('key1')->willReturn(false);
        $savedSettingRepository->get('key1')->shouldNotBeCalled();

        $setting = new Setting($SettingStore->reveal(), $savedSettingRepository->reveal());
        $this->assertEquals('val1', $setting->get('key1'));
    }

    /** @test */
    public function get_returns_an_overridden_value_if_exists(){
        $SettingStore = $this->prophesize(SettingStore::class);

        $savedSettingRepository = $this->prophesize(SavedSettingRepository::class);
        $savedSettingRepository->has('key1')->willReturn(true);
        $savedSettingRepository->get('key1')->willReturn('val1-changed');

        $setting = new Setting($SettingStore->reveal(), $savedSettingRepository->reveal());
        $this->assertEquals('val1-changed', $setting->get('key1'));
    }

    /** @test */
    public function set_sets_an_override_value(){
        $SettingStore = $this->prophesize(SettingStore::class);

        $savedSettingRepository = $this->prophesize(SavedSettingRepository::class);
        $savedSettingRepository->set('key1', 'val1')->shouldBeCalled();

        $setting = new Setting($SettingStore->reveal(), $savedSettingRepository->reveal());
        $setting->set('key1', 'val1');
    }


}

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
class DummySetting4 extends DummySettingBase {
    protected static $key;
    protected static $value;
}

