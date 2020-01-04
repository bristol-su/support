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
 * Class SystemUserPermission
 * @package BristolSU\Support\Permissions\Testers
 */
class SystemUserPermission extends Tester
{

    /**
     * Do the given models have the ability?
     * 
     * If the permission is a system permission, we'll see if the user has it or not.
     *
     * @param string $ability
     * @param User|null $user
     * @param Group|null $group
     * @param Role|null $role
     * @return bool|null
     */
    public function can(Permission $permission, ?User $user, ?Group $group, ?Role $role): ?bool
    {
        if($user === null || $permission->getType() !== 'global') {
            return null;
        }
        
        $override = ModelPermission::user($user->id(), $permission->getAbility())->first();
        
        return ($override === null?null:$override->result);
    }
}
