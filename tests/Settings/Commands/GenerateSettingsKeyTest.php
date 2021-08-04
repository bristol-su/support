<?php

namespace BristolSU\Support\Tests\Settings\Commands;

use BristolSU\Support\Settings\Commands\GenerateSettingsKey;
use BristolSU\Support\Settings\Definition\Category;
use BristolSU\Support\Settings\Definition\GlobalSetting;
use BristolSU\Support\Settings\Definition\Group;
use BristolSU\Support\Settings\Definition\SettingStore;
use BristolSU\Support\Settings\Definition\UserSetting;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Filesystem\Filesystem;
use Prophecy\Argument;

class GenerateSettingsKeyTest extends TestCase
{

    /** @test */
    public function it_generates_the_correct_information(){

        $setting1 = $this->prophesize(UserSetting::class);
        $setting1->key()->willReturn('cat1.group1.setting1');
        $setting2 = $this->prophesize(GlobalSetting::class);
        $setting2->key()->willReturn('cat1.group1.setting2');
        $setting3 = $this->prophesize(UserSetting::class);
        $setting3->key()->willReturn('cat1.group2.setting3');
        $setting4 = $this->prophesize(GlobalSetting::class);
        $setting4->key()->willReturn('cat2.group3.setting4');
        $setting5 = $this->prophesize(GlobalSetting::class);
        $setting5->key()->willReturn('cat2.group3.setting5');

        $group1 = $this->prophesize(Group::class);
        $group1->settings(Argument::that(fn($arg) => $arg instanceof Category && $arg->key() === 'cat1'))->willReturn([
            $setting1->reveal(), $setting2->reveal()
        ]);
        $group2 = $this->prophesize(Group::class);
        $group2->settings(Argument::that(fn($arg) => $arg instanceof Category && $arg->key() === 'cat1'))->willReturn([
            $setting3->reveal()
        ]);
        $group3 = $this->prophesize(Group::class);
        $group3->settings(Argument::that(fn($arg) => $arg instanceof Category && $arg->key() === 'cat2'))->willReturn([
            $setting4->reveal(), $setting5->reveal()
        ]);

        $cat1 = $this->prophesize(Category::class);
        $cat1->groups()->willReturn([$group1->reveal(), $group2->reveal()]);
        $cat1->key()->willReturn('cat1');
        $cat2 = $this->prophesize(Category::class);
        $cat2->groups()->willReturn([$group3->reveal()]);
        $cat2->key()->willReturn('cat2');

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getCategories()->willReturn([$cat1, $cat2]);

        $filesystem = $this->prophesize(Filesystem::class);
        $expected = sprintf('export default %s;', json_encode([
            'cat1' => [
                'group1' => [
                    'setting1' => 'cat1.group1.setting1',
                    'setting2' => 'cat1.group1.setting2',
                ],
                'group2' => [
                    'setting3' => 'cat1.group2.setting3',
                ]
            ],
            'cat2' => [
                'group3' => [
                    'setting4' => 'cat2.group3.setting4',
                    'setting5' => 'cat2.group3.setting5'
                ]
            ]
        ], JSON_PRETTY_PRINT));
        $filesystem->put(app()->resourcePath() . '/js/settings.js', $expected)->shouldBeCalled();

        $output = $this->prophesize(\Illuminate\Console\OutputStyle::class);
        $output->writeln('<info>Generated setting keys</info>');


        $command = new GenerateSettingsKey();
        $command->setOutput($output->reveal());
        $command->handle($filesystem->reveal(), $settingStore->reveal());

    }

}
