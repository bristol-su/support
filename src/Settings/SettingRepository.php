<?php

namespace BristolSU\Support\Settings;

interface SettingRepository
{

    public function all(): array;

    public function get(string $key);

    public function set(string $key, $value = null): void;

}
