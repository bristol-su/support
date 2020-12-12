<?php

namespace BristolSU\Support\Tests\Http\View;

use BristolSU\Support\Settings\SettingRepository;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\View;
use Laracasts\Utilities\JavaScript\Transformers\Transformer;
use Prophecy\Argument;

class InjectSiteSettingsTest extends TestCase
{

    /** @test */
    public function it_injects_the_site_settings(){
        $siteSettings = $this->prophesize(SettingRepository::class);
        $siteSettings->all()->shouldBeCalled()->willReturn(['test' => 'one', 'another' => 'two']);

        $config = $this->prophesize(Repository::class);
        $config->get('support.url')->willReturn('https://appurl.com');
        $config->get('support.api_url')->willReturn('https://api.appurl.com');

        $transformer = $this->prophesize(Transformer::class);
        $transformer->put(Argument::that(function($arg) {
            return array_key_exists('site_settings', $arg)
              && $arg['site_settings'] === ['test' => 'one', 'another' => 'two'];
        }))->shouldBeCalled();
        $this->instance('JavaScript', $transformer->reveal());

        (new \BristolSU\Support\Http\View\InjectSiteSettings($siteSettings->reveal(), $config->reveal()))->compose(
          $this->prophesize(View::class)->reveal()
        );
    }

    /** @test */
    public function it_injects_the_app_url(){
        $siteSettings = $this->prophesize(SettingRepository::class);
        $siteSettings->all()->willReturn([]);

        $config = $this->prophesize(Repository::class);
        $config->get('support.url')->willReturn('https://appurl.com');
        $config->get('support.api_url')->willReturn('https://api.appurl.com');

        $transformer = $this->prophesize(Transformer::class);
        $transformer->put(Argument::that(function($arg) {
            return array_key_exists('app_url', $arg)
              && $arg['app_url'] === 'https://appurl.com';
        }))->shouldBeCalled();
        $this->instance('JavaScript', $transformer->reveal());

        (new \BristolSU\Support\Http\View\InjectSiteSettings($siteSettings->reveal(), $config->reveal()))->compose(
          $this->prophesize(View::class)->reveal()
        );
    }

    /** @test */
    public function it_injects_the_api_url(){
        $siteSettings = $this->prophesize(SettingRepository::class);
        $siteSettings->all()->willReturn([]);

        $config = $this->prophesize(Repository::class);
        $config->get('support.url')->willReturn('https://appurl.com');
        $config->get('support.api_url')->willReturn('https://api.appurl.com');

        $transformer = $this->prophesize(Transformer::class);
        $transformer->put(Argument::that(function($arg) {
            return array_key_exists('api_url', $arg)
              && $arg['api_url'] === 'https://api.appurl.com';
        }))->shouldBeCalled();
        $this->instance('JavaScript', $transformer->reveal());

        (new \BristolSU\Support\Http\View\InjectSiteSettings($siteSettings->reveal(), $config->reveal()))->compose(
          $this->prophesize(View::class)->reveal()
        );
    }

}
