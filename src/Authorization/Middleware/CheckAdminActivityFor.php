<?php

namespace BristolSU\Support\Authorization\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authorization\Exception\ActivityRequiresAdmin;
use BristolSU\Support\Logic\Facade\LogicTester;
use Closure;
use Illuminate\Http\Request;

/**
 * Class CheckLoggedIntoActivityFor
 * @package BristolSU\Support\Activity\Middleware
 */
class CheckAdminActivityFor
{
    /**
     * @var Authentication
     */
    private $authentication;

    /**
     * CheckLoggedIntoActivityFor constructor.
     * @param Authentication $authentication
     */
    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function handle(Request $request, Closure $next)
    {
        $activity = $request->route('activity_slug');
        if (!LogicTester::evaluate($activity->adminLogic, $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole())) {
            throw new ActivityRequiresAdmin('You must be an administrator to access this page', 403, null, $activity);
        }
        return $next($request);
    }
}