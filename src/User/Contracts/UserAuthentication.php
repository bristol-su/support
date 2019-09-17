<?php

namespace BristolSU\Support\User\Contracts;

use BristolSU\Support\User\User;

/**
 * Contract to set or resolve a logged in user
 */
interface UserAuthentication
{

    /**
     * Get the currently set user
     * 
     * @return User|null Returns the user, or null if no user found
     */
    public function getUser(): ?User;

    /**
     * Set the currently logged in user
     * 
     * @param User $user User to log in
     * @return void
     */
    public function setUser(User $user);
    
}