<?php


namespace BristolSU\Support\Permissions\Testers;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Contracts\Models\Permission;
use BristolSU\Support\Permissions\Contracts\Tester;
use Illuminate\Contracts\Container\Container;
use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Control\Contracts\Models\User;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class ModuleInstanceAdminPermissions
 * @package BristolSU\Support\Permissions\Testers
 */
class ModuleInstanceAdminPermissions extends Tester
{

    /**
     * @var LogicTester
     */
    private $logicTester;

    /**
     * ModuleInstanceAdminPermissions constructor.
     * @param LogicTester $logicTester
     */
    public function __construct(LogicTester $logicTester)
    {
        $this->logicTester = $logicTester;
    }

    /**
     * @param Permission $permission
     * @param User|null $user
     * @param Group|null $group
     * @param Role|null $role
     * @return bool|null
     */
    public function can(Permission $permission, ?User $user, ?Group $group, ?Role $role): ?bool
    {
        $moduleInstance = app(ModuleInstance::class);
        if ($moduleInstance->exists === false) {
            return null;
        }

        $adminPermissions = $moduleInstance->moduleInstancePermissions->admin_permissions;
        if (!array_key_exists($permission->getAbility(), $adminPermissions)) {
            return null;
        }

        $logic = Logic::find($adminPermissions[$permission->getAbility()]);
        if ($logic === null) {
            return null;
        }

        return $this->logicTester->evaluate($logic, $user, $group, $role);
    }
}
