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
use BristolSU\Support\User\User;

class FilterTester implements FilterTesterContract
{

    private $user;
    private $group;
    private $role;
    
    /**
     * @var FilterRepository
     */
    private $repository;

    public function __construct(FilterRepository $repository)
    {
        $this->repository = $repository;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function setGroup(Group $group)
    {
        $this->group = $group;
    }

    public function setRole(Role $role)
    {
        $this->role = $role;
    }
    
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
