<?php

namespace BristolSU\Support\Tests\Settings\Facade;

use BristolSU\Support\Settings\SettingRepository;
use BristolSU\Support\Tests\TestCase;

class SettingTest extends TestCase
{

    /** @test */
    public function it_resolves_the_facade_root(){
        $setting = $this->prophesize(SettingRepository::class);
        $setting->getGlobalValue('key')->shouldBeCalled()->willReturn('value');
        $this->instance(SettingRepository::class, $setting->reveal());

        $this->assertEquals('value', \BristolSU\Support\Settings\Facade\Setting::getGlobalValue('key'));
    }

}
