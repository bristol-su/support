<?php


namespace BristolSU\Support\Control\Contracts\Models;


use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Interface User
 * @package BristolSU\Support\Control\Contracts\Models
 */
interface User extends Authenticatable
{

    /**
     * @return mixed
     */
    public function id();

    /**
     * @return mixed
     */
    public function dataPlatformId();

}
