<?php

namespace BristolSU\Support\Settings\Saved;

class DatabaseSavedSettingRepository implements SavedSettingRepository
{

    /**
     * @inheritDoc
     */
    public function has($key): bool
    {
        return SavedSettingModel::key($key)->count() > 0;
    }

    /**
     * @inheritDoc
     */
    public function get($key, $default = null)
    {
        if($this->has($key)) {
            return SavedSettingModel::key($key)->firstOrFail()->getSettingValue();
        }
        return $default;
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        $settings = [];
        SavedSettingModel::all()->each(function(SavedSettingModel $setting) use (&$settings) {
            $settings[$setting->getSettingKey()] = $setting->getSettingValue();
        });
        return $settings;
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value = null): void
    {
        SavedSettingModel::updateOrCreate(['key' => $key], [
            'key' => $key,
            'value' => $value
        ]);
    }
}
