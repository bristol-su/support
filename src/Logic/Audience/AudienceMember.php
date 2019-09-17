<?php

namespace BristolSU\Support\Logic\Audience;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\Logic\Facade\LogicTester;
use BristolSU\Support\Logic\Logic;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;

/**
 * Represents a user and their roles/memberships, and allows for logic filtering
 */
class AudienceMember implements Arrayable, Jsonable
{

    /**
     * Holds the user for which the audience member is about
     * 
     * @var User
     */
    private $user;

    /**
     * Is the user themselves in the logic group?
     * 
     * @var bool
     */
    private $canBeUser;

    /**
     * Roles the user owns/that're in the logic group
     * 
     * @var Collection
     */
    private $roles;

    /**
     * Groups the user owns/that're in the logic group
     * 
     * @var Collection
     */
    private $groups;

    /**
     * @param User $user User to construct the audience member with
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get all groups for which the user has a membership to
     * 
     * @return Collection
     */
    public function groups()
    {
        if ($this->groups == null) {
            $this->groups = collect(app(GroupRepository::class)->allThroughUser($this->user));
        }
        return $this->groups;
    }

    /**
     * Get all roles which the user is in
     * 
     * @return Collection
     */
    public function roles()
    {
        if ($this->roles == null) {
            $this->roles = collect(app(RoleRepository::class)->allThroughUser($this->user))->map(function(Role $role) {
                $role->group = $role->group();
                $role->position = $role->position();
                return $role;
            });
        }
        return $this->roles;
    }

    /**
     * Get the user the audience member is about
     * 
     * @return User
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * Can the user themselves be in the logic group?
     * 
     * @return bool
     */
    public function canBeUser()
    {
        return ($this->canBeUser??true);
    }

    /**
     * Filter the audience member down to those in the logic group
     * 
     * If passed a logic group, the audience member will only then contain roles and groups which are in the given
     * logic group with the user. It will also set canBeUser, as to whether just the user is in the logic group (without
     * their roles or groups).
     * 
     * @param Logic $logic Logic group to test
     * 
     * @return void
     */
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

    /**
     * Does the audience member have an audience at all?
     * 
     * After filtering for logic, this function will return true if the user can access the logic group
     * in any way, or false or otherwise.
     * 
     * @return bool
     */
    public function hasAudience()
    {
        return $this->canBeUser() || count($this->groups) > 0 || count($this->roles) > 0;
    }


    /**
     * Get the instance as an array.
     *
     * Returns the audience member in the following form
     * [
     *      'user' => \BristolSU\ControlDB\Models\User(),
     *      'can_be_user' => true/false, // Does just the user belong in the logic group?
     *      'groups' => [ // All groups for which the user has a membership that belongs in the logic group
     *          \BristolSU\ControlDB\Models\Group(),
     *          ...
     *      ],
     *      'roles' => [ // All roles for which the user belongs to and is in the logic group
     *          \BristolSU\ControlDB\Models\Role()
     *      ]
     * ]
     * 
     * @return array
     */
    public function toArray()
    {
        return [
            'user' => $this->user(),
            'can_be_user' => $this->canBeUser(),
            'groups' => $this->groups(),
            'roles' => $this->roles()
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     * 
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Convert the object to a string, a JSON representation
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}
