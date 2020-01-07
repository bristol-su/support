<?php

namespace BristolSU\Support\Logic;

use BristolSU\Support\Filters\Contracts\FilterTester;
use BristolSU\Support\Logic\Contracts\LogicTester as LogicTesterContract;
use BristolSU\Support\Logic\Specification\AndSpecification;
use BristolSU\Support\Logic\Specification\OrSpecification;
use BristolSU\Support\Logic\Specification\FilterFalseSpecification;
use BristolSU\Support\Logic\Specification\FilterTrueSpecification;

/**
 * Class LogicTester
 * @package BristolSU\Support\Logic
 */
class LogicTester implements LogicTesterContract
{

    /**
     * @param Logic $logic
     * @param null $userModel
     * @param null $groupModel
     * @param null $roleModel
     * @return bool
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
