<?php

namespace BristolSU\Support\Settings\Commands;

use BristolSU\Support\Settings\Definition\SettingStore;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class GenerateSettingsKey extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a list of all setting keys';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Filesystem $filesystem, SettingStore $settingStore)
    {
        $filesystem->put(
            app()->resourcePath('js/settings.js'),
            sprintf('export default %s;', json_encode($this->getSettings($settingStore), JSON_PRETTY_PRINT))
        );

        $this->info('Generated setting keys');

        return 0;
    }

    public function getSettings(SettingStore $settingStore): array
    {
        $settings = [];
        foreach($settingStore->getCategories() as $category) {
            foreach($category->groups() as $group) {
                foreach($group->settings($category) as $setting) {
                    data_set($settings, $setting->key(), $setting->key());
                }
            }
        }
        return $settings;
    }

}
