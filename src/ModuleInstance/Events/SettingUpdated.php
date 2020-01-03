<?php

namespace BristolSU\Support\ModuleInstance\Events;

use BristolSU\Support\ModuleInstance\Settings\ModuleInstanceSetting;

class SettingSaved
{

    protected $dispatchesEvents = [
        'saved' => UserSaved::class,
        'deleted' => UserDeleted::class,
    ];
    
    /**
     * @var ModuleInstanceSetting
     */
    public $setting;

    public function __construct(ModuleInstanceSetting $setting)
    {
        $this->setting = $setting;
    }

}