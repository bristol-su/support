<?php


namespace BristolSU\Support\Control\Contracts\Repositories;


use BristolSU\Support\Control\Contracts\Models\User as UserModelContract;

/**
 * Interface User
 * @package BristolSU\Support\Control\Contracts\Repositories
 */
interface User
{

    /**
     * @param $dataPlatformId
     * @return UserModelContract
     */
    public function findOrCreateByDataId($dataPlatformId) : UserModelContract;

    /**
     * @param $id
     * @return UserModelContract
     */
    public function getById($id): UserModelContract;

    public function all();

    /**
     * @param $dataPlatformId
     * @return UserModelContract
     */ 
    public function findByDataId($dataPlatformId) : UserModelContract;

}
