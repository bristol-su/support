<?php

namespace BristolSU\Support\ModuleInstance\Settings;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class SettingListener implements ShouldQueue
{
    
    use Dispatchable, SerializesModels, Queueable;
    
    protected $key;
    
    public function handle($event, $payload) {
        $setting = $payload[0];
        $eventName =substr(substr($event, 9), 0, -65);
        if($setting->key === $this->key) {
            return $this->callEventFunction($eventName, $setting);
        }
        return false;
    }

    private function callEventFunction($eventName, $setting)
    {
        $method = 'on' . ucfirst($eventName);
        return (method_exists($this, $method)?$this->{$method}($setting):null);
    }


}