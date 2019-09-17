<?php


namespace BristolSU\Support\Logic\Specification;


use BristolSU\Support\Filters\Contracts\FilterInstance;
use BristolSU\Support\Filters\Contracts\FilterTester;
use BristolSU\Support\Logic\Contracts\Specification;

class FilterTrueSpecification implements Specification
{
    /**
     * @var FilterInstance
     */
    private $filter;
    /**
     * @var FilterTester
     */
    private $filterTester;

    public function __construct(FilterInstance $filter, FilterTester $filterTester)
    {
        $this->filter = $filter;
        $this->filterTester = $filterTester;
    }

    public function isSatisfied(): bool
    {
        return $this->filterTester->evaluate($this->filter);
    }

}
