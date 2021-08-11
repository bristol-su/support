<?php

namespace BristolSU\Support\Settings\Saved\ValueManipulator;

use BristolSU\Support\Settings\Definition\SettingStore;
use Illuminate\Contracts\Encryption\Encrypter;

class EncryptValue implements Manipulator
{
    public function __construct(
        protected Manipulator $manipulator,
        protected SettingStore $settingStore,
        protected Encrypter $encrypter
    ) {
    }

    /**
     * Convert the original value ready for saving.
     *
     * @param string $key The key of the setting being saved
     * @param mixed $value Original Value
     * @return string Saved value
     */
    public function encode(string $key, mixed $value): string
    {
        $value = $this->manipulator->encode($key, $value);

        return ($this->shouldEncrypt($key) ? $this->encrypter->encrypt($value, false) : $value);
    }

    /**
     * Convert the saved value back to the original value.
     *
     * @param string $key The key of the setting being saved
     * @param string $value Saved Value
     * @return mixed Original Value
     */
    public function decode(string $key, string $value): mixed
    {
        if ($this->shouldEncrypt($key)) {
            $value = $this->encrypter->decrypt($value, false);
        }

        return $this->manipulator->decode($key, $value);
    }

    private function shouldEncrypt(string $key): bool
    {
        $setting = $this->settingStore->getSetting($key);

        return $setting->shouldEncrypt();
    }
}
