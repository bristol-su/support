<?php

namespace BristolSU\Support\Permissions\Testers;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Contracts\Models\Permission;
use BristolSU\Support\Permissions\Contracts\Testers\Tester;
use BristolSU\Support\Permissions\Models\ModelPermission;
use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Control\Contracts\Models\User;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class SystemRolePermission
 * @package BristolSU\Support\Permissions\Testers
 */
class ModuleInstanceRoleOverridePermission extends Tester
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    
    /**
     * Do the given models have the ability?
     *
     * @param string $ability
     * @param User|null $user
     * @param Group|null $group
     * @param Role|null $role
     * @return bool|null
     */
    public function can(Permission $permission, ?User $user, ?Group $group, ?Role $role): ?bool
    {
        $moduleInstance = $this->app->make(ModuleInstance::class);
        if($role === null || $moduleInstance->exists === false) {
            return null;
        }

        $override = ModelPermission::role($role->id(), $permission->getAbility(), $moduleInstance->id())->first();

        return ($override === null?null:$override->result);
    }
}
