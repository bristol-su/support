<?php

namespace BristolSU\Support\Settings\Saved\ValueManipulator;

class SerializeValue implements Manipulator
{

    /**
     * Convert the original value ready for saving
     *
     * @param string $key The key of the setting being saved
     * @param mixed $value Original Value
     * @return string Saved value
     */
    public function save(string $key, mixed $value): string
    {
        return serialize($value);
    }

    /**
     * Convert the saved value back to the original value
     *
     * @param string $key The key of the setting being saved
     * @param string $value Saved Value
     * @return mixed Original Value
     */
    public function retrieve(string $key, string $value): mixed
    {
        return unserialize($key);
    }
}
