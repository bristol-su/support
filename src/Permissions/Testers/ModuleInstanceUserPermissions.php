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

/**
 * Class ModuleInstanceUserPermissions
 * @package BristolSU\Support\Permissions\Testers
 */
class ModuleInstanceUserPermissions extends Tester
{
    /**
     * @var LogicTester
     */
    private $logicTester;

    /**
     * ModuleInstanceUserPermissions constructor.
     * @param LogicTester $logicTester
     */
    public function __construct(LogicTester $logicTester)
    {
        $this->logicTester = $logicTester;
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
        $moduleInstance = app(ModuleInstance::class);
        if ($moduleInstance->exists === false) {
            return null;
        }
        
        $participantPermissions = $moduleInstance->moduleInstancePermissions->participant_permissions;
        if (!array_key_exists($permission->getAbility(), $participantPermissions)) {
            return null;
        }
        
        $logic = Logic::find($participantPermissions[$permission->getAbility()]);
        if ($logic === null) {
            return null;
        }

        return $this->logicTester->evaluate($logic, $user, $group, $role);
    }
}
