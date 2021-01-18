<?php

namespace BristolSU\Support\Settings;

use BristolSU\Support\Settings\Definition\SettingStore;
use BristolSU\Support\Settings\Saved\DatabaseSavedSettingRepository;
use BristolSU\Support\Settings\Saved\SavedSettingRepository;
use BristolSU\Support\Settings\Saved\ValueManipulator\EncryptValue;
use BristolSU\Support\Settings\Saved\ValueManipulator\Manipulator;
use BristolSU\Support\Settings\Saved\ValueManipulator\SerializeValue;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(SettingStore::class);
        $this->app->bind(SavedSettingRepository::class, DatabaseSavedSettingRepository::class);
        $this->app->bind(SettingRepository::class, Setting::class);
        $this->app->extend(SettingRepository::class, function($service, $app) {
            return $app->make(SettingValidation::class, [
                'repository' => $service
            ]);
        });
        $this->app->bind(Manipulator::class, SerializeValue::class);
        $this->app->extend(Manipulator::class, function($service, $app) {
            return $app->make(EncryptValue::class, [
                'manipulator' => $service
            ]);
        });
    }

}
