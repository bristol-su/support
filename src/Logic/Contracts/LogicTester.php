<?php

namespace BristolSU\Support\Logic\Contracts;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Logic\Logic;

/**
 * Test a set of resources against a logic group
 */
interface LogicTester
{

    /**
     * See if the given resources together are in the logic group
     * 
     * @param Logic $logic Logic group to test
     * @param null|User $userModel User to test in the logic group
     * @param null|Group $groupModel Group to test in the logic group
     * @param null|Role $roleModel Role to test in the logic group
     * @return bool Are the given resources in the logic group
     */
    public function evaluate(Logic $logic, $userModel = null, $groupModel = null, $roleModel = null): bool;

}
