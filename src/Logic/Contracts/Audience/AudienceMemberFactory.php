<?php

namespace BristolSU\Support\Logic\Contracts\Audience;

use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Logic\Audience\AudienceMember;

/**
 * Create an audience member
 */
interface AudienceMemberFactory
{

    /**
     * Create an audience member from a user
     * 
     * @param User $user User to create the audience member from
     * 
     * @return AudienceMember
     */
    public function fromUser(User $user);

}
