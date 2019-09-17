<?php

namespace BristolSU\Support\Logic;

use BristolSU\Support\Filters\Contracts\FilterTester;
use BristolSU\Support\Logic\Contracts\LogicTester as LogicTesterContract;
use BristolSU\Support\Logic\Specification\AndSpecification;
use BristolSU\Support\Logic\Specification\OrSpecification;
use BristolSU\Support\Logic\Specification\FilterFalseSpecification;
use BristolSU\Support\Logic\Specification\FilterTrueSpecification;

class LogicTester implements LogicTesterContract
{

    /**
     * @var FilterTester
     */
    private $filterTester;

    public function __construct(FilterTester $filterTester)
    {
        $this->filterTester = $filterTester;
    }

    public function evaluate(Logic $logic)
    {
        $allTrue = [];
        $anyTrue = [];
        $allFalse = [];
        $anyFalse = [];

        foreach($logic->allTrueFilters as $filter) {
            $allTrue[] = new FilterTrueSpecification($filter, $this->filterTester);
        }

        foreach($logic->anyTrueFilters as $filter) {
            $anyTrue[] = new FilterTrueSpecification($filter, $this->filterTester);
        }

        foreach($logic->allFalseFilters as $filter) {
            $allFalse[] = new FilterFalseSpecification($filter, $this->filterTester);
        }

        foreach($logic->anyFalseFilters as $filter) {
            $anyFalse[] = new FilterFalseSpecification($filter, $this->filterTester);
        }


        return (new AndSpecification(
            new AndSpecification(...$allTrue),
            new OrSpecification(...$anyTrue),
            new AndSpecification(...$allFalse),
            new OrSpecification(...$anyFalse)
        ))->isSatisfied();
    }


}
