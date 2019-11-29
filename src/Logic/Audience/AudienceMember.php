<?php

namespace BristolSU\Support\Logic\Audience;

use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Control\Contracts\Models\User;
use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\Logic\Facade\LogicTester;
use BristolSU\Support\Logic\Logic;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;

class AudienceMember implements Arrayable, Jsonable
{

    /**
     * @var User
     */
    private $user;

    private $canBeUser;

    /**
     * @var Collection
     */
    private $roles;

    /**
     * @var Collection
     */
    private $groups;
    
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function groups()
    {
        if ($this->groups == null) {
            $this->groups = collect(app(GroupRepository::class)->allThroughUser($this->user));
        }
        return $this->groups;
    }

    public function roles()
    {
        if ($this->roles == null) {
            $this->roles = collect(app(RoleRepository::class)->allThroughUser($this->user));
        }
        return $this->roles;
    }

    public function user()
    {
        return $this->user;
    }

    public function canBeUser()
    {
        return ($this->canBeUser??true);
    }
    
    public function filterForLogic(Logic $logic)
    {
        $this->canBeUser = LogicTester::evaluate($logic, $this->user);
        
        $this->groups = $this->groups()->filter(function(Group $group) use ($logic) {
            return LogicTester::evaluate($logic, $this->user, $group);
        })->values();

        $this->roles = $this->roles()->filter(function(Role $role) use ($logic) {
            return LogicTester::evaluate($logic, $this->user, $role->group(), $role);
        })->values();
    }
    
    public function hasAudience()
    {
        return $this->canBeUser() || count($this->groups) > 0 || count($this->roles) > 0;
    }


    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'user' => $this->user(),
            'can_be_user' => $this->canBeUser(),
            'groups' => $this->groups(),
            'roles' => $this->roles()->map(function(Role $role) {
                $role->group = $role->group();
                $role->position = $role->position();
                return $role;
            })
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function __toString()
    {
        return $this->toJson();
    }
}