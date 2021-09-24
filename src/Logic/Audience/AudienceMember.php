<?php

namespace BristolSU\Support\Logic\Audience;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Logic\Facade\LogicTester;
use BristolSU\Support\Logic\Logic;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;

/**
 * Represents a user and their roles/memberships, and allows for logic filtering.
 */
class AudienceMember implements Arrayable, Jsonable
{
    /**
     * Holds the user for which the audience member is about.
     *
     * @var User
     */
    private $user;

    /**
     * Is the user themselves in the logic group?
     *
     * @var bool
     */
    private $canBeUser = true;

    /**
     * Roles the user owns/that're in the logic group.
     *
     * @var Collection
     */
    private $roles;

    /**
     * Groups the user owns/that're in the logic group.
     *
     * @var Collection
     */
    private $groups;

    /**
     * @param User $user User to construct the audience member with
     */
    public function __construct(User $user, ?Collection $groups = null, ?Collection $roles = null)
    {
        $this->user = $user;
        $this->groups = $groups ?? collect();
        $this->roles = $roles ?? collect();
    }

    /**
     * Get all groups for which the user has a membership to.
     *
     * @return Collection
     */
    public function groups()
    {
        return $this->groups;
    }

    public function setGroups(Collection $groups)
    {
        $this->groups = $groups;
    }

    public function setRoles(Collection $roles)
    {
        $this->roles = $roles;
    }

    /**
     * Get all roles which the user is in.
     *
     * @return Collection
     */
    public function roles()
    {
        return $this->roles->map(function (Role $role) {
                $role->group = $role->group();
                $role->position = $role->position();
                return $role;
            }) ?? collect();
    }

    /**
     * Get the user the audience member is about.
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
    public function canBeUser(): bool
    {
        return $this->canBeUser;
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
     * Convert the object to a string, a JSON representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}
