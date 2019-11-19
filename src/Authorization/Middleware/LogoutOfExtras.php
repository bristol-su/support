<?php


namespace BristolSU\Support\Authorization\Middleware;


use BristolSU\Support\Authentication\Contracts\Authentication;
use Illuminate\Http\Request;

class LogoutOfExtras
{

    /**
     * @var Authentication
     */
    private $authentication;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function handle(Request $request, \Closure $next)
    {
        // TODO Do the same if we're going from one module to another.
        $this->authentication->reset();
        return $next($request);
    }

}