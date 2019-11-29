<?php

namespace BristolSU\Support\Authorization\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authorization\Exception\ActivityRequiresGroup;
use BristolSU\Support\Authorization\Exception\ActivityRequiresRole;
use BristolSU\Support\Authorization\Exception\ActivityRequiresUser;
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
        // TODO Is this working? Is it right to pass all the things to the logic? I think so!
        // Change to 'not authenricated for module' exception?
        $activity = $request->route('activity_slug');
        $logic = $activity->forLogic;
        if ($activity->activity_for === 'user' && !LogicTester::evaluate($logic, $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole())) {
            throw new ActivityRequiresUser(
                'Activity requires a user to be logged in,', 403, null, $activity
            );
        }
        if ($activity->activity_for === 'group' && !LogicTester::evaluate($logic, $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole())) {
            throw new ActivityRequiresGroup(
                'Activity requires a group to be logged in,', 403, null, $activity
            );
        }
        if ($activity->activity_for === 'role' && !LogicTester::evaluate($logic, $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole())) {
            throw new ActivityRequiresRole(
                'Activity requires a role to be logged in,', 403, null, $activity
            );
        }
        return $next($request);
    }
}