<?php

namespace BristolSU\Support\Settings\Saved\ValueManipulator;

use BristolSU\Support\Settings\Definition\SettingStore;
use Illuminate\Support\Facades\Crypt;

class EncryptValue implements Manipulator
{

    public function __construct(protected Manipulator $manipulator,
                                protected SettingStore $settingStore)
    {
    }

    /**
     * Convert the original value ready for saving
     *
     * @param string $key The key of the setting being saved
     * @param mixed $value Original Value
     * @return string Saved value
     */
    public function save(string $key, mixed $value): string
    {
        $value = $this->manipulator->save($key, $value);
        return ($this->shouldEncrypt($key) ? Crypt::encrypt($value, false) : $value);    }

    /**
     * Convert the saved value back to the original value
     *
     * @param string $key The key of the setting being saved
     * @param string $value Saved Value
     * @return mixed Original Value
     */
    public function retrieve(string $key, string $value): mixed
    {
        if($this->shouldEncrypt($key)) {
            $value = Crypt::decrypt($value, false);
        }
        return $this->manipulator->retrieve($key, $value);
    }

    private function shouldEncrypt(string $key): bool
    {
        $setting = $this->settingStore->getSetting($key);
        return $setting->shouldEncrypt();
    }
}
