<?php

namespace BristolSU\Support\Authorization\Exception;

use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;
use Exception;
use Throwable;

/**
 * Fired if the module is inactive.
 */
class ModuleInactive extends Exception
{
    /**
     * Hold a module instance.
     *
     * @var ModuleInstance
     */
    private $moduleInstance;

    /**
     * Initialise the exception.
     *
     * @param string $message Message for the exception
     * @param int $code Status code
     * @param Throwable|null $previous Previous exception
     * @param ModuleInstance $moduleInstance ModuleInstance that was accessed
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null, $moduleInstance = null)
    {
        parent::__construct($message, $code, $previous);
        $this->moduleInstance = $moduleInstance;
    }

    /**
     * Create the exception with a given moduleInstance.
     *
     * @param ModuleInstance $moduleInstance ModuleInstance that was accessed
     * @param string $message Message for the exception
     * @param int $code Status code
     *
     * @return ModuleInactive
     */
    public static function createWithModuleInstance(ModuleInstance $moduleInstance, string $message = '', int $code = 0)
    {
        return new self($message, $code, null, $moduleInstance);
    }

    /**
     * Get the module instance.
     *
     * @return ModuleInstance
     */
    public function getModuleInstance()
    {
        return $this->moduleInstance;
    }
}
