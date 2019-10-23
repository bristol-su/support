<?php

namespace BristolSU\Support\Logic;

use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Filters\Contracts\FilterTester;
use BristolSU\Support\Logic\Contracts\LogicTester as LogicTesterContract;
use BristolSU\Support\Logic\Specification\AndSpecification;
use BristolSU\Support\Logic\Specification\OrSpecification;
use BristolSU\Support\Logic\Specification\FilterFalseSpecification;
use BristolSU\Support\Logic\Specification\FilterTrueSpecification;
use BristolSU\Support\Control\Contracts\Models\User;

/**
 * Class LogicTester
 * @package BristolSU\Support\Logic
 */
class LogicTester implements LogicTesterContract
{

    /**
     * @var FilterTester
     */
    private $filterTester;

    /**
     * LogicTester constructor.
     * @param FilterTester $filterTester
     */
    public function __construct(FilterTester $filterTester)
    {
        $this->filterTester = $filterTester;
    }

    /**
     * @param Logic $logic
     * @param null $userModel
     * @param null $groupModel
     * @param null $roleModel
     * @return bool
     */
    public function evaluate(Logic $logic, $userModel = null, $groupModel = null, $roleModel = null)
    {
        $this->overrideFilterTester($userModel, $groupModel, $roleModel);
        $allTrue = [];
        $anyTrue = [];
        $allFalse = [];
        $anyFalse = [];

        foreach ($logic->allTrueFilters as $filter) {
            $allTrue[] = new FilterTrueSpecification($filter, $this->filterTester);
        }

        foreach ($logic->anyTrueFilters as $filter) {
            $anyTrue[] = new FilterTrueSpecification($filter, $this->filterTester);
        }

        foreach ($logic->allFalseFilters as $filter) {
            $allFalse[] = new FilterFalseSpecification($filter, $this->filterTester);
        }

        foreach ($logic->anyFalseFilters as $filter) {
            $anyFalse[] = new FilterFalseSpecification($filter, $this->filterTester);
        }


        return (new AndSpecification(
            new AndSpecification(...$allTrue),
            new OrSpecification(...$anyTrue),
            new AndSpecification(...$allFalse),
            new OrSpecification(...$anyFalse)
        ))->isSatisfied();
    }

    /**
     * @param User|null $user
     * @param Group|null $group
     * @param Role|null $role
     */
    private function overrideFilterTester(?User $user, ?Group $group, ?Role $role)
    {
        if($user !== null) {
            $this->filterTester->setUser($user);
        }
        if($group !== null) {
            $this->filterTester->setGroup($group);
        }
        if($role !== null) {
            $this->filterTester->setRole($role);
        }
    }


}
