<?php

namespace BristolSU\Support\ModuleInstance\Events;

use BristolSU\Support\ModuleInstance\Settings\ModuleInstanceSetting;

class SettingUpdated
{

    
    /**
     * @var ModuleInstanceSetting
     */
    public $setting;

    public function __construct(ModuleInstanceSetting $setting)
    {
        $this->setting = $setting;
    }

}