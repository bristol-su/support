<?php


namespace BristolSU\Support\Logic\Specification;


use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Control\Contracts\Models\User;
use BristolSU\Support\Filters\Contracts\FilterInstance;
use BristolSU\Support\Filters\Contracts\FilterTester;
use BristolSU\Support\Logic\Contracts\Specification;

/**
 * Class FilterTrueSpecification
 * @package BristolSU\Support\Logic\Specification
 */
class FilterTrueSpecification implements Specification
{
    /**
     * @var FilterInstance
     */
    private $filter;
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
     * FilterTrueSpecification constructor.
     * @param FilterInstance $filter
     * @param FilterTester $filterTester
     */
    public function __construct(FilterInstance $filter, ?User $user, ?Group $group, ?Role $role)
    {
        $this->filter = $filter;
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
                return ($this->user === null ?false:
                    app(FilterTester::class)->evaluate($this->filter, $this->user) === true);
            case 'group':
                return ($this->group === null ?false:
                    app(FilterTester::class)->evaluate($this->filter, $this->group) === true);
            case 'role':
                return ($this->role === null ?false:
                    app(FilterTester::class)->evaluate($this->filter, $this->role) === true);
            default:
                return false;
        }
    }

}
