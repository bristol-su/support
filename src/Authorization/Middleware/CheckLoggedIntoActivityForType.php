<?php

namespace BristolSU\Support\Authorization\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authorization\Exception\ActivityRequiresGroup;
use BristolSU\Support\Authorization\Exception\ActivityRequiresParticipant;
use BristolSU\Support\Authorization\Exception\ActivityRequiresRole;
use BristolSU\Support\Authorization\Exception\ActivityRequiresUser;
use Closure;
use Illuminate\Http\Request;

/**
 * Check the user is logged into the correct resource
 */
class CheckLoggedIntoActivityForType
{
    /**
     * Holds the authentication
     * 
     * @var Authentication
     */
    private $authentication;

    /**
     * Initialise middleware
     * @param Authentication $authentication
     */
    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * Check the user is logged into the correct resource.
     * 
     * If, for example, the activity is a group activity, an exception will be thrown if the user is not logged into a group
     * 
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws ActivityRequiresParticipant
     */
    public function handle(Request $request, Closure $next)
    {
        $activity = $request->route('activity_slug');
        if ($activity->activity_for === 'user' && $this->authentication->getUser() === null) {
            throw new ActivityRequiresUser(
                'Activity requires a user to be logged in,', 403, null, $activity
            );
        }
        if ($activity->activity_for === 'group' && $this->authentication->getGroup() === null) {
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