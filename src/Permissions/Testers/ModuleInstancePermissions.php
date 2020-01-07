<?php

namespace BristolSU\Support\Permissions\Testers;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Contracts\Models\Permission;
use BristolSU\Support\Permissions\Contracts\Tester;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Check if the credentials are in the logic group assigned to the module instance default permission
 */
class ModuleInstancePermissions extends Tester
{

    /**
     * Holds the logic tester instance
     * 
     * @var LogicTester
     */
    private $logicTester;

    /**
     * @param LogicTester $logicTester Tester to test the logic group with
     */
    public function __construct(LogicTester $logicTester)
    {
        $this->logicTester = $logicTester;
    }

    /**
     * Does the user have the given permission?
     * 
     * This tester will check the module instance permissions, and see if the given credentials are in the logic 
     * group assigned to the module instance permission.
     * 
     * @param Permission $permission Permission to test
     * @param User|null $user User to test with
     * @param Group|null $group Group to test with
     * @param Role|null $role Role to test with
     * @return bool|null If the user has the position
     */
    public function can(Permission $permission, ?User $user, ?Group $group, ?Role $role): ?bool
    {
        $moduleInstance = app(ModuleInstance::class);
        if ($moduleInstance->exists === false) {
            return null;
        }

        try {
            $permissionValue = $moduleInstance->moduleInstancePermissions()
                ->where('ability', $permission->getAbility())->firstOrFail();
            if ($permissionValue->logic !== null) {
                return $this->logicTester->evaluate($permissionValue->logic, $user, $group, $role);

            }
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }
}
