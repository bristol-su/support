<?php


namespace BristolSU\Support\Logic\Facade;


use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Logic\Contracts\LogicTester as LogicTesterContract;
use BristolSU\Support\Logic\Logic;
use Illuminate\Support\Facades\Facade;

/**
 * CTest a logic group
 *
 * @method static bool evaluate(Logic $logic, ?User $user = null, ?Group $group = null, ?Role $role = null) Are the given resources in the logic group?
 *
 * @see LogicTesterContract
 */
class LogicTester extends Facade
{

    /**
     * Get the name of the logic tester binding in the container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return LogicTesterContract::class;
    }

}
