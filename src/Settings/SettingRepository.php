<?php

namespace BristolSU\Support\Settings;

interface SettingRepository
{

    public function getValue(string $key);

    public function setForUser(string $key, $value, int $userId);

    public function setForAllUsers(string $key, $value);

    public function setGlobal(string $key, $value);

}
