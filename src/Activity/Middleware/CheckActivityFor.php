<?php

namespace BristolSU\Support\Activity\Middleware;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Activity\Exception\ActivityRequiresGroup;
use BristolSU\Support\Activity\Exception\ActivityRequiresRole;
use BristolSU\Support\Activity\Exception\ActivityRequiresUser;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Logic\Facade\LogicTester;
use Closure;
use Illuminate\Http\Request;

/**
 * Class CheckLoggedIntoActivityFor
 * @package BristolSU\Support\Activity\Middleware
 */
class CheckActivityFor
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
        $logic = $activity->forLogic;
        if ($activity->activity_for === 'user' && !LogicTester::evaluate($logic, $this->authentication->getUser())) {
            throw new ActivityRequiresUser(
                'Activity requires a user to be logged in,', 403, null, $activity
            );
        }
        if($activity->activity_for === 'group' && !LogicTester::evaluate($logic, null, $this->authentication->getGroup())) {
            throw new ActivityRequiresGroup(                
                'Activity requires a group to be logged in,', 403, null, $activity
            );
        }
        if ($activity->activity_for === 'role' && !LogicTester::evaluate($logic, null, null, $this->authentication->getRole())) {
            throw new ActivityRequiresRole(                
                'Activity requires a role to be logged in,', 403, null, $activity
            );
        }
        return $next($request);
    }
}