<?php

namespace BristolSU\Support\Logic\Contracts;

use BristolSU\Support\Logic\Logic;

/**
 * Interface LogicTester
 * @package BristolSU\Support\Logic\Contracts
 */
interface LogicTester
{

    /**
     * @param Logic $logic
     * @param null $userModel
     * @param null $groupModel
     * @param null $roleModel
     * @return mixed
     */
    public function evaluate(Logic $logic, $userModel = null, $groupModel = null, $roleModel = null);

}
