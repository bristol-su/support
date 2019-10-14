<?php

namespace BristolSU\Support\Logic\Contracts;

use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\User\User;

interface LogicTester
{

    public function evaluate(Logic $logic, $userModel = null, $groupModel = null, $roleModel = null);

}
