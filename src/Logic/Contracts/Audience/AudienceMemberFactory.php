<?php

namespace BristolSU\Support\Logic\Contracts\Audience;

use BristolSU\Support\Control\Contracts\Models\User;

/**
 * Interface AudienceFactory
 * @package BristolSU\Support\Logic\Contracts
 */
interface AudienceMemberFactory
{
    public function fromUser(User $user);

}
