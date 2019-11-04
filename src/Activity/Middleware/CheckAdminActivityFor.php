<?php

namespace BristolSU\Support\Activity\Middleware;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Activity\Exception\ActivityRequiresGroup;
use BristolSU\Support\Activity\Exception\ActivityRequiresRole;
use BristolSU\Support\Activity\Exception\ActivityRequiresUser;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Logic\Facade\LogicTester;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
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

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws ActivityRequiresGroup
     * @throws ActivityRequiresRole
     * @throws ActivityRequiresUser
     */
    public function handle(Request $request, Closure $next)
    {
        $activity = $request->route('activity_slug');
        $user = $this->authentication->getUser();
        $group = $this->authentication->getGroup();
        $role = $this->authentication->getRole();
        if(!LogicTester::evaluate($activity->adminLogic, $user, $group, $role)) {
            throw new AuthorizationException('You do not have access to this activity');
        }
        return $next($request);
    }
}