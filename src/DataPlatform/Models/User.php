<?php


namespace BristolSU\Support\DataPlatform\Models;


use BristolSU\Support\DataPlatform\Contracts\Models\User as UserContract;

/**
 * Class User
 * @package BristolSU\Support\DataPlatform\Models
 */
class User extends Model implements UserContract
{

    /**
     * @return mixed|null
     */
    public function id()
    {
        return $this->uid;
    }

    /**
     * @return mixed|null
     */
    public function forename()
    {
        return $this->forename;
    }

    /**
     * @return mixed|null
     */
    public function surname()
    {
        return $this->surname;
    }

    /**
     * @return mixed|null
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * @return mixed|null
     */
    public function studentId()
    {
        return $this->id;
    }


}
