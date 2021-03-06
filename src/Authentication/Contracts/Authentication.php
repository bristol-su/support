<?php

namespace BristolSU\Support\Authentication\Contracts;

use \BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;

/**
 * Authentication manager interface.
 *
 * Sets and retrieves the current user, group or role
 *
 */
interface Authentication
{
    /**
     * Get a group.
     *
     * @return Group|null
     */
    public function getGroup();

    /**
     * Get a role.
     *
     * @return Role|null
     */
    public function getRole();

    /**
     * Get a user.
     *
     * @return User|null
     */
    public function getUser();

    /**
     * Set a group.
     *
     * @param Group $group
     */
    public function setGroup(Group $group);

    /**
     * Set a role.
     *
     * @param Role $role
     */
    public function setRole(Role $role);

    /**
     * Set a user.
     *
     * @param User $user
     */
    public function setUser(User $user);

    /**
     * Reset any authentication persistence, to bring the authentication back to an initial clean state.
     *
     */
    public function reset(): void;
}
