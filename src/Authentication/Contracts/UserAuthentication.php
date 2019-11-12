<?php


namespace BristolSU\Support\Authentication\Contracts;


use BristolSU\Support\User\User;

/**
 * Interface UserAuthentication
 * @package BristolSU\Support\Authentication\Contracts
 */
interface UserAuthentication
{

    /**
     * @return User
     */
    public function getUser(): ?User;

    /**
     * @param User $user
     * @return mixed
     */
    public function setUser(User $user);
    
}