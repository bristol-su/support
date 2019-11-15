<?php


namespace BristolSU\Support\Control\Models;


use BristolSU\Support\Control\Contracts\Models\User as UserContract;
use BristolSU\Support\Control\Contracts\Repositories\Tags\UserTag;
use Illuminate\Support\Collection;

/**
 * Class User
 * @package BristolSU\Support\Control\Models
 */
class User extends Model implements UserContract
{

    /**
     * @return mixed|null
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return mixed|null
     */
    public function dataPlatformId()
    {
        return $this->uc_uid;
    }

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
     * @param $value
     */
    public function setRememberToken($value)
    {
        //
    }

    /**
     * Tags the user is tagged with
     *
     * @return Collection
     */
    public function tags(): Collection
    {
        return app(UserTag::class)->allThroughUser($this);
    }

    /**
     * Roles the user owns
     *
     * @return \Illuminate\Support\Collection
     */
    public function roles(): \Illuminate\Support\Collection
    {
        return app(\BristolSU\Support\Control\Contracts\Repositories\Role::class)->allThroughUser($this);
    }

    /**
     * Groups the user is a member of
     *
     * @return \Illuminate\Support\Collection
     */
    public function groups(): \Illuminate\Support\Collection
    {
        return app(\BristolSU\Support\Control\Contracts\Repositories\Group::class)->allThroughUser($this);
    }
}
