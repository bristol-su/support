<?php

namespace BristolSU\Support\Permissions\Testers;

use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Contracts\Models\Permission;
use BristolSU\Support\Permissions\Contracts\Tester;
use BristolSU\Support\Permissions\Models\ModelPermission;
use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Control\Contracts\Models\User;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class SystemUserPermission
 * @package BristolSU\Support\Permissions\Testers
 */
class ModuleInstanceUserOverridePermission extends Tester
{
    
    /**
     * Do the given models have the ability?
     *
     * Check if the given user has been given an overrided permission for the specific module instance
     * 
     * @param Permission $permission
     * @param User|null $user
     * @param Group|null $group
     * @param Role|null $role
     * @return bool|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function can(Permission $permission, ?User $user, ?Group $group, ?Role $role): ?bool
    {
        $moduleInstance = app(ModuleInstance::class);
        if ($user === null || $moduleInstance->exists === false || $permission->getType() !== 'module') {
            return null;
        }

        $override = ModelPermission::user($user->id(), $permission->getAbility(), $moduleInstance->id())->first();
        
        return ($override === null ?null:$override->result);
    }
}
