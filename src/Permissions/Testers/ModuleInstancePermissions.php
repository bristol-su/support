<?php


namespace BristolSU\Support\Permissions\Testers;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Contracts\Models\Permission;
use BristolSU\Support\Permissions\Contracts\Tester;
use Illuminate\Contracts\Container\Container;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ModuleInstanceAdminPermissions
 * @package BristolSU\Support\Permissions\Testers
 */
class ModuleInstancePermissions extends Tester
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
        if($moduleInstance->exists === false){
            return null;
        }

        try {
            $permissionValue = $moduleInstance->moduleInstancePermissions()
                ->where('ability', $permission->getAbility())->firstOrFail();
            if($permissionValue->logic !== null) {
                return $this->logicTester->evaluate($permissionValue->logic, $user, $group, $role);

            }
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }
}
