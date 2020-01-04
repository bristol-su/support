<?php

namespace BristolSU\Support\Logic\Contracts\Audience;

use BristolSU\ControlDB\Contracts\Models\User;

/**
 * Interface AudienceFactory
 * @package BristolSU\Support\Logic\Contracts
 */
interface AudienceMemberFactory
{
    public function fromUser(User $user);

}
