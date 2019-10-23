<?php


namespace BristolSU\Support\Filters;


use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Filters\Contracts\FilterInstance;
use BristolSU\Support\Filters\Contracts\FilterRepository;
use BristolSU\Support\Filters\Contracts\Filters\GroupFilter;
use BristolSU\Support\Filters\Contracts\Filters\RoleFilter;
use BristolSU\Support\Filters\Contracts\Filters\UserFilter;
use BristolSU\Support\Filters\Contracts\FilterTester as FilterTesterContract;
use \BristolSU\Support\Control\Contracts\Models\User;

/**
 * Class FilterTester
 * @package BristolSU\Support\Filters
 */
class FilterTester implements FilterTesterContract
{

    /**
     * @var
     */
    private $user;
    /**
     * @var
     */
    private $group;
    /**
     * @var
     */
    private $role;
    
    /**
     * @var FilterRepository
     */
    private $repository;

    /**
     * FilterTester constructor.
     * @param FilterRepository $repository
     */
    public function __construct(FilterRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param Group $group
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;
    }

    /**
     * @param Role $role
     */
    public function setRole(Role $role)
    {
        $this->role = $role;
    }

    /**
     * @param FilterInstance $filterInstance
     * @param null $userModel
     * @param null $groupModel
     * @param null $roleModel
     * @return bool
     * @throws \Exception
     */
    public function evaluate(FilterInstance $filterInstance, $userModel = null, $groupModel = null, $roleModel = null): bool
    {
        $this->setModels($userModel, $groupModel, $roleModel);
        
        $filter = $this->repository->getByAlias($filterInstance->alias());
        $filter = $this->overrideModels($filter);
        
        if(!$filter->hasModel()) {
            return false;
        }
        return $filter->evaluate($filterInstance->settings());
    }

    /**
     * @param User|null $user
     * @param Group|null $group
     * @param Role|null $role
     */
    private function setModels(?User $user, ?Group $group, ?Role $role) {
        if($user !== null) {
            $this->setUser($user);
        }
        if($group !== null) {
            $this->setGroup($group);
        }
        if($role !== null) {
            $this->setRole($role);
        }
    }

    /**
     * @param $filter
     * @return mixed
     * @throws \Exception
     */
    private function overrideModels($filter) {
        if($filter instanceof UserFilter && $this->user !== null) {
            $filter->setModel($this->user);
        } if($filter instanceof GroupFilter && $this->group !== null) {
            $filter->setModel($this->group);
        } if($filter instanceof RoleFilter && $this->role !== null) {
            $filter->setModel($this->role);
        }
        return $filter;
    }

}
