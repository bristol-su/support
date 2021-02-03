<?php

namespace BristolSU\Support\ModuleInstance\Settings;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Handles triggering functions when a setting is changed.
 */
abstract class SettingListener implements ShouldQueue
{
    use Dispatchable, SerializesModels, Queueable;

    /**
     * Set the key of the setting to trigger on.
     *
     * @var string
     */
    protected $key;

    /**
     * Handle a setting event.
     *
     * @param string $event Event name. Of the form 'eloquent.eventname: ModuleInstanceSetting::class'
     * @param array $payload First element is the module instance setting
     * @return mixed
     */
    public function handle($event, $payload)
    {
        $setting = $payload[0];
        $eventName = substr(substr($event, 9), 0, -65);
        if ($setting->key === $this->key) {
            return $this->callEventFunction($eventName, $setting);
        }

        return false;
    }

    /**
     * Call the method related to the event name.
     *
     * If the event name was 'saved', this function will call the onSaved method (if exists) and pass the setting
     * @param string $eventName Event name
     * @param ModuleInstanceSetting $setting Setting to pass to the method
     * @return mixed
     */
    private function callEventFunction($eventName, $setting)
    {
        $method = 'on' . ucfirst($eventName);

        return (method_exists($this, $method) ? $this->{$method}($setting) : null);
    }
}
