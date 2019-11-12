<?php


namespace BristolSU\Support\Logic\Specification;


use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Control\Contracts\Models\User;
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
     * @var User|null
     */
    private $user;
    /**
     * @var Group|null
     */
    private $group;
    /**
     * @var Role|null
     */
    private $role;

    /**
     * FilterFalseSpecification constructor.
     * @param FilterInstance $filter
     * @param FilterTester $filterTester
     */
    public function __construct(FilterInstance $filter, FilterTester $filterTester, ?User $user, ?Group $group, ?Role $role)
    {
        $this->filter = $filter;
        $this->filterTester = $filterTester;
        $this->user = $user;
        $this->group = $group;
        $this->role = $role;
    }

    /**
     * @return bool
     */
    public function isSatisfied(): bool
    {
        switch ($this->filter->for()) {
            case 'user':
                return ($this->user === null?false:
                    $this->filterTester->evaluate($this->filter, $this->user) === false);
            case 'group':
                return ($this->group === null?false:
                    $this->filterTester->evaluate($this->filter, $this->group) === false);
            case 'role':
                return ($this->role === null?false:
                    $this->filterTester->evaluate($this->filter, $this->role) === false);
        }
    }

}
