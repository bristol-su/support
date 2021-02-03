<?php


namespace BristolSU\Support\Authorization\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;
use Illuminate\Http\Request;

/**
 * Log out of the user, group and role.
 */
class LogoutOfExtras
{
    /**
     * Holds the authentication.
     *
     * @var Authentication
     */
    private $authentication;

    /**
     * LogoutOfExtras constructor.
     *
     * @param Authentication $authentication
     */
    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * Log out of user, group and role.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        $this->authentication->reset();

        return $next($request);
    }
}
