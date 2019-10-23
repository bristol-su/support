<?php


namespace BristolSU\Support\Logic\Specification;


use BristolSU\Support\Filters\Contracts\FilterInstance;
use BristolSU\Support\Filters\Contracts\FilterTester;
use BristolSU\Support\Logic\Contracts\Specification;

/**
 * Class FilterFalseSpecification
 * @package BristolSU\Support\Logic\Specification
 */
class FilterFalseSpecification implements Specification
{
    /**
     * @var FilterInstance
     */
    private $filter;
    /**
     * @var FilterTester
     */
    private $filterTester;

    /**
     * FilterFalseSpecification constructor.
     * @param FilterInstance $filter
     * @param FilterTester $filterTester
     */
    public function __construct(FilterInstance $filter, FilterTester $filterTester)
    {
        $this->filter = $filter;
        $this->filterTester = $filterTester;
    }

    /**
     * @return bool
     */
    public function isSatisfied(): bool
    {
        return $this->filterTester->evaluate($this->filter) === false;
    }

}
