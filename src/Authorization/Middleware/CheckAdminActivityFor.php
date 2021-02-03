<?php

namespace BristolSU\Support\Authorization\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authorization\Exception\ActivityRequiresAdmin;
use BristolSU\Support\Logic\Facade\LogicTester;
use Closure;
use Illuminate\Http\Request;

/**
 * Middleware to check the user is in the adminLogic logic group.
 */
class CheckAdminActivityFor
{
    /**
     * Holds the authentication.
     *
     * @var Authentication
     */
    private $authentication;

    /**
     * Initialise middleware.
     *
     * @param Authentication $authentication
     */
    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * Check the user is in the adminLogic logic group.
     * @param Request $request
     * @param Closure $next
     * @throws ActivityRequiresAdmin
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $activity = $request->route('activity_slug');
        if (!LogicTester::evaluate($activity->adminLogic, $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole())) {
            throw ActivityRequiresAdmin::createWithActivity($activity, 'You must be an administrator to access this page', 403);
        }

        return $next($request);
    }
}
