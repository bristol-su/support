<?php

namespace BristolSU\Support\Authorization\Exception;

use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;

class ModuleInstanceDisabled extends \Exception
{
    /**
     * The moduleInstance that was disabled.
     * @var ModuleInstance
     */
    protected $moduleInstance;

    /**
     * Create an instance of the exception.
     *
     * @param ModuleInstance $moduleInstance
     * @return ModuleInstanceDisabled
     */
    public static function fromModuleInstance(ModuleInstance $moduleInstance)
    {
        $exception = new self(sprintf('%s from %s has been disabled', $moduleInstance->name, $moduleInstance->activity->name), 403);
        $exception->setModuleInstance($moduleInstance);

        return $exception;
    }

    /**
     * Set the moduleInstance that caused the exception to be thrown.
     *
     * @param ModuleInstance $moduleInstance
     */
    public function setModuleInstance(ModuleInstance $moduleInstance)
    {
        $this->moduleInstance = $moduleInstance;
    }

    /**
     * Set the moduleInstance that caused the exception to be thrown.
     *
     * @return ModuleInstance
     */
    public function moduleInstance()
    {
        return $this->moduleInstance;
    }
}
