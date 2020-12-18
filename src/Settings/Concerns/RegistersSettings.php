<?php

namespace BristolSU\Support\Settings\Concerns;

use BristolSU\Support\Settings\Definition\SettingRegistrar;

trait RegistersSettings
{

    protected function registerSettings(): SettingRegistrar
    {
        return app(SettingRegistrar::class);
    }

}
