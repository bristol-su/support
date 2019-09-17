<?php

namespace BristolSU\Support\Authentication\Contracts;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use \BristolSU\ControlDB\Contracts\Models\User;

/**
 * Authentication manager interface.
 * 
 * Sets and retrieves the current user, group or role
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
     * Reset any authentication persistence, to bring the authentication back to an initial clean state.
     * 
     * @return void
     */
    public function reset(): void;
}
