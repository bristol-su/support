<?php

namespace BristolSU\Support\Authentication\Middleware;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authorization\Exception\IncorrectLogin;
use Illuminate\Http\Request;

class CheckAdditionalCredentialsOwnedByUser
{

    /**
     * @var Authentication
     */
    private $authentication;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * Check the user has access to any logged in group/role
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws IncorrectLogin If there is an issue with the logged in group/role
     */
    public function handle(Request $request, \Closure $next)
    {
        if($user = $this->authentication->getUser()) {
            if($role = $this->authentication->getRole()) {
                $this->checkUserIsInRole($user, $role);
                $group = $this->checkGroupIsLoggedIn();
                $this->checkGroupBelongsToRole($group, $role);
            } elseif($group = $this->authentication->getGroup()) {
                $this->checkUserHasMembershipToGroup($user, $group);
            }
        }

        return $next($request);
    }

    /**
     * Check that the user is in the given role
     *
     * @param User $user
     * @param Role $role
     * @throws IncorrectLogin If the user does not belong to the role
     */
    private function checkUserIsInRole(User $user, Role $role): void
    {
        if(!in_array($role->id(), $user->roles()->map(function(Role $role) {
            return $role->id();
        })->toArray())) {
            throw new IncorrectLogin('The user must own the current role');
        }
    }

    /**
     * Check that the role belongs to the given group
     *
     * @param Group $group
     * @param Role $role
     * @throws IncorrectLogin If the group does not belong to the role
     */
    private function checkGroupBelongsToRole(Group $group, Role $role): void
    {
        if($group->id() !== $role->groupId()) {
            throw new IncorrectLogin('The group must belong to the current role');
        }
    }

    /**
     * Check that the user has a membership to the given group
     *
     * @param User $user
     * @param Group $group
     * @throws IncorrectLogin If the user does not have a membership to the group
     */
    private function checkUserHasMembershipToGroup(User $user, Group $group): void
    {
        if(!in_array($group->id(), $user->groups()->map(function(Group $group) {
            return $group->id();
        })->toArray())) {
            throw new IncorrectLogin('The user must have a membership to the group');
        }
    }

    /**
     * Check a group is currently logged in
     *
     * @throws IncorrectLogin If a group is not logged in
     */
    private function checkGroupIsLoggedIn(): Group
    {
        if($group = $this->authentication->getGroup()) {
            return $group;
        }
        throw new IncorrectLogin('The group must belong to the current role');
    }

}
