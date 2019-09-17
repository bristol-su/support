<?php

namespace BristolSU\Support\Permissions\Testers;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Permissions\Contracts\Models\Permission;
use BristolSU\Support\Permissions\Contracts\Tester;
use BristolSU\Support\Permissions\Models\ModelPermission;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;

/**
 * Check if a global position is owned by the user
 */
class SystemUserPermission extends Tester
{

    /**
     * Do the given models have the ability?
     *
     * If the permission is a system permission, we'll see if the user has it or not.
     *
     * @param Permission $permission Permission to test
     * @param User|null $user User to test on
     * @param Group|null $group Group to test on. Not used
     * @param Role|null $role Role to test on. Not used
     * @return bool|null If the permission is owned or not owned. Null if no records found
     */
    public function can(Permission $permission, ?User $user, ?Group $group, ?Role $role): ?bool
    {
        if ($user === null || $permission->getType() !== 'global') {
            return null;
        }
        
        $override = ModelPermission::user($user->id(), $permission->getAbility())->first();
        
        return ($override === null ?null:$override->result);
    }
}
