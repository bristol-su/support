<?php

namespace BristolSU\Support\Settings;

use BristolSU\Support\Settings\Definition\SettingStore;
use BristolSU\Support\Settings\Saved\DatabaseSavedSettingRepository;
use BristolSU\Support\Settings\Saved\SavedSettingRepository;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(SettingStore::class);
        $this->app->bind(SavedSettingRepository::class, DatabaseSavedSettingRepository::class);
        $this->app->bind(SettingRepository::class, Setting::class);
    }

}
