<?php

namespace BristolSU\Support\Authentication\Exception;

use Throwable;

class IsAuthenticatedException extends \Exception
{

    public function __construct($message = "You must be unauthenticated.", $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
