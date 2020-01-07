<?php


namespace BristolSU\Support\Permissions\Facade;


use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Permissions\Contracts\PermissionTester as PermissionTesterContract;
use BristolSU\Support\Permissions\Contracts\Tester;
use Illuminate\Support\Facades\Facade;

/**
 * Facade for testing permissions and registering testers
 *
 * @method static bool evaluate(string $ability) Test if the currently authenticated user/group/role has the given ability
 * @method static bool evaluateFor(string $ability, ?User $userModel = null, ?Group $group = null, ?Role $role = null) Test if the given set of credentials have a given ability
 * @method static void register(Tester $tester) Register a new permission tester
 */
class PermissionTester extends Facade
{

    /**
     * Get the binding in the laravel container
     * 
     * @return string PermissionTester Contract
     */
    protected static function getFacadeAccessor()
    {
        return PermissionTesterContract::class;
    }

}
