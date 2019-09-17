<?php


namespace BristolSU\Support\Control\Models;


use BristolSU\Support\Control\Contracts\Models\User as UserContract;

class User extends Model implements UserContract
{

    public function id()
    {
        return $this->id;
    }

    public function dataPlatformId()
    {
        return $this->uc_uid;
    }

}
