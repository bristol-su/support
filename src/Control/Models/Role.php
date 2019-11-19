<?php

namespace BristolSU\Support\Control\Models;

use BristolSU\Support\Control\Contracts\Models\Group as GroupContract;
use BristolSU\Support\Control\Contracts\Models\Position;
use BristolSU\Support\Control\Contracts\Models\Role as RoleContract;
use BristolSU\Support\Control\Contracts\Repositories\Tags\RoleTag;
use Illuminate\Support\Collection;

/**
 * Class Role
 * @package BristolSU\Support\Control\Models
 */
class Role extends Model implements RoleContract
{

    /**
     * @return mixed|null
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * @return string|void
     */
    public function getAuthPassword()
    {
    }

    /**
     * @return string|void
     */
    public function getRememberToken()
    {
    }

    /**
     * @return string|void
     */
    public function getRememberTokenName()
    {
    }

    /**
     * @param string $value
     */
    public function setRememberToken($value)
    {
        //
    }

    /**
     * ID of the position
     *
     * @return mixed
     */
    public function positionId()
    {
        return $this->position_id;
    }

    /**
     * ID of the group
     *
     * @return mixed
     */
    public function groupId()
    {
        return $this->group_id;
    }

    /**
     * Custom name of the position.
     *
     * This does not need to be the same as the actual position name. It may instead be anything you like, to allow for
     * more granular control over the positions and roles owned by an individual, whilst not creating too many positions.
     *
     * @return string
     */
    public function positionName(): string
    {
        return $this->position()->name();
    }

    /**
     * Position belonging to the role
     *
     * @return Position
     */
    public function position(): Position
    {
        return app(\BristolSU\Support\Control\Contracts\Repositories\Position::class)->getById($this->positionId());
    }

    /**
     * Group belonging to the role
     *
     * @return GroupContract
     */
    public function group(): GroupContract
    {
        return app(\BristolSU\Support\Control\Contracts\Repositories\Group::class)->getById($this->groupId());
    }

    /**
     * Users who occupy the role
     *
     * @return Collection
     */
    public function users(): Collection
    {
        return app(\BristolSU\Support\Control\Contracts\Repositories\User::class)->allThroughRole($this);
    }

    /**
     * Tags the role is tagged with
     *
     * @return Collection
     */
    public function tags(): Collection
    {
        return app(RoleTag::class)->allThroughRole($this);
    }

    /**
     * Get the ID of the role
     *
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }
}
