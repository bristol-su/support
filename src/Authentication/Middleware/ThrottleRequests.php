<?php

namespace BristolSU\Support\Authentication\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;

class ThrottleRequests extends \Illuminate\Routing\Middleware\ThrottleRequests
{

    protected function resolveRequestSignature($request)
    {
        if (app(Authentication::class)->hasUser()) {
            return sha1(app(Authentication::class)->getUser()->id());
        }
        return parent::resolveRequestSignature($request);
    }

}
