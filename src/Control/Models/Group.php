<?php

namespace BristolSU\Support\Control\Models;

use BristolSU\Support\Control\Contracts\Models\Group as GroupContract;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\Control\Contracts\Repositories\Tags\GroupTag as GroupTagRepository;
use BristolSU\Support\Control\Contracts\Repositories\User as UserRepository;
use Illuminate\Support\Collection;

/**
 * Class Group
 * @package BristolSU\Support\Control\Models
 */
class Group extends Model implements GroupContract
{

    /**
     * @return mixed|null
     */
    public function getAuthIdentifier()
    {
        return $this->id();
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
     * Name of the group
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Contact email address for the group
     *
     * @return string|null
     */
    public function email(): ?string
    {
        return $this->email;
    }

    /**
     * ID of the group
     *
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * Data platform ID of the group
     *
     * @return int
     */
    public function dataPlatformId(): int
    {
        return $this->unioncloud_id;
    }

    /**
     * Members of the group
     *
     * @return Collection
     */
    public function members(): Collection
    {
        app(UserRepository::class)->getThroughGroup($this);
    }

    /**
     * Roles belonging to the group
     *
     * @return Collection
     */
    public function roles(): Collection
    {
        app(RoleRepository::class)->allThroughGroup($this);
    }

    /**
     * Tags the group is tagged with
     *
     * @return Collection
     */
    public function tags(): Collection
    {
        app(GroupTagRepository::class)->allThroughGroup($this);
    }
}
