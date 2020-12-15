<?php

namespace BristolSU\Support\Tests\Settings\Facade;

use BristolSU\Support\Settings\Setting;
use BristolSU\Support\Tests\TestCase;

class SettingTest extends TestCase
{

    /** @test */
    public function it_resolves_the_facade_root(){
        $setting = $this->prophesize(Setting::class);
        $setting->getGlobalValue('key')->shouldBeCalled()->willReturn('value');
        $this->instance(Setting::class, $setting->reveal());

        $this->assertEquals('value', \BristolSU\Support\Settings\Facade\Setting::getGlobalValue('key'));
    }

}
