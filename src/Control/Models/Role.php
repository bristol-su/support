<?php

namespace BristolSU\Support\Control\Models;

use BristolSU\Support\Control\Contracts\Models\Group as GroupContract;
use BristolSU\Support\Control\Contracts\Models\Role as RoleContract;

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

}
