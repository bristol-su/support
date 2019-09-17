<?php

namespace BristolSU\Support\ModuleInstance\Connection;

use Throwable;

/**
 * Exception for if a service is requested but cannot be found
 */
class NoConnectionAvailable extends \Exception
{

    /**
     * @param string $message Message for the exception
     * @param int $code Code for the exception
     * @param Throwable|null $previous Previous exception
     */
    public function __construct($message = "No connection has been found", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}