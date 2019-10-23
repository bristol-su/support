<?php


namespace BristolSU\Support\Control\Models;


use BristolSU\Support\Control\Contracts\Models\User as UserContract;

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
    
}
