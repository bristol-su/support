<?php

namespace BristolSU\Support\Authorization\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class IncorrectLogin extends HttpException
{

    public function __construct(string $message = null, \Throwable $previous = null, array $headers = [], ?int $code = 0)
    {
        parent::__construct(403, $message, $previous, $headers, $code);
    }

}