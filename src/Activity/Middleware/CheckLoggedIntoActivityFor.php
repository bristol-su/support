<?php

namespace BristolSU\Support\Activity\Middleware;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Activity\Exception\ActivityRequiresGroup;
use BristolSU\Support\Activity\Exception\ActivityRequiresRole;
use BristolSU\Support\Activity\Exception\ActivityRequiresUser;
use BristolSU\Support\Authentication\Contracts\Authentication;
use Closure;
use Illuminate\Http\Request;

/**
 * Class CheckLoggedIntoActivityFor
 * @package BristolSU\Support\Activity\Middleware
 */
class CheckLoggedIntoActivityFor
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
        if ($activity->activity_for === 'user' && $this->authentication->getUser() === null) {
            throw new ActivityRequiresUser(
                'Activity requires a user to be logged in,', 403, null, $activity
            );
        }
        if($activity->activity_for === 'group' && $this->authentication->getGroup() === null) {
            throw new ActivityRequiresGroup(                
                'Activity requires a group to be logged in,', 403, null, $activity
            );
        }
        if ($activity->activity_for === 'role' && $this->authentication->getRole() === null) {
            throw new ActivityRequiresRole(                
                'Activity requires a role to be logged in,', 403, null, $activity
            );
        }
        return $next($request);
    }
}