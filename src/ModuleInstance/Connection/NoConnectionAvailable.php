<?php

namespace BristolSU\Support\ModuleInstance\Connection;

use Throwable;

class NoConnectionAvailable extends \Exception
{

    public function __construct($message = "No connection has been found", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}