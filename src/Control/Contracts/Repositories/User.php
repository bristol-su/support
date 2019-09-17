<?php


namespace BristolSU\Support\Control\Contracts\Repositories;


use BristolSU\Support\Control\Contracts\Models\User as UserModelContract;

interface User
{

    public function findOrCreateByDataId($dataPlatformId) : UserModelContract;

}
