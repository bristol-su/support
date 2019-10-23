<?php


namespace BristolSU\Support\User\Contracts;


/**
 * Interface UserRepository
 * @package BristolSU\Support\User\Contracts
 */
interface UserRepository
{

    /**
     * @param $identity
     * @return mixed
     */
    public function getWhereIdentity($identity);

    /**
     * @param $email
     * @return mixed
     */
    public function getWhereEmail($email);

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes);

    public function all();

}
