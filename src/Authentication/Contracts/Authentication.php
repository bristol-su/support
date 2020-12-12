<?php

namespace BristolSU\Support\Authentication\Contracts;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use \BristolSU\ControlDB\Contracts\Models\User;

/**
 * Authentication manager interface.
 *
 * Sets and retrieves the current user, group or role. Used to get any logged in control model. This is the proxy for
 * getting and setting the logged in user/group/role.
 *
 */
interface Authentication
{
    /**
     * Get a group
     *
     * @return Group|null
     */
    public function getGroup();

    /**
     * Get a role
     *
     * @return Role|null
     */
    public function getRole();

    /**
     * Get a user
     *
     * @return User|null
     */
    public function getUser();

    /**
     * Set a group
     *
     * @param Group $group
     * @return void
     */
    public function setGroup(Group $group);

    /**
     * Set a role
     *
     * @param Role $role
     * @return void
     */
    public function setRole(Role $role);

    /**
     * Set a user
     *
     * @param User $user
     * @return void
     */
    public function setUser(User $user);

    /**
     * Is a group logged in?
     *
     * @return bool
     */
    public function hasGroup(): bool;

    /**
     * Is a role logged in?
     *
     * @return bool
     */
    public function hasRole(): bool;

    /**
     * Is a user logged in?
     *
     * @return bool
     */
    public function hasUser(): bool;

}
