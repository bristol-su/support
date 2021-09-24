<?php

namespace BristolSU\Support\Logic\DatabaseDecorator;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;

class LogicResultCacher
{

    public function cacheLogic(Logic $logic)
    {
        // Go through every one of the users, groups and roles.
    }

    public function cacheLogicWithResources(Logic $logic, ?User $user = null, ?Group $group = null, ?Role $role = null)
    {
        app(LogicTester::class)->evaluate($logic, $user, $group, $role);
    }

}
