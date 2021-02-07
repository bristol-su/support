<?php

namespace BristolSU\Support\Tests\Settings\Saved\ValueManipulator;

use BristolSU\Support\Settings\Definition\GlobalSetting;
use BristolSU\Support\Settings\Definition\SettingStore;
use BristolSU\Support\Settings\Saved\ValueManipulator\EncryptValue;
use BristolSU\Support\Settings\Saved\ValueManipulator\Manipulator;
use BristolSU\Support\Settings\Saved\ValueManipulator\SerializeValue;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Support\Facades\Crypt;

class EncryptValueTest extends TestCase
{
    public \Prophecy\Prophecy\ObjectProphecy $settingStore;

    public function setUp(): void
    {
        parent::setUp();
        $this->settingStore = $this->prophesize(SettingStore::class);
    }

    private function registerSetting(string $key, mixed $value, bool $shouldEncrypt)
    {
        $setting = $this->prophesize(GlobalSetting::class);
        $setting->shouldEncrypt()->willReturn($shouldEncrypt);
        $this->settingStore->getSetting($key)->willReturn($setting->reveal());
    }

    /** @test */
    public function encode_returns_the_result_of_the_given_manipulator_if_the_setting_should_not_be_encrypted()
    {
        $baseManipulator = $this->prophesize(Manipulator::class);
        $baseManipulator->encode('key1', 'original-value')->shouldBeCalled()->willReturn('base-encoded-value');

        $encrypter = $this->prophesize(Encrypter::class);

        $this->registerSetting('key1', 'some-random-value-doesnt-matter', false);

        $manipulator = new EncryptValue($baseManipulator->reveal(), $this->settingStore->reveal(), $encrypter->reveal());

        $this->assertEquals('base-encoded-value', $manipulator->encode('key1', 'original-value'));
    }

    /** @test */
    public function decode_returns_the_result_of_the_given_manipulator_if_the_setting_should_not_be_encrypted()
    {
        $baseManipulator = $this->prophesize(Manipulator::class);
        $baseManipulator->decode('key1', 'encoded-value')->shouldBeCalled()->willReturn('original-value');

        $encrypter = $this->prophesize(Encrypter::class);

        $this->registerSetting('key1', 'some-random-value-doesnt-matter', false);

        $manipulator = new EncryptValue($baseManipulator->reveal(), $this->settingStore->reveal(), $encrypter->reveal());

        $this->assertEquals('original-value', $manipulator->decode('key1', 'encoded-value'));
    }

    /** @test */
    public function encode_returns_the_encrypted_result_of_the_given_manipulator_if_the_setting_should_be_encrypted()
    {
        $baseManipulator = $this->prophesize(Manipulator::class);
        $baseManipulator->encode('key1', 'original-value')->shouldBeCalled()->willReturn('base-encoded-value');

        $encrypter = $this->prophesize(Encrypter::class);
        $encrypter->encrypt('base-encoded-value', false)->shouldBeCalled()->willReturn('encrypted-value');

        $this->registerSetting('key1', 'some-random-value-doesnt-matter', true);

        $manipulator = new EncryptValue($baseManipulator->reveal(), $this->settingStore->reveal(), $encrypter->reveal());

        $this->assertEquals('encrypted-value', $manipulator->encode('key1', 'original-value'));
    }

    /** @test */
    public function decode_returns_the_encrypted_result_of_the_given_manipulator_if_the_setting_should_be_encrypted()
    {
        $baseManipulator = $this->prophesize(Manipulator::class);
        $baseManipulator->decode('key1', 'base-encoded-value')->shouldBeCalled()->willReturn('original-value');

        $encrypter = $this->prophesize(Encrypter::class);
        $encrypter->decrypt('encrypted-value', false)->shouldBeCalled()->willReturn('base-encoded-value');

        $this->registerSetting('key1', 'some-random-value-doesnt-matter', true);

        $manipulator = new EncryptValue($baseManipulator->reveal(), $this->settingStore->reveal(), $encrypter->reveal());

        $this->assertEquals('original-value', $manipulator->decode('key1', 'encrypted-value'));
    }

    /** @test */
    public function it_works_with_serialize_value_manipulator()
    {
        $this->registerSetting('key1', 'some-random-value-doesnt-matter', false);
        $this->registerSetting('key2', 'some-random-value-doesnt-matter', true);
        $this->app->instance(SettingStore::class, $this->settingStore->reveal());

        $manipulator = app(EncryptValue::class, [
            'manipulator' => app(SerializeValue::class)
        ]);

        $encoded = $manipulator->encode('key1', 'original-value');
        $this->assertIsString($encoded);
        $this->assertLessThan(25, strlen($encoded));
        $decoded = $manipulator->decode('key1', $encoded);
        $this->assertIsString($decoded);
        $this->assertEquals('original-value', $decoded);

        $encoded = $manipulator->encode('key2', 'original-value');
        $this->assertIsString($encoded);
        $this->assertGreaterThan(25, strlen($encoded));
        $this->assertEquals('original-value', Crypt::decrypt($encoded));
        $decoded = $manipulator->decode('key2', $encoded);
        $this->assertIsString($decoded);
        $this->assertEquals('original-value', $decoded);

        $encoded = $manipulator->encode('key1', ['dummy' => 'original']);
        $this->assertIsString($encoded);
        $this->assertLessThan(40, strlen($encoded));
        $decoded = $manipulator->decode('key1', $encoded);
        $this->assertIsArray($decoded);
        $this->assertEquals(['dummy' => 'original'], $decoded);

        $encoded = $manipulator->encode('key2', ['dummy' => 'original']);
        $this->assertIsString($encoded);
        $this->assertGreaterThan(40, strlen($encoded));
        $this->assertEquals(['dummy' => 'original'], Crypt::decrypt($encoded));
        $decoded = $manipulator->decode('key2', $encoded);
        $this->assertIsArray($decoded);
        $this->assertEquals(['dummy' => 'original'], $decoded);
    }
}
