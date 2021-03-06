<?php

namespace BristolSU\Support\Permissions\Testers;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Contracts\Models\Permission;
use BristolSU\Support\Permissions\Contracts\Tester;
use BristolSU\Support\Permissions\Models\ModelPermission;

/**
 * Check if a module permission is owned by a role.
 */
class ModuleInstanceGroupOverridePermission extends Tester
{
    /**
     * Do the given models have the ability?
     *
     * Check if the given group has been given an overridden permission for the specific module instance
     *
     * @param Permission $permission Permission to test
     * @param User|null $user User to test on. Not used
     * @param Group|null $group Group to test on.
     * @param Role|null $role Role to test on. Not used
     * @return bool|null If the permission is owned or not owned. Null if no records found
     */
    public function can(Permission $permission, ?User $user, ?Group $group, ?Role $role): ?bool
    {
        $moduleInstance = app(ModuleInstance::class);
        if ($group === null || $moduleInstance->exists === false) {
            return null;
        }

        $override = ModelPermission::group($group->id(), $permission->getAbility(), $moduleInstance->id())->first();

        return ($override === null ?null:$override->result);
    }
}
