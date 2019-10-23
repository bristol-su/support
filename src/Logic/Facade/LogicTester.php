<?php


namespace BristolSU\Support\Logic\Facade;


use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Logic\Contracts\LogicTester as LogicTesterContract;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Control\Contracts\Models\User;
use Illuminate\Support\Facades\Facade;

/**
 * Class LogicTester
 * @package BristolSU\Support\Logic\Facade
 *
 * @method static bool evaluate(Logic $logic, ?User $user = null, ?Group $group = null, ?Role $role = nul)
 *
 * @see LogicTesterContract
 */
class LogicTester extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return LogicTesterContract::class;
    }

}
