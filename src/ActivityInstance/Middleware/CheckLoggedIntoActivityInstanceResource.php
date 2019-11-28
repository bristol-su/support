<?php

namespace BristolSU\Support\ActivityInstance\Middleware;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authorization\Exception\ActivityRequiresGroup;
use BristolSU\Support\Authorization\Exception\ActivityRequiresRole;
use BristolSU\Support\Authorization\Exception\ActivityRequiresUser;
use Illuminate\Http\Request;

class CheckLoggedIntoActivityInstanceResource
{

    /**
     * @var ActivityInstanceResolver
     */
    private $activityInstanceResolver;
    /**
     * @var Authentication
     */
    private $authentication;

    public function __construct(ActivityInstanceResolver $activityInstanceResolver, Authentication $authentication)
    {
        $this->activityInstanceResolver = $activityInstanceResolver;
        $this->authentication = $authentication;
    }
    public function handle(Request $request, \Closure $next)
    {
        $activityInstance = $this->activityInstanceResolver->getActivityInstance();

        if ($activityInstance->resource_type === 'user' && $this->authentication->getUser() === null) {
            throw new ActivityRequiresUser(
                'Activity requires a user to be logged in,', 403, null, $activityInstance->activity
            );
        }
        if($activityInstance->resource_type === 'group' && $this->authentication->getGroup() === null) {
            throw new ActivityRequiresGroup(
                'Activity requires a group to be logged in,', 403, null, $activityInstance->activity
            );
        }
        if ($activityInstance->resource_type === 'role' && $this->authentication->getRole() === null) {
            throw new ActivityRequiresRole(
                'Activity requires a role to be logged in,', 403, null, $activityInstance->activity
            );
        }
        return $next($request);
    }
}