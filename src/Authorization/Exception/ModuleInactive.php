<?php

namespace BristolSU\Support\Authorization\Exception;

class ModuleInactive extends \Exception
{
    /**
     * @var
     */
    private $moduleInstance;

    /**
     * ActivityRequiresAdmin constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param $moduleInstance
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null, $moduleInstance)
    {
        parent::__construct($message, $code, $previous);
        $this->$moduleInstance = $moduleInstance;
    }

    /**
     * @return mixed
     */
    public function getModuleInstance()
    {
        return $this->moduleInstance;
    }
}