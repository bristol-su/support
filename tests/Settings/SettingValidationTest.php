<?php

namespace BristolSU\Support\Tests\Settings;

use BristolSU\Support\Settings\Definition\SettingStore;
use BristolSU\Support\Settings\Definition\UserSetting;
use BristolSU\Support\Settings\SettingRepository;
use BristolSU\Support\Settings\SettingValidation;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class SettingValidationTest extends TestCase
{

    /** @test */
    public function getUserValue_calls_the_underlying_repository(){
        $repository = $this->prophesize(SettingRepository::class);
        $repository->getUserValue('key', 1)->shouldBeCalled()->willReturn('val1');

        $settingStore = $this->prophesize(SettingStore::class);

        $validation = new SettingValidation($repository->reveal(), $settingStore->reveal());
        $this->assertEquals('val1', $validation->getUserValue('key', 1));
    }

    /** @test */
    public function getGlobalValue_calls_the_underlying_repository(){
        $repository = $this->prophesize(SettingRepository::class);
        $repository->getGlobalValue('key')->shouldBeCalled()->willReturn('val1');

        $settingStore = $this->prophesize(SettingStore::class);

        $validation = new SettingValidation($repository->reveal(), $settingStore->reveal());
        $this->assertEquals('val1', $validation->getGlobalValue('key'));
    }

    /** @test */
    public function setForUser_calls_the_underlying_repository(){
        $repository = $this->prophesize(SettingRepository::class);
        $repository->setForUser('key', 'val1', 1)->shouldBeCalled();

        $validator = $this->prophesize(Validator::class);
        $validator->validate()->shouldBeCalled()->willReturn([]);

        $setting = $this->prophesize(UserSetting::class);
        $setting->validator('val1')->shouldBeCalled()->willReturn($validator->reveal());

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getSetting('key')->willReturn($setting->reveal());

        $validation = new SettingValidation($repository->reveal(), $settingStore->reveal());
        $validation->setForUser('key', 'val1', 1);
    }

    /** @test */
    public function setForAllUsers_calls_the_underlying_repository(){
        $repository = $this->prophesize(SettingRepository::class);
        $repository->setForAllUsers('key', 'val1')->shouldBeCalled();

        $validator = $this->prophesize(Validator::class);
        $validator->validate()->shouldBeCalled()->willReturn([]);

        $setting = $this->prophesize(UserSetting::class);
        $setting->validator('val1')->shouldBeCalled()->willReturn($validator->reveal());

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getSetting('key')->willReturn($setting->reveal());

        $validation = new SettingValidation($repository->reveal(), $settingStore->reveal());
        $validation->setForAllUsers('key', 'val1');
    }

    /** @test */
    public function setGlobal_calls_the_underlying_repository(){
        $repository = $this->prophesize(SettingRepository::class);
        $repository->setGlobal('key', 'val1')->shouldBeCalled();

        $validator = $this->prophesize(Validator::class);
        $validator->validate()->shouldBeCalled()->willReturn([]);

        $setting = $this->prophesize(UserSetting::class);
        $setting->validator('val1')->shouldBeCalled()->willReturn($validator->reveal());

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getSetting('key')->willReturn($setting->reveal());

        $validation = new SettingValidation($repository->reveal(), $settingStore->reveal());
        $validation->setGlobal('key', 'val1');
    }

    /** @test */
    public function it_validates_when_setting_a_user_setting(){
        $this->expectException(ValidationException::class);

        $validator = $this->prophesize(Validator::class);
        $validator->validate()->shouldBeCalled()->willThrow(new ValidationException($validator->reveal()));

        $setting = $this->prophesize(UserSetting::class);
        $setting->validator('val1')->shouldBeCalled()->willReturn($validator->reveal());

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getSetting('key')->willReturn($setting->reveal());

        $repository = $this->prophesize(SettingRepository::class);
        $repository->setForUser('key', 'val1', 1)->shouldNotBeCalled();

        $validation = new SettingValidation($repository->reveal(), $settingStore->reveal());
        $validation->setForUser('key', 'val1', 1);
    }

    /** @test */
    public function it_validates_when_setting_a_user_default_setting(){
        $this->expectException(ValidationException::class);

        $validator = $this->prophesize(Validator::class);
        $validator->validate()->shouldBeCalled()->willThrow(new ValidationException($validator->reveal()));

        $setting = $this->prophesize(UserSetting::class);
        $setting->validator('val1')->shouldBeCalled()->willReturn($validator->reveal());

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getSetting('key')->willReturn($setting->reveal());

        $repository = $this->prophesize(SettingRepository::class);
        $repository->setForAllUsers('key', 'val1')->shouldNotBeCalled();

        $validation = new SettingValidation($repository->reveal(), $settingStore->reveal());
        $validation->setForAllUsers('key', 'val1');
    }

    /** @test */
    public function it_validates_when_setting_a_global_setting(){
        $this->expectException(ValidationException::class);

        $validator = $this->prophesize(Validator::class);
        $validator->validate()->shouldBeCalled()->willThrow(new ValidationException($validator->reveal()));

        $setting = $this->prophesize(UserSetting::class);
        $setting->validator('val1')->shouldBeCalled()->willReturn($validator->reveal());

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getSetting('key')->willReturn($setting->reveal());

        $repository = $this->prophesize(SettingRepository::class);
        $repository->setGlobal('key', 'val1')->shouldNotBeCalled();

        $validation = new SettingValidation($repository->reveal(), $settingStore->reveal());
        $validation->setGlobal('key', 'val1');
    }

    /** @test */
    public function it_extends_the_base_setting_repository(){
        $this->expectException(ValidationException::class);

        $validator = $this->prophesize(Validator::class);
        $validator->validate()->shouldBeCalled()->willThrow(new ValidationException($validator->reveal()));

        $setting = $this->prophesize(UserSetting::class);
        $setting->validator('val1')->shouldBeCalled()->willReturn($validator->reveal());

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getSetting('key')->willReturn($setting->reveal());

        $baseRepository = $this->prophesize(SettingRepository::class);
        $baseRepository->setGlobal('key', 'val1')->shouldNotBeCalled();

        $validationRepo = new SettingValidation($baseRepository->reveal(), $settingStore->reveal());
        $validationRepo->setGlobal('key', 'val1');

    }
}
