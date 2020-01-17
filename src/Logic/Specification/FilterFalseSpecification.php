<?php


namespace BristolSU\Support\Logic\Specification;


use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Filters\Contracts\FilterInstance;
use BristolSU\Support\Filters\Contracts\FilterTester;
use BristolSU\Support\Logic\Contracts\Specification;

/**
 * Does the given filter return false for the given resources?
 */
class FilterFalseSpecification implements Specification
{
    /**
     * Holds the filter instance to test
     * 
     * @var FilterInstance
     */
    private $filter;
    
    /**
     * Holds the user to test the filter against
     * 
     * @var User|null
     */
    private $user;
    
    /**
     * Holds the group to test the filter against
     * 
     * @var Group|null
     */
    private $group;
    
    /**
     * Holds the role to test the filter against
     * 
     * @var Role|null
     */
    private $role;

    /**
     * @param FilterInstance $filter Filter instance to test
     * @param User|null $user Use to test the filter against
     * @param Group|null $group Group to test the filter against
     * @param Role|null $role Role to test the filter against
     */
    public function __construct(FilterInstance $filter, ?User $user, ?Group $group, ?Role $role)
    {
        $this->filter = $filter;
        $this->user = $user;
        $this->group = $group;
        $this->role = $role;
    }

    /**
     * Does the filter return false?
     * 
     * @return bool True if the filter is false, false if the filter is true
     */
    public function isSatisfied(): bool
    {
        switch ($this->filter->for()) {
            case 'user':
                return ($this->user === null ?false:
                    app(FilterTester::class)->evaluate($this->filter, $this->user) === false);
            case 'group':
                return ($this->group === null ?false:
                    app(FilterTester::class)->evaluate($this->filter, $this->group) === false);
            case 'role':
                return ($this->role === null ?false:
                    app(FilterTester::class)->evaluate($this->filter, $this->role) === false);
            default:
                return false;
        }
    }

}
