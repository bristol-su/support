<?php

namespace BristolSU\Support\Logic\Audience;

use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory as AudienceMemberFactoryContract;

/**
 * Creates an audience member
 */
class AudienceMemberFactory implements AudienceMemberFactoryContract
{

    /**
     * Create an audience member from a user
     * 
     * @param User $user User to create the audience member around
     * 
     * @return AudienceMember
     */
    public function fromUser(User $user): AudienceMember {
        return new AudienceMember($user);
    }

}
