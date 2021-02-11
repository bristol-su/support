<?php

namespace BristolSU\Support\Settings\Saved\ValueManipulator;

interface Manipulator
{
    /**
     * Convert the original value ready for saving.
     *
     * @param string $key The key of the setting being saved
     * @param mixed $value Original Value
     * @return string Saved value
     */
    public function encode(string $key, mixed $value): string;

    /**
     * Convert the saved value back to the original value.
     *
     * @param string $key The key of the setting being saved
     * @param string $value Saved Value
     * @return mixed Original Value
     */
    public function decode(string $key, string $value): mixed;
}
