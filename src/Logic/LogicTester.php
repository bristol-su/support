<?php

namespace BristolSU\Support\Logic;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Logic\Contracts\LogicTester as LogicTesterContract;
use BristolSU\Support\Logic\Specification\AndSpecification;
use BristolSU\Support\Logic\Specification\OrSpecification;
use BristolSU\Support\Logic\Specification\FilterFalseSpecification;
use BristolSU\Support\Logic\Specification\FilterTrueSpecification;

/**
 * Test if the given resources are in a logic group
 */
class LogicTester implements LogicTesterContract
{

    /**
     * Test if the given resources are in a logic group
     * 
     * @param Logic $logic Logic group to test
     * @param null|User $userModel The user model to test the logic group with
     * @param null|Group $groupModel The group model to test the logic group with
     * @param null|Role $roleModel The role model to test the logic group with
     * 
     * @return bool If the user, group and/or role are in the logic group
     */
    public function evaluate(Logic $logic, $userModel = null, $groupModel = null, $roleModel = null): bool
    {
        $allTrue = [];
        $anyTrue = [];
        $allFalse = [];
        $anyFalse = [];

        foreach ($logic->allTrueFilters as $filter) {
            $allTrue[] = new FilterTrueSpecification($filter, $userModel, $groupModel, $roleModel);
        }

        foreach ($logic->anyTrueFilters as $filter) {
            $anyTrue[] = new FilterTrueSpecification($filter, $userModel, $groupModel, $roleModel);
        }

        foreach ($logic->allFalseFilters as $filter) {
            $allFalse[] = new FilterFalseSpecification($filter, $userModel, $groupModel, $roleModel);
        }

        foreach ($logic->anyFalseFilters as $filter) {
            $anyFalse[] = new FilterFalseSpecification($filter, $userModel, $groupModel, $roleModel);
        }


        return (new AndSpecification(
            new AndSpecification(...$allTrue),
            new OrSpecification(...$anyTrue),
            new AndSpecification(...$allFalse),
            new OrSpecification(...$anyFalse)
        ))->isSatisfied();
    }


}
